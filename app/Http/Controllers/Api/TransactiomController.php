<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class TransactiomController extends Controller
{
    public function transaction()
    {
        $OrderLatest = Transaction::latest('created_at')->first();
        if ($OrderLatest != null) {
            $nopo = substr($OrderLatest, 4, 5);
            $no_po = intval($nopo);
            do {
                $number = 'invoice-' . str_pad(($no_po++ + 1), 5, "0", STR_PAD_LEFT) . '-' . $this->getRomawi(date('n')) . '-' . date('Y');
            } while ($OrderLatest->where('invoice', $number)->exists());
        } else {
            $number = 'invoice-00001' . '-' . $this->getRomawi(date('n')) . '-' . date('Y');
        }

        $cart = Cart::get();
        $total = [];
        foreach ($cart as $data) {
            array_push($total, $data->price);
            TransactionDetail::create([
                'invoice' => $number,
                'product_id' => $data->product_id,
                'qty' => $data->qty,
                'price' => $data->price,
            ]);
            $data->delete();
        }
        $order =  Transaction::create([
            'invoice' => $number,
            'total' => array_sum($total),
        ]);

        return response()->json([
            'message' => "Pesanan berhasil dibayar"
        ], 200);
    }
    public function riwayatTransaksi(Request $request)
    {
        $productData = Transaction::query();
        $productData->orderBy('created_at', 'DESC');
        $products = $productData->paginate($request['perPage']);
        return response()->json($products);
    }
    public function riwayatTransaksiDetail($id)
    {
        $order = Transaction::where('id', $id)->first();
        $orderDetail = TransactionDetail::with('product')->where('invoice', $order->invoice)->get();
        return response()->json([
            'order' => $order,
            'orderDetail' => $orderDetail,
        ]);
    }

    function getRomawi($bln)
    {
        switch ($bln) {
            case 1:
                return "I";
                break;
            case 2:
                return "II";
                break;
            case 3:
                return "III";
                break;
            case 4:
                return "IV";
                break;
            case 5:
                return "V";
                break;
            case 6:
                return "VI";
                break;
            case 7:
                return "VII";
                break;
            case 8:
                return "VIII";
                break;
            case 9:
                return "IX";
                break;
            case 10:
                return "X";
                break;
            case 11:
                return "XI";
                break;
            case 12:
                return "XII";
                break;
        }
    }
}
