<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function index()
    {
        $items = Contact::with('user')->orderBy('id', 'desc')->paginate(10);
        return view('admin.contact.index', compact('items'));
    }

    public function show($id)
    {
        $contact = Contact::with('user')->findOrFail($id);
        if (!$contact->is_read) {
            $contact->is_read = 1;
            $contact->save();
        }
        return view('admin.contact.show', compact('contact'));
    }

    public function destroy($id)
    {
        $contact = Contact::find($id);
        if ($contact) {
            $contact->delete();
            return response()->json(['success' => true, 'message' => 'Xóa liên hệ thành công']);
        } else {
            return response()->json(['success' => false, 'message' => 'Liên hệ không tồn tại'], 404);
        }
    }

    public function markAsRead($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->is_read = 1;
        $contact->save();
        return response()->json(['success' => true, 'message' => 'Đánh dấu đã đọc thành công']);
    }

    public function markAsUnread($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->is_read = 0;
        $contact->save();
        return response()->json(['success' => true, 'message' => 'Đánh dấu chưa đọc thành công']);
    }

    public function reply(Request $request, $id)
    {
        $contact = Contact::with('user')->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'reply_message' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Vui lòng nhập nội dung phản hồi'], 422);
        }

        if (!$contact->user || !$contact->user->email) {
            return response()->json(['success' => false, 'message' => 'Không thể gửi phản hồi vì không có email'], 400);
        }

        try {
            Mail::html($request->reply_message, function ($message) use ($contact) {
                $message->to($contact->user->email)
                ->subject('Phản hồi từ hệ thống liên hệ');
            });

            $contact->reply_message = $request->reply_message;
            $contact->replied_at = now();
            $contact->save();

            return response()->json(['success' => true, 'message' => 'Phản hồi đã được gửi thành công']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi khi gửi email: ' . $e->getMessage()], 500);
        }
    }
}