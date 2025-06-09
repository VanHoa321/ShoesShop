<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderCancel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function pendingOrders()
    {
        $items = Order::with("user", "orderDetails")->where("status", 1)->orderBy("created_at", "desc")->get();
        return view("admin.order.pending", compact("items"));
    }

    public function details($id)
    {
        $item = Order::with("user", "orderDetails")->where("id", $id)->first();
        return view("admin.order.details", compact("item"));
    }

    public function updateStatus(Request $request, $id)
    {
        
        $order = Order::findOrFail($id);
        $status = $request->status;
        $order->update(['status' => $status]);

        if ($status == 2) {
            $message = 'Xác nhận đơn hàng thành công!';
        } 
        elseif ($status == 3) {
            $message = 'Đã chuyển sang trạng thái giao hàng!';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    public function cancelOrder(Request $request, $id)
    {

        $order = Order::findOrFail($id);

        $order->update(['status' => 0]);
        $order->save();

        $user = Auth::user();
        $cancelBy = "Admin: " . ($user->name) . " (" . ($user->phone) . ")";

        OrderCancel::create([
            'order_id' => $id,
            'cancel_reason' => $request->reason,
            'cancel_date' => now(),
            'cancel_by' => $cancelBy
        ]);

        return response()->json([
            'success' => true,
            'message' => "Đã hủy đơn hàng này!"
        ]);
    }
}
