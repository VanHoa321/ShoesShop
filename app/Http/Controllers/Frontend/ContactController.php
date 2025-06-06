<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function index()
    {
        return view('frontend.contact.index');
    }
    
    public function sendContact(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        Contact::create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_read' => 0,
        ]);

        return redirect()->back()->with('success', 'Liên hệ của bạn đã được gửi thành công!');
    }
}
