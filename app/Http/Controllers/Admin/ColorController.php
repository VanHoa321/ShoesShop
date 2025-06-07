<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ColorController extends Controller
{

    public function index()
    {
        $items = Color::orderBy("id", "desc")->get();
        return view('admin.color.index', compact('items'));
    }

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:50|unique:colors,name,' . $id,
            'code' => 'required|string|max:50|unique:colors,code,' . $id,
        ];

        $messages = [
            'name.required' => 'Tên màu không được để trống',
            'name.unique' => 'Tên màu đã tồn tại',
            'name.max' => 'Tên màu không quá 50 ký tự',

            'code.required' => 'Mã màu không được để trống',
            'code.unique' => 'Mã màu đã tồn tại',
            'code.max' => 'Mã màu không quá 50 ký tự',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    public function create()
    {
        return view('admin.color.create');
    }

    public function store(Request $request)
    {
        $validator = $this->validates($request);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Color::create([
            'name' => $request->name,
            'code' => $request->code,
        ]);

        $request->session()->put("messenge", ["style" => "success", "msg" => "Thêm mới màu sắc thành công"]);
        return redirect()->route("color.index");
    }

    public function edit($id)
    {
        $edit = Color::findOrFail($id);
        return view('admin.color.edit', compact('edit'));
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validates($request, $id);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Color::where('id', $id)->update([
            'name' => $request->name,
            'code' => $request->code,
        ]);

        $request->session()->put("messenge", ["style" => "success", "msg" => "Cập nhật màu sắc thành công"]);
        return redirect()->route("color.index");
    }

    public function destroy(string $id)
    {
        $destroy = Color::find($id);
        if ($destroy) {
            $destroy->delete();
            return response()->json(['success' => true, 'message' => 'Xóa màu sắc thành công']);
        } else {
            return response()->json(['danger' => false, 'message' => 'Màu sắc không tồn tại']);
        }
    }
}
