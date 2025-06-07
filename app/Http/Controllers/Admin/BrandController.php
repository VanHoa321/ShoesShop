<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function index()
    {
        $items = Brand::where("id", ">", 1)->orderBy("id", "desc")->get();
        return view('admin.brand.index', compact('items'));
    }

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:255|unique:brands,name,' . $id . ',id',
        ];

        $messages = [
            'name.required' => 'Tên thương hiệu không được để trống',
            'name.unique' => 'Tên thương hiệu đã tồn tại',
            'name.max' => 'Tên thương hiệu không quá 255 ký tự',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    public function create()
    {
        return view('admin.brand.create');
    }

    public function store(Request $request)
    {
        $validator = $this->validates($request);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = [
            'name' => $request->name,
            'image' => $request->image ? $request->image : "/storage/photos/1/Avatar/12225935.png",
            'description' => $request->description,
        ];

        Brand::create($data);
        $request->session()->put("messenge", ["style" => "success", "msg" => "Thêm mới thương hiệu thành công"]);
        return redirect()->route("brand.index");
    }

    public function edit($id)
    {
        $edit = Brand::findOrFail($id);
        return view('admin.brand.edit', compact('edit'));
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validates($request, $id);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = [
            'name' => $request->name,
            'image' => $request->image ? $request->image : "/storage/photos/1/Avatar/12225935.png",
            'description' => $request->description,
        ];

        Brand::where('id', $id)->update($data);
        $request->session()->put("messenge", ["style" => "success", "msg" => "Cập nhật thông tin nhà xuất bản thành công"]);
        return redirect()->route("brand.index");
    }

    public function destroy(string $id)
    {
        $destroy = Brand::find($id);
        if ($destroy) {
            $destroy->delete();
            return response()->json(['success' => true, 'message' => 'Xóa thương hiệu thành công']);
        } else {
            return response()->json(['danger' => false, 'message' => 'Thương hiệu này không tồn tại'], 404);
        }
    }
    
    public function changeActive($id){
        $change = Brand::find($id);    
        if ($change) {
            $change->is_active = !$change->is_active;
            $change->save();
            return response()->json(['success' => true, 'message' => 'Thay đổi trạng thái thành công']);
        }
        else {
            return response()->json(['success' => false, 'message' => 'Thay đổi trạng thái không thành công'], 404);
        }
    }
}
