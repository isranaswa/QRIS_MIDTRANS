<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function index()
    {
        return view('payment'); // View utama di resources/views/payment.blade.php
    }

    public function process(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'amount' => 'required|integer|min:1000',
            'payment_type' => 'required|string|in:gopay,qris',
        ]);

        $serverKey = config('services.midtrans.server_key'); // Pastikan sudah diatur di .env
        $orderId = 'ORDER-' . Str::uuid();

        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $request->amount,
            ],
            'customer_details' => [
                'first_name' => $request->name,
                'email' => $request->email,
            ],
            'payment_type' => $request->payment_type,
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($serverKey . ':'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post('https://api.sandbox.midtrans.com/v2/charge', $payload);

        if ($response->failed()) {
            Log::error('Midtrans charge failed', ['response' => $response->json()]);
            return response()->json([
                'status' => 'error',
                'status_message' => $response->json()['status_message'] ?? 'Payment failed',
                'data' => $response->json(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'data' => $response->json(),
        ]);
    }
}
