<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductColorImage;
use App\Models\ProductImage;
use App\Models\ProductSize;
use App\Models\ProductVariant;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function index()
    {
        $items = Product::with("brand")->orderBy("id", "desc")->get();
        return view("admin.product.index", compact("items")); 
    }

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:255|unique:products,name,' . $id . ',id',
            'code' => 'required|string|max:50|unique:products,code,' . $id . ',id',
            'thumbnail' => 'required|string',
            'brand_id' => 'required|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'summary' => 'required|string|min:10',
            'is_new' => 'boolean',
            'is_sale' => 'boolean',
            'is_bestseller' => 'boolean',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'color_id' => 'required|exists:colors,id',
            'sizes' => 'required|array',
            'sizes.*.selected' => 'required|boolean',
            'color_images' => 'nullable|string',
        ];

        $messages = [
            'name.required' => 'Tên sản phẩm không được để trống!',
            'name.unique' => 'Tên sản phẩm đã tồn tại!',
            'name.max' => 'Tên sản phẩm không quá 255 ký tự!',
            'code.required' => 'Mã sản phẩm không được để trống!',
            'code.unique' => 'Mã sản phẩm đã tồn tại!',
            'code.max' => 'Mã sản phẩm không quá 50 ký tự!',
            'thumbnail.required' => 'Ảnh đại diện không được để trống!',
            'brand_id.required' => 'Vui lòng chọn thương hiệu!',
            'brand_id.exists' => 'Thương hiệu không tồn tại!',
            'price.required' => 'Giá bán không được để trống!',
            'price.numeric' => 'Giá bán phải là số!',
            'price.min' => 'Giá bán phải lớn hơn hoặc bằng 0!',
            'summary.required' => 'Mô tả ngắn không được để trống!',
            'summary.min' => 'Mô tả ngắn phải có ít nhất 10 ký tự!',
            'categories.required' => 'Vui lòng chọn ít nhất một danh mục!',
            'categories.*.exists' => 'Danh mục không tồn tại!',
            'color_id.required' => 'Vui lòng chọn một màu sắc!',
            'color_id.exists' => 'Màu sắc không tồn tại!',
            'sizes.required' => 'Vui lòng chọn ít nhất một size!',
            'sizes.*.selected.required' => 'Trạng thái chọn size không được để trống!',
            'color_images.string' => 'Ảnh màu sắc phải là chuỗi hợp lệ!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->after(function ($validator) use ($request) {
            $sizes = $request->input('sizes', []);
            $hasSelectedSize = false;

            foreach ($sizes as $sizeData) {
                if (isset($sizeData['selected']) && $sizeData['selected'] == 1) {
                    $hasSelectedSize = true;
                    break;
                }
            }

            if (!$hasSelectedSize) {
                $validator->errors()->add('sizes', 'Vui lòng chọn ít nhất một size!');
            }
        });

        return $validator;
    }

    public function create()
    {
        $brands = Brand::where('is_active', 1)->get();
        $categories = Category::where('is_active', 1)->get();
        $colors = Color::all();
        $sizes = Size::all();
        return view('admin.product.create', compact('brands', 'categories', 'colors', 'sizes'));
    }

    public function store(Request $request)
    {

        $validator = $this->validates($request);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Create new product
            $product = Product::create([
                'name' => $request->name,
                'code' => $request->code,
                'group_code' => $request->group_code,
                'thumbnail' => $request->thumbnail,
                'brand_id' => $request->brand_id,
                'color_id' => $request->color_id,
                'price' => $request->price,
                'discount' => $request->discount ?? 0,
                'summary' => $request->summary,
                'description' => $request->description,
                'is_new' => $request->input('is_new', 0),
                'is_sale' => $request->input('is_sale', 0),
                'is_bestseller' => $request->input('is_bestseller', 0),
                'status' => 1,
            ]);

            // Attach categories
            if ($request->has('categories')) {
                $product->categories()->attach($request->categories);
            }

            // Save product images
            if ($request->has('color_images') && !empty($request->color_images)) {
                $imagePaths = explode(',', $request->color_images);
                foreach ($imagePaths as $imagePath) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => trim($imagePath),
                    ]);
                }
            }

            // Save product sizes
            if ($request->has('sizes')) {
                foreach ($request->sizes as $sizeId => $sizeData) {
                    if (isset($sizeData['selected']) && $sizeData['selected'] == 1) {
                        ProductSize::create([
                            'product_id' => $product->id,
                            'size_id' => $sizeId,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('product.index')->with('messenge', [
                'style' => 'success',
                'msg' => 'Sản phẩm đã được thêm thành công!',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('messenge', [
                'style' => 'danger',
                'msg' => 'Có lỗi xảy ra khi thêm sản phẩm: ' . $e->getMessage(),
            ])->withInput();
        }
    }

    public function edit($id)
    {
        $product = Product::with(['categories', 'images', 'sizes'])->findOrFail($id);
        $brands = Brand::where('is_active', 1)->get();
        $categories = Category::where('is_active', 1)->get();
        $colors = Color::all();
        $sizes = Size::all();

        $selectedSizes = ProductSize::where('product_id', $product->id)->pluck('size_id')->toArray();
        $colorImages = $product->images->pluck('image')->implode(',');

        return view('admin.product.edit', compact('product', 'brands', 'categories', 'colors', 'sizes', 'selectedSizes', 'colorImages'));
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validates($request, $id);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);
            $product->update([
                'name' => $request->name,
                'code' => $request->code,
                'group_code' => $request->group_code,
                'thumbnail' => $request->thumbnail,
                'brand_id' => $request->brand_id,
                'color_id' => $request->color_id,
                'price' => $request->price,
                'discount' => $request->discount ?? 0,
                'summary' => $request->summary,
                'description' => $request->description,
                'is_new' => $request->input('is_new', 0),
                'is_sale' => $request->input('is_sale', 0),
                'is_bestseller' => $request->input('is_bestseller', 0),
                'status' => $product->status,
            ]);

            $product->categories()->sync($request->categories);

            if ($request->has('color_images') && !empty($request->color_images)) {
                $newImagePaths = explode(',', $request->color_images);
                $existingImagePaths = $product->images->pluck('image')->toArray();

                // Delete images not in the new list
                ProductImage::where('product_id', $product->id)
                ->whereNotIn('image', $newImagePaths)
                ->delete();

                // Add new images
                foreach ($newImagePaths as $imagePath) {
                    if (!in_array($imagePath, $existingImagePaths)) {
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image' => trim($imagePath),
                        ]);
                    }
                }
            } else {
                // If no images provided, delete all existing images
                ProductImage::where('product_id', $product->id)->delete();
            }

            // Update sizes
            if ($request->has('sizes')) {
                // Lấy danh sách size_id được chọn từ request
                $selectedSizeIds = collect($request->sizes)
                    ->filter(function ($sizeData) {
                        return isset($sizeData['selected']) && $sizeData['selected'] == 1;
                    })
                    ->keys()
                    ->toArray();

                // Xóa các kích thước không được chọn (những size_id không có trong $selectedSizeIds)
                ProductSize::where('product_id', $product->id)
                    ->whereNotIn('size_id', $selectedSizeIds)
                    ->delete();

                // Thêm hoặc giữ nguyên các kích thước được chọn
                foreach ($request->sizes as $sizeId => $sizeData) {
                    if (isset($sizeData['selected']) && $sizeData['selected'] == 1) {
                        // Kiểm tra xem kích thước đã tồn tại chưa
                        $exists = ProductSize::where('product_id', $product->id)
                            ->where('size_id', $sizeId)
                            ->exists();

                        // Nếu chưa tồn tại, tạo mới
                        if (!$exists) {
                            ProductSize::create([
                                'product_id' => $product->id,
                                'size_id' => $sizeId,
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->route('product.index')->with('messenge', [
                'style' => 'success',
                'msg' => 'Sản phẩm đã được cập nhật thành công!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('messenge', [
                'style' => 'danger',
                'msg' => 'Có lỗi xảy ra khi cập nhật sản phẩm: ' . $e->getMessage(),
            ])->withInput();
        }
    }

    public function changeStatus(Request $request, $id)
    {
        $item = Product::find($id);

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy bản ghi'], 404);
        }

        $type = $request->input('type');

        switch ($type) {
            case 'is_new':
                $item->is_new = !$item->is_new;
                break;
            case 'is_sale':
                $item->is_sale = !$item->is_sale;
                break;
            case 'is_bestseller':
                $item->is_bestseller = !$item->is_bestseller;
                break;
            case 'status':
                $item->status = $item->status == 1 ? 0 : 1;
                break;
            default:
                return response()->json(['success' => false, 'message' => 'Loại trạng thái không hợp lệ'], 400);
        }

        $item->save();

        return response()->json(['success' => true, 'message' => 'Thay đổi trạng thái thành công']);
    }

    public function destroy($id)
    {
        $item = Product::find($id);

        if (!$item) {
            return redirect()->route('product.index')->with('messenge', [
                'style' => 'danger',
                'msg' => 'Không tìm thấy sản phẩm!',
            ]);
        }

        $item->sizes()->delete();

        $item->images()->delete();

        $item->categories()->detach();

        $item->delete();

        return response()->json(['success' => true, 'message' => 'Xoá sản phẩm thành công']);
    }
}
