<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $items = Category::orderBy("id", "desc")->get();
        return view('admin.category.index', compact('items'));
    }

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:255|unique:categories,name,' . $id . ',id',
        ];

        $messages = [
            'name.required' => 'Tên danh mục không được để trống',
            'name.unique' => 'Tên danh mục đã tồn tại',
            'name.max' => 'Tên danh mục không quá 255 ký tự',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    public function create()
    {
        return view('admin.category.create');
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

        Category::create($data);
        $request->session()->put("messenge", ["style" => "success", "msg" => "Thêm mới danh mục thành công"]);
        return redirect()->route("category.index");
    }

    public function edit($id)
    {
        $edit = Category::findOrFail($id);
        return view('admin.category.edit', compact('edit'));
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

        Category::where('id', $id)->update($data);
        $request->session()->put("messenge", ["style" => "success", "msg" => "Cập nhật thông tin danh mục thành công"]);
        return redirect()->route("category.index");
    }

    public function destroy(string $id)
    {
        $destroy = Category::find($id);
        if ($destroy) {
            $destroy->delete();
            return response()->json(['success' => true, 'message' => 'Xóa danh mục thành công']);
        } else {
            return response()->json(['danger' => false, 'message' => 'danh mục này không tồn tại'], 404);
        }
    }
    
    public function changeActive($id){
        $change = Category::find($id);    
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
