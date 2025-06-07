<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    public function index()
    {
        $items = Tag::orderBy("id", "desc")->get();
        return view("admin.tag.index", compact("items"));
    }

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:50|unique:tags,name,' . $id . ',id',
        ];

        $messages = [
            'name.required' => 'Tên thẻ tag không được để trống',
            'name.unique' => 'Tên thẻ tag đã tồn tại',
            'name.max' => 'Tên thẻ tag không quá 50 ký tự.',   
        ];

        return Validator::make($request->all(), $rules, $messages);
    }


    public function create()
    {
        return view("admin.tag.create");
    }

    public function store(Request $request){
        $validator = $this->validates($request);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = [
            'name' => $request->name,
            'description'=> $request->description,
        ];
        $create = new Tag();
        $create->create($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Thêm mới thẻ tag thành công"]);
        return redirect()->route("tag.index");
    }

    public function edit($id)
    {
        $edit = Tag::where("id", $id)->first();
        return view("admin.tag.edit", compact("edit"));
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validates($request, $id);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = [
            'name' => $request->name,
            'description'=> $request->description,
        ];
        $edit = Tag::where('id', $id)->first();
        $edit->update($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Cập nhật thẻ tag thành công"]);
        return redirect()->route("tag.index");
    }

    public function destroy(string $id)
    {
        $destroy = Tag::find($id);
        if ($destroy) {
            $destroy->delete();
            return response()->json(['success' => true, 'message' => 'Xóa thẻ tag thành công']);
        } else {
            return response()->json(['danger' => false, 'message' => 'Thẻ tag không tồn tại'], 404);
        }
    }

    public function changeActive($id){
        $change = Tag::find($id);    
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
