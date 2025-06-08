<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Size;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $query = Product::query();

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

        $products = $query->with(['brand', 'color', 'categories'])->paginate(1);
        $categories = Category::withCount('products')->get();
        $brands = Brand::withCount('products')->get();
        $colors = Color::all();
        $sizes = Size::all();

        return view('frontend.product.index', compact('products', 'categories', 'brands', 'colors', 'sizes', 'sort'));
    }

    public function details($id)
    {
        $product = Product::with(['sizes', 'color', 'images'])->findOrFail($id);
        $relatedProducts = Product::where('group_code', $product->group_code)
            ->where('id', '!=', $id)
            ->with(['color'])
            ->get();
        $sizes = ProductSize::where('product_id', $id)->with('size')->get();

        return view('frontend.product.details', compact('product', 'relatedProducts', 'sizes'));
    }
}
