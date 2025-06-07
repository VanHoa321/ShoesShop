<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function profile()
    {
        return view('frontend.account.profile');
    }

    public function editProfile()
    {
        return view('frontend.account.profile');
    }

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|min:3|max:50',
            'email' => 'required|email|max:100|unique:users,email,' . $id . ',id',
            'phone' => 'nullable|string|regex:/^0[0-9]{9}$/|unique:users,phone,' . $id . ',id',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'avatar' => 'nullable|string',
        ];

        $messages = [
            'name.required' => 'Họ tên không được để trống!',
            'name.min' => 'Họ tên phải có ít nhất :min ký tự!',
            'name.max' => 'Họ tên tối đa :max ký tự!',

            'email.required' => 'Email không được để trống!',
            'email.email' => 'Email không hợp lệ!',
            'email.max' => 'Email tối đa :max ký tự!',
            'email.unique' => 'Email đã tồn tại!',

            'phone.regex' => 'Số điện thoại phải có 10 chữ số và bắt đầu bằng số 0!',
            'phone.unique' => 'Số điện thoại đã tồn tại!',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    public function updateProfile(Request $request)
    {
        $userId = Auth::id();

        $validator = $this->validates($request, $userId);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::findOrFail($userId);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'description' => $request->description,
            'avatar' => $request->avatar,
        ]);

        return redirect()->route('frontend.profile')->with('messenger', [
            'style' => 'success',
            'msg' => 'Cập nhật hồ sơ thành công!',
        ]);
    }

    public function editPassword()
    {
        return view('frontend.account.edit-password');
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::findOrFail(Auth::id());

        if (!Hash::check($request->old_password, $user->password)) {
            return redirect()->back()
                ->withErrors(['old_password' => 'Mật khẩu cũ không đúng!'])
                ->withInput();
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('frontend.profile')
            ->with('messenge', [
                'style' => 'success',
                'msg' => 'Đổi mật khẩu thành công!'
            ]);
    }
}
