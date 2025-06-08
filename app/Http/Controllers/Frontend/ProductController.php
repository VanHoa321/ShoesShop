<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        
    }

    public function details($id)
    {
        $product = Product::with(['sizes', 'color', 'images'])->findOrFail($id);
        $relatedProducts = Product::where('group_code', $product->group_code)
            ->where('id', '!=', $id)
            ->with(['color'])
            ->get();
        $sizes = ProductSize::where('product_id', $id)->with('size')->get();

        return view('frontend.product.details', compact('product', 'relatedProducts', 'sizes'));
    }
}
