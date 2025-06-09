<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {

        $cart = session('cart', []);

        $productSizeIds = array_keys($cart);

        $productSizes = ProductSize::with([
            'product.color' => function ($query) {
                $query->select('id', 'name');
            },
            'size' => function ($query) {
                $query->select('id', 'name');
            }
        ])
        ->whereIn('id', $productSizeIds)
        ->select('id', 'product_id', 'size_id')
        ->get()
        ->keyBy('id');

        $cartItems = [];

        foreach ($cart as $productSizeId => $item) {
            $productSize = $productSizes->get($productSizeId);

            if ($productSize && $productSize->product && $productSize->size) {
                $cartItems[] = [
                    'cart_key' => $productSizeId,
                    'product_id' => $productSize->product_id,
                    'size_id' => $productSize->size_id,
                    'name' => $productSize->product->name,
                    'thumbnail' => $productSize->product->thumbnail,
                    'size' => $productSize->size->name,
                    'color' => $productSize->product->color->name,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total' => $item['total'],
                ];
            } 
            else {
                unset($cart[$productSizeId]);
                session(['cart' => $cart]);
            }
        }

        return view("frontend.checkout.index", compact("cartItems"));
    }

    public function store(Request $request)
    {

        $cart = session('cart', []);
        if (empty($cart)) {
            return response()->json(['status' => 'error', 'message' => 'Giỏ hàng rỗng.']);
        }

        $subtotal = array_sum(array_column($cart, 'total'));
        $discount = session('voucher') ? session('voucher.discount') : 0;
        $total = $subtotal - $discount;

        $datePrefix = now()->format('Ymd');
        $randomSuffix = Str::random(6);
        $orderCode = $datePrefix . $randomSuffix;

        $userId = Auth::check() ? Auth::id() : null;

        $voucherCode = session('voucher.code') ?? null;

        if ($request->payment_method === 'momo') {
            return response()->json([
                'status' => 'pending',
                'message' => 'Thanh toán qua Momo chưa được tích hợp. Vui lòng xử lý tích hợp.',
            ]);
        }

        // Lưu đơn hàng cho COD
        $order = Order::create([
            'code' => $orderCode,
            'user_id' => $userId,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_email' => $request->customer_email,
            'customer_address' => $request->customer_address,
            'notes' => $request->notes,
            'voucher_code' => $voucherCode,
            'discount_amount' => $discount,
            'subtotal' => $subtotal,
            'total' => $total,
            'payment_method' => $request->payment_method,
            'status' => 1,
        ]);

        foreach ($cart as $productSizeId => $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'size' => $item['size'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'total' => $item['total'],
            ]);
        }

        // Xóa giỏ hàng và voucher
        session()->forget(['cart', 'voucher']);

        return redirect()->route("frontend.checkout.success");
    }

    public function success()
    {
        return view("frontend.checkout.success");
    }
}
