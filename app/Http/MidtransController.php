<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        Log::info('Midtrans Callback:', $request->all());

        $serverKey = env('MIDTRANS_SERVER_KEY');

        $hashed = hash('sha512',
            $request->order_id .
            $request->status_code .
            $request->gross_amount .
            $serverKey
        );

        if ($hashed === $request->signature_key) {
            $transactionStatus = $request->transaction_status;

            if (in_array($transactionStatus, ['capture', 'settlement'])) {
                // ✅ Pembayaran berhasil
                Log::info("✅ Payment success for Order ID: {$request->order_id}");
            } elseif ($transactionStatus === 'pending') {
                // ⏳ Masih menunggu
                Log::info("⏳ Payment pending for Order ID: {$request->order_id}");
            } else {
                // ❌ Gagal
                Log::warning("❌ Payment failed for Order ID: {$request->order_id}");
            }

            return response()->json(['success' => true]);
        }

        Log::error('❌ Invalid signature for order: ' . $request->order_id);
        return response()->json(['success' => false], 403);
    }
}
