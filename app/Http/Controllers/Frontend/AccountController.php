<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Favourite;
use App\Models\Product;
use App\Models\ProductRating;
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

    private function validatePassword(Request $request)
    {
        $rules = [
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:6|max:50|confirmed',
        ];

        $messages = [
            'old_password.required' => 'Mật khẩu cũ không được để trống!',
            'new_password.required' => 'Mật khẩu mới không được để trống!',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất :min ký tự!',
            'new_password.max' => 'Mật khẩu mới tối đa :max ký tự!',
            'new_password.confirmed' => 'Xác nhận mật khẩu mới không khớp!',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    public function updatePassword(Request $request)
    {
        $validator = $this->validatePassword($request);

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

        return redirect()->route('frontend.edit-password')
            ->with('messenger', [
                'style' => 'success',
                'msg' => 'Đổi mật khẩu thành công!'
            ]);
    }

    public function myFavourite()
    {
        $favourites = Favourite::where('user_id', Auth::id())
            ->with(['product' => function ($query) {
                $query->withCount('ratings as rating_count')
                    ->withAvg('ratings as average_rating', 'rating')
                    ->addSelect(['id', 'name', 'price', 'thumbnail', 'discount', 'is_sale']); // Thêm các cột cần thiết
            }])
            ->paginate(1); // Áp dụng phân trang

        $favourites->getCollection()->transform(function ($favourite) {
            $favourite->product->is_favourite = Auth::check() && Favourite::where('user_id', Auth::id())
                ->where('product_id', $favourite->product_id)
                ->exists();
            $favourite->product->rating_count = $favourite->product->rating_count ?? 0;
            $favourite->product->average_rating = $favourite->product->average_rating ?? 0;
            return $favourite;
        });
        return view('frontend.account.my-favourite', compact('favourites'));
    }

    public function addFavourite(Request $request, $id)
    {
        $user = User::findOrFail(Auth::id());
        $product = Product::findOrFail($id);

        $existingFavourite = Favourite::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existingFavourite) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm này đã tồn tại trong danh sách yêu thích của bạn!'
            ], 400);
        }

        Favourite::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm đã được thêm vào danh sách yêu thích!'
        ]);
    }

    public function removeFavourite(Request $request, $id)
    {
        $user = User::findOrFail(Auth::id());
        $product = Product::findOrFail($id);

        $favourite = Favourite::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if (!$favourite) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm này không tồn tại trong danh sách yêu thích của bạn!'
            ], 400);
        }

        $favourite->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm đã được xóa khỏi danh sách yêu thích!'
        ]);
    }
}
