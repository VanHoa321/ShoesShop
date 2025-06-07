<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductColorImage;
use App\Models\ProductVariant;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function index()
    {
        $items = Product::with("brand")->orderBy("id", "desc")->get();
        return view("admin.product.index", compact("items")); 
    }

    public function create()
    {
        $brands = Brand::where('is_active', 1)->get();
        $categories = Category::where('is_active', 1)->get();
        $colors = Color::all();
        $sizes = Size::all();
        return view('admin.product.create', compact('brands', 'categories', 'colors', 'sizes'));
    }

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:255|unique:products,name,' . $id . ',id',
            'code' => 'required|string|max:50|unique:products,code,' . $id . ',id',
            'thumbnail' => 'required|string',
            'branch_id' => 'required|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'summary' => 'required|string|min:10',
            'is_new' => 'boolean',
            'is_sale' => 'boolean',
            'is_bestseller' => 'boolean',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'selected_color_ids' => 'required|string',
            'variants' => 'required|array',
            'variants.*.*.quantity' => 'required_if:variants.*.*.selected,1|numeric|min:0',
            'variants.*.*.price' => 'required_if:variants.*.*.selected,1|numeric|min:0',
            'variants.*.*.discount' => 'nullable|numeric|min:0|max:100',
            'color_images.*' => 'nullable|string',
        ];

        $messages = [
            'name.required' => 'Tên sản phẩm không được để trống!',
            'name.unique' => 'Tên sản phẩm đã tồn tại!',
            'name.max' => 'Tên sản phẩm không quá 255 ký tự!',
            'code.required' => 'Mã sản phẩm không được để trống!',
            'code.unique' => 'Mã sản phẩm đã tồn tại!',
            'code.max' => 'Mã sản phẩm không quá 50 ký tự!',
            'thumbnail.required' => 'Ảnh đại diện không được để trống!',
            'branch_id.required' => 'Vui lòng chọn thương hiệu!',
            'branch_id.exists' => 'Thương hiệu không tồn tại!',
            'price.required' => 'Giá bán không được để trống!',
            'price.numeric' => 'Giá bán phải là số!',
            'price.min' => 'Giá bán phải lớn hơn hoặc bằng 0!',
            'summary.required' => 'Mô tả ngắn không được để trống!',
            'summary.min' => 'Mô tả ngắn phải có ít nhất 10 ký tự!',
            'categories.required' => 'Vui lòng chọn ít nhất một danh mục!',
            'categories.*.exists' => 'Danh mục không tồn tại!',
            'selected_color_ids.required' => 'Vui lòng chọn ít nhất một màu sắc!',
            'variants.required' => 'Vui lòng chọn ít nhất một biến thể!',
            'variants.*.*.quantity.required_if' => 'Số lượng không được để trống khi chọn size!',
            'variants.*.*.quantity.numeric' => 'Số lượng phải là số!',
            'variants.*.*.quantity.min' => 'Số lượng phải lớn hơn hoặc bằng 0!',
            'variants.*.*.price.required_if' => 'Giá bán không được để trống khi chọn size!',
            'variants.*.*.price.numeric' => 'Giá bán phải là số!',
            'variants.*.*.price.min' => 'Giá bán phải lớn hơn hoặc bằng 0!',
            'variants.*.*.discount.numeric' => 'Giảm giá phải là số!',
            'variants.*.*.discount.min' => 'Giảm giá phải từ 0!',
            'variants.*.*.discount.max' => 'Giảm giá không được vượt quá 100!',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    public function store(Request $request)
    {

        $validator = $this->validates($request);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Tạo sản phẩm
        $product = Product::create([
            'name' => $request->name,
            'code' => $request->code,
            'thumbnail' => $request->thumbnail,
            'branch_id' => $request->branch_id,
            'price' => $request->price,
            'summary' => $request->summary,
            'description' => $request->description,
            'is_new' => $request->has('is_new') ? true : false,
            'is_sale' => $request->has('is_sale') ? true : false,
            'is_bestseller' => $request->has('is_bestseller') ? true : false,
            'status' => 1,
        ]);

        // Gắn danh mục
        $product->categories()->sync($request->categories);

        // Lưu biến thể
        foreach ($request->variants as $colorId => $sizes) {
            foreach ($sizes as $sizeId => $variant) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'color_id' => $colorId,
                    'size_id' => $sizeId,
                    'quantity' => $variant['quantity'],
                    'price' => $variant['price'],
                    'discount' => $variant['discount'] ?? 0,
                ]);
            }
        }

        // Lưu ảnh màu sắc
        if ($request->has('color_images')) {
            foreach ($request->color_images as $colorId => $imagePaths) {
                if (!empty($imagePaths)) {
                    $paths = explode(',', $imagePaths);
                    foreach ($paths as $path) {
                        ProductColorImage::create([
                            'product_id' => $product->id,
                            'color_id' => $colorId,
                            'image' => trim($path),
                        ]);
                    }
                }
            }
        }

        return redirect()->route('product.index')->with('messenge', [
            'style' => 'success',
            'msg' => 'Sản phẩm đã được thêm thành công!',
        ]);
    }
}
