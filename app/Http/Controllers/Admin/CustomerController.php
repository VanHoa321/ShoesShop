<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index()
    {
        $items = User::with("role")->where("role_id", 2)->orderBy("id", "desc")->get();
        return view("admin.customer.index", compact("items")); 
    }

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|min:3|max:50',
            'email' => 'required|email|max:100|unique:users,email,' . $id . ',id',
            'phone' => 'required|string|regex:/^0[0-9]{9}$/|unique:users,phone,' . $id . ',id',
        ];

        if (!$id) {
            $rules['user_name'] = 'required|string|min:3|max:30|unique:users,user_name';
            $rules['password'] = 'required|min:6';
        }

        $messages = [
            'name.required' => 'Họ tên không được để trống!',
            'name.min' => 'Họ tên phải có ít nhất :min ký tự!',
            'name.max' => 'Họ tên tối đa :max ký tự!',

            'user_name.required' => 'Tên đăng nhập không được để trống!',
            'user_name.min' => 'Tên đăng nhập phải có ít nhất :min ký tự!',
            'user_name.max' => 'Tên đăng nhập tối đa :max ký tự!',
            'user_name.unique' => 'Tên đăng nhập đã tồn tại!',

            'email.required' => 'Email không được để trống!',
            'email.email' => 'Email không hợp lệ!',
            'email.max' => 'Email tối đa :max ký tự!',
            'email.unique' => 'Email đã tồn tại!',

            'phone.required' => 'Số điện thoại không được để trống!',
            'phone.regex' => 'Số điện thoại phải có 10 chữ số và bắt đầu bằng số 0!',
            'phone.unique' => 'Số điện thoại đã tồn tại!',

            'password.required' => 'Mật khẩu không được để trống!',
            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự!',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    public function create()
    {
        return view("admin.customer.create");
    }

    public function store(Request $request)
    {
        $validator = $this->validates($request);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $userData = [
            "name" => $request->name,
            "user_name" => $request->user_name,
            "email" => $request->email,
            "phone" => $request->phone,
            "address" => $request->address,
            "avatar" => $request->avatar ? $request->avatar : "/storage/photos/1/Avatar/12225935.png",
            "password" => $request->password,
            "role_id" => 2
        ];

        $create = User::create($userData);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Thêm mới người dùng thành công"]);
        return redirect()->route("customer.index");
    }

    public function edit($id)
    {
        $edit = User::find($id);
        return view("admin.customer.edit", compact("edit"));
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validates($request, $id);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $userData = [
            "name" => $request->name,
            "email" => $request->email,
            "phone" => $request->phone,
            "avatar" => $request->avatar ? $request->avatar : "/storage/photos/1/Avatar/12225935.png",
            "role_id" => 2
        ];

        User::where('id', $id)->update($userData);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Cập nhật người dùng thành công"]);
        return redirect()->route("customer.index");
    }

    public function destroy(string $id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Khách hàng không tồn tại'], 404);
        }
        if ($user->transactions()->count() > 0) {
            return response()->json(['success' => false, 'message' => 'Không thể xóa khách hàng vì đã có giao dịch'], 422);
        }
        $user->delete();
        return response()->json(['success' => true, 'message' => 'Xóa khách hàng thành công']);
    }
    
    public function changeStatus($id)
    {
        $user = User::find($id);
        if($user){
            $user->status = !$user->status;
            $user->save();
            $message = $user->status ? 'Tài khoản đã được kích hoạt' : 'Tài khoản đã bị khóa';
            return response()->json(['success' => true, 'message'=> $message, 'status' => $user->status]);
        }
        else{
            return response()->json(['success'=> false, 'message'=> 'Không tìm thấy người dùng']);
        }
    }
}
