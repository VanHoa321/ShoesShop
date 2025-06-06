<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Role;
use Illuminate\Http\Request;

class AdminSiderbarController extends Controller
{
    public function index()
    {
        $items = Menu::with('parents')->orderBy("id", "desc")->get();
        return view("admin.admin-sidebar.index", compact("items"));
    }

    public function create()
    {
        $items = Menu::where('is_active', true)->where('parent', 0)->orderBy('id', 'desc')->get();
        $roles = Role::all();
        return view("admin.admin-sidebar.create", compact("items","roles"));
    }

    public function store(Request $request)
    {
        $level = ($request->parent == 0) ? 1 : 2;
        $data = [
            'name' => $request->name,
            'level' => $level,
            'parent'=> $request->parent,
            'order'=> $request->order,
            'icon'=> $request->icon,
            'route'=> $request->route,
        ];
        $create = new Menu();
        $create->create($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Thêm mới Menu thành công"]);
        return redirect()->route("admin-sidebar.index");
    }

    public function edit($id)
    {
        $items = Menu::where('is_active', true)->where('parent', 0)->where('id', '!=', $id)->orderBy('id', 'desc')->get();
        $edit = Menu::find($id);
        $roles = Role::all();
        return view("admin.admin-sidebar.edit", compact("items","edit","roles"));
    }

    public function update(Request $request, $id)
    {
        $level = ($request->parent == 0) ? 1 : 2;
        $data = [
            'name' => $request->name,
            'level' => $level,
            'parent'=> $request->parent,
            'order'=> $request->order,
            'icon'=> $request->icon,
            'route'=> $request->route,
        ];
        Menu::where('id', $id)->update($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Cập nhật Menu thành công"]);
        return redirect()->route("admin-sidebar.index");
    }

    public function destroy($id)
    {
        $destroy = Menu::find($id);
        if ($destroy) {
            $destroy->delete();
            return response()->json(['success' => true, 'message' => 'Xóa menu thành công']);
        } else {
            return response()->json(['danger' => false, 'message' => 'Menu không tồn tại'], 404);
        }
    }

    public function changeActive($id)
    {

        $change = Menu::find($id);    
        if ($change) {
            $change->is_active = !$change->is_active;
            $change->save();

            return response()->json(['success' => true, 'message' => 'Thay đổi trạng thái thành công']);
        } else {
            return response()->json(['success' => false, 'message' => 'Thay đổi trạng thái không thành công'], 404);
        }
    }
}
