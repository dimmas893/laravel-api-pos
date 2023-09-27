<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function getData()
    {
        $cart = Cart::with('product')->get();
        $total = Cart::sum('price');
        return response()->json([
            'cart' => $cart,
            'total' => (int)$total
        ]);
    }
    public function post(Request $request)
    {
        $cek = Cart::where('product_id', $request->product_id)->first();

        $product = Product::where('id', $request->product_id)->first();
        if ($cek) {
            // dd($product);
            if ($product->stock > 0) {
                $product->update([
                    'stock' => $product->stock - 1
                ]);
                $cek->update([
                    'qty' => $cek->qty + 1,
                    'price' =>  $cek->price + $product->price
                ]);
            }
        } else {
            $product->update([
                'stock' => $product->stock - 1
            ]);
            $data = [
                'product_id' => $product->id,
                'qty' => 1,
                'price' =>  $product->price
            ];
            Cart::create($data);
        }
        return response()->json([
            'message' => "Data berhasil di tambah"
        ], 200);
    }
    public function delete($id)
    {
        $cart = Cart::where('id', $id)->first();

        $product = Product::where('id', $cart->product_id)->first();
        $product->update([
            'stock' => $product->stock + $cart->qty
        ]);
        $cart->delete();
        return response()->json([
            'message' => "Data berhasil di hapus"
        ], 200);
    }
}
