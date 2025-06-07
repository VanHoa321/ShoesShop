<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SizeController extends Controller
{
    public function index()
    {
        $items = Size::orderBy("id", "desc")->get();
        return view('admin.size.index', compact('items'));
    }

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:255|unique:sizes,name,' . $id . ',id',
        ];

        $messages = [
            'name.required' => 'Tên size giày không được để trống',
            'name.unique' => 'Tên size giày đã tồn tại',
            'name.max' => 'Tên size giày không quá 255 ký tự',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    public function create()
    {
        return view('admin.size.create');
    }

    public function store(Request $request)
    {
        $validator = $this->validates($request);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = [
            'name' => $request->name,
            'description' => $request->description,
        ];

        Size::create($data);
        $request->session()->put("messenge", ["style" => "success", "msg" => "Thêm mới size giày thành công"]);
        return redirect()->route("size.index");
    }

    public function edit($id)
    {
        $edit = Size::findOrFail($id);
        return view('admin.size.edit', compact('edit'));
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validates($request, $id);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = [
            'name' => $request->name,
            'description' => $request->description,
        ];

        Size::where('id', $id)->update($data);
        $request->session()->put("messenge", ["style" => "success", "msg" => "Cập nhật size giày thành công"]);
        return redirect()->route("size.index");
    }

    public function destroy(string $id)
    {
        $destroy = Size::find($id);
        if ($destroy) {
            $destroy->delete();
            return response()->json(['success' => true, 'message' => 'Xóa size giày thành công']);
        } 
        else {
            return response()->json(['danger' => false, 'message' => 'Size giày này không tồn tại']);
        }
    }
}
