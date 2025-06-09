<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Favourite;
use App\Models\Product;
use App\Models\ProductRating;
use App\Models\ProductSize;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $query = Product::withCount('ratings')->withAvg('ratings', 'rating')->where('status', 1);

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('categories')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->whereIn('category_id', $request->categories);
            });
        }

        if ($request->has('brands')) {
            $query->whereIn('brand_id', $request->brands);
        }

        if ($request->has('ratings') && is_array($request->ratings)) {
            $query->having(function ($q) use ($request) {
                foreach ($request->ratings as $rating) {
                    if (is_numeric($rating)) {
                        $min = (int)$rating;
                        $max = ($min == 5) ? 5.0 : ($min + 0.999);
                        $q->orHavingRaw('ratings_avg_rating BETWEEN ? AND ?', [$min, $max]);
                    }
                }
            });
        }

        if ($request->has('is_new')) {
            $query->where('is_new', 1);
        }

        if ($request->has('is_bestseller')) {
            $query->where('is_bestseller', 1);
        }

        if ($request->has('is_sale')) {
            $query->where('is_sale', 1);
        }

        if ($request->has('colors')) {
            $query->whereIn('color_id', (array) $request->colors);
        }

        if ($request->has('sizes')) {
            $query->whereHas('sizes', function ($q) use ($request) {
                $q->whereIn('size_id', $request->sizes);
            });
        }

        if ($request->has(['min_price', 'max_price'])) {
            $query->whereBetween('price', [$request->min_price ?: 0, $request->max_price ?: 1000]);
        }

        $sort = $request->sort ?? 'default';
        switch ($sort) {
            case 'latest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'bestseller':
                $query->orderBy('is_bestseller', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->orderBy('id', 'asc');
        }

        $products = $query->with(['brand', 'color', 'categories'])
            ->withCount(['ratings as count_rating'])
            ->withAvg(['ratings as average_rating' ], 'rating')
            ->paginate(12);

        $products->getCollection()->transform(function ($product) {
            $product->is_favourite = Favourite::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->exists();
            return $product;
        });

        $categories = Category::withCount('products')->get();
        $brands = Brand::withCount('products')->get();
        $colors = Color::all();
        $sizes = Size::all();

        return view('frontend.product.index', compact('products', 'categories', 'brands', 'colors', 'sizes', 'sort'));
    }

    public function details($id)
    {
        $product = Product::with(['sizes', 'color', 'images'])->findOrFail($id);
        $sizes = ProductSize::where('product_id', $id)->with('size')->get();
        $categoryIds = $product->categories->pluck('id')->toArray();
        $groupProducts = Product::where('group_code', $product->group_code)
        ->where('id', '!=', $id)
        ->with(['color'])
        ->get();

        $relatedProducts = Product::query()
            ->where('id', '!=', $id)
            ->where(function ($query) use ($product, $categoryIds) {
                $query->where('brand_id', $product->brand_id)
                    ->orWhereHas('categories', function ($q) use ($categoryIds) {
                        $q->whereIn('category_id', $categoryIds);
                    });
            })
            ->with(['color', 'categories', 'sizes'])
            ->orderByRaw('brand_id = ? DESC', [$product->brand_id])
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get()
            ->map(function ($relatedProduct) {
                $relatedProduct->is_favourite = Favourite::where('user_id', Auth::id())
                    ->where('product_id', $relatedProduct->id)
                    ->exists();
                $relatedProduct->rating_count = ProductRating::where('product_id', $relatedProduct->id)->count();
                $relatedProduct->average_rating = $relatedProduct->rating_count > 0 ? ProductRating::where('product_id', $relatedProduct->id)->avg('rating') : 0;
                return $relatedProduct;
            });

        $ratingsQuery = ProductRating::where('product_id', $id);
        $product->rating_count = $ratingsQuery->count();
        $product->average_rating = $product->rating_count > 0 ? $ratingsQuery->avg('rating') : 0;
        $ratings = $ratingsQuery->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(3);
        $product->is_favourite = Favourite::where('user_id', Auth::id())->where('product_id', $id)->exists();

        return view('frontend.product.details', compact('product', 'groupProducts', 'relatedProducts', 'sizes', 'ratings'));
    }

    public function storeRating(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|max:1000',
        ], [
            'rating.required' => 'Vui lòng chọn số sao đánh giá.',
            'content.required' => 'Vui lòng nhập nội dung đánh giá.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $rating = ProductRating::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'content' => $request->content,
            'status' => 0,
        ]);

        $rating->load('user');

        $reviewHtml = view('layout.partial.single_review', ['review' => $rating])->render();

        return response()->json([
            'success' => true,
            'message' => 'Đánh giá sản phẩm thành công!',
            'review_html' => $reviewHtml,
        ]);
    }
}
