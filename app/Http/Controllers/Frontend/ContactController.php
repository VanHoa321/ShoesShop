<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class ContactController extends Controller
{
    public function index()
    {
        return view('frontend.contact.index');
    }

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'message' => 'required|string|max:1000',
        ];

        $messages = [
            'message.required' => 'Tin nhắn không được để trống!',
            'message.string' => 'Tin nhắn phải là một chuỗi ký tự!',
            'message.max' => 'Tin nhắn tối đa :max ký tự!',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }
    
    public function sendContact(Request $request)
    {
        $validator = $this->validates($request);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Contact::create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_read' => 0,
        ]);

        return redirect()->back()->with('success', 'Liên hệ của bạn đã được gửi thành công!');
    }
}
