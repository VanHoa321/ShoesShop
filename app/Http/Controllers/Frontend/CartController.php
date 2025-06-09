<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ProductSize;
use App\Models\Voucher;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function getCartItemCount()
    {
        $cart = session('cart', []);
        return count($cart);
    }

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

        return view('frontend.cart.index', compact('cartItems'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_size_id' => 'required|exists:product_sizes,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $productSizeId = $request->product_size_id;
        $quantity = $request->quantity;
        $price = $request->price;

        $productSize = ProductSize::with([
            'product.color' => function ($query) {
                $query->select('id', 'name');
            },
            'size' => function ($query) {
                $query->select('id', 'name');
            }
        ])
        ->select('id', 'product_id', 'size_id')
        ->find($productSizeId);

        if (!$productSize || !$productSize->product || !$productSize->size) {
            return response()->json(['status' => 'error', 'message' => 'Sản phẩm hoặc kích thước không hợp lệ.']);
        }

        $cart = session('cart', []);
        $cartKey = $productSizeId;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
            $cart[$cartKey]['total'] = $cart[$cartKey]['quantity'] * $cart[$cartKey]['price'];
        } 
        else {
            $cart[$cartKey] = [
                'product_id' => $productSize->product_id,
                'size_id' => $productSize->size_id,
                'name' => $productSize->product->name,
                'thumbnail' => $productSize->product->thumbnail,
                'size' => $productSize->size->name,
                'color' => $productSize->product->color->name,
                'quantity' => $quantity,
                'price' => $price,
                'total' => $quantity * $price,
            ];
        }

        session(['cart' => $cart]);
        $totalItemsInCart = $this->getCartItemCount();
        return response()->json(['status' => 'success', 'cart' => $cart, 'count' => $totalItemsInCart]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'cart_key' => 'required|exists:product_sizes,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartKey = $request->cart_key;
        $quantity = $request->quantity;

        $cart = session('cart', []);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] = $quantity;
            $cart[$cartKey]['total'] = $quantity * $cart[$cartKey]['price'];
        }

        session(['cart' => $cart]);
        $totalItemsInCart = $this->getCartItemCount();
        return response()->json(['status' => 'updated', 'cart' => $cart, 'count' => $totalItemsInCart]);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'cart_key' => 'required|exists:product_sizes,id',
        ]);

        $cartKey = $request->cart_key;
        $cart = session('cart', []);

        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
        }

        session(['cart' => $cart]);
        $totalItemsInCart = $this->getCartItemCount();
        return response()->json(['status' => 'removed', 'cart' => $cart, 'count' => $totalItemsInCart]);
    }

    public function applyVoucher(Request $request)
    {
        if (session('voucher')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Chỉ được áp dụng một mã voucher'
            ]);
        }

        $voucherCode = $request->input('voucher_code');
        $cart = session('cart', []);

        $voucher = Voucher::where('code', $voucherCode)
            ->where('status', 1)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('quantity', '>', 0)
            ->first();

        if (!$voucher) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mã voucher không hợp lệ hoặc đã hết hạn'
            ]);
        }

        $cartTotal = array_sum(array_column($cart, 'total'));

        if ($cartTotal < $voucher->min_order_amount) {
            return response()->json([
                'status' => 'error',
                'message' => 'Đơn hàng chưa đạt giá trị tối thiểu ' . number_format($voucher->min_order_amount, 0, ',', '.') . ' đ'
            ]);
        }

        $discount = 0;
        if ($voucher->type == 1) {
            $discount = $voucher->discount_value;
        } elseif ($voucher->type == 2) {
            $discount = ($voucher->discount_value / 100) * $cartTotal;
        }

        $voucher->decrement('quantity');

        session(['voucher' => [
            'code' => $voucher->code,
            'discount' => $discount,
            'type' => $voucher->type
        ]]);

        return response()->json([
            'status' => 'success',
            'message' => 'Áp dụng voucher thành công',
            'discount' => $discount,
            'new_total' => $cartTotal - $discount
        ]);
    }

    public function removeVoucher(Request $request)
    {
        $voucherCode = session('voucher.code');

        if ($voucherCode) {
            $voucher = Voucher::where('code', $voucherCode)->first();
            if ($voucher) {
                $voucher->increment('quantity');
            }

            session()->forget('voucher');

            $cart = session('cart', []);
            $cartTotal = array_sum(array_column($cart, 'total'));

            return response()->json([
                'status' => 'success',
                'message' => 'Xóa voucher thành công',
                'discount' => 0,
                'new_total' => $cartTotal
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Không có voucher để xóa'
        ]);
    }

    public function clear()
    {
        session()->forget('cart');
        return response()->json(['status' => 'cleared']);
    }
}