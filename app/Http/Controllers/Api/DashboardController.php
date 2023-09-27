<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function data()
    {
        $product = Product::count();
        $category = Category::count();
        $productReady = Product::where('stock', '>=', 1)->count();
        $transaction = Transaction::sum('total');
        return response()->json([
            'product' => $product,
            'category' => $category,
            'productReady' => $productReady,
            'transaction' => $transaction,
        ]);
    }
}
