<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function getCartItemCount()
    {
        $cart = session('cart', []);
        $totalItems = count($cart);
        return $totalItems;
    }

    public function index(Request $request)
    {
        $cart = session('cart', []);
        dd($cart);
        return view('frontend.cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $productSizeId = $request->product_size_id;
        $quantity = $request->quantity;
        $price = $request->price;

        $cart = session('cart', []);

        if (isset($cart[$productSizeId])) {
            $cart[$productSizeId]['quantity'] += $quantity;
        } 
        else {
            $cart[$productSizeId] = [
                'quantity' => $quantity,
                'price' => $price,
            ];
        }

        $cart[$productSizeId]['total'] = $cart[$productSizeId]['quantity'] * $cart[$productSizeId]['price'];

        session(['cart' => $cart]);

        return response()->json(['status' => 'success', 'cart' => $cart, 'count' => count($cart)]);
    }

    public function update(Request $request)
    {
        $productSizeId = $request->product_size_id;
        $quantity = $request->quantity;

        $cart = session('cart', []);

        if (isset($cart[$productSizeId])) {
            $cart[$productSizeId]['quantity'] = $quantity;
            $cart[$productSizeId]['total'] = $quantity * $cart[$productSizeId]['price'];
        }

        session(['cart' => $cart]);

        return response()->json(['status' => 'updated', 'cart' => $cart, 'count' => count($cart)]);
    }

    public function remove(Request $request)
    {
        $productSizeId = $request->product_size_id;
        $cart = session('cart', []);

        if (isset($cart[$productSizeId])) {
            unset($cart[$productSizeId]);
        }

        session(['cart' => $cart]);

        return response()->json(['status' => 'removed', 'cart' => $cart]);
    }

    public function clear()
    {
        session()->forget('cart');
        return response()->json(['status' => 'cleared']);
    }
}
