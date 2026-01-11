<?php

namespace App\Http\Controllers;

use App\Services\CheckoutService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function store(Request $request, CheckoutService $chekout) {
        // urus http

    }

    public function create(Request $request)
    {
        $orderId = 'TRX-' . time();
        $total = 10000; // sementara hardcode dulu

        // simpan transaksi (dummy dulu kalau mau)
        // nanti baru pakai DB beneran

        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = false;

        $snapToken = \Midtrans\Snap::getSnapToken([
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $total,
            ],
        ]);

        return response()->json([
            'snap_token' => $snapToken,
            'order_id' => $orderId,
        ]);
    }

}
