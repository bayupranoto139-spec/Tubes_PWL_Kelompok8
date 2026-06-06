<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Notification;

class MidtransNotificationController extends Controller
{
    public function handle(Request $request)
    {
        // Midtrans recommended: use Notification object to validate signature
        $notification = new Notification;

        try {
            $orderId = $notification->order_id; // equals transaction_details.order_id
            $transactionStatus = $notification->transaction_status;

            $bill = Bill::where('reference_number', $orderId)->first();

            if (! $bill) {
                Log::warning('Midtrans notification: bill not found', [
                    'order_id' => $orderId,
                ]);

                return response()->json(['message' => 'Bill not found'], 404);
            }

            if (in_array($transactionStatus, ['capture', 'settlement'], true)) {
                $bill->update([
                    'status' => 'paid',
                    'payment_date' => now(),
                    'payment_method' => $notification->payment_type ?? $bill->payment_method,
                    'midtrans_transaction_id' => $notification->transaction_id,
                ]);
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'], true)) {
                $bill->update([
                    'status' => 'unpaid',
                    'midtrans_transaction_id' => $notification->transaction_id,
                ]);
            } elseif ($transactionStatus === 'pending') {
                $bill->update([
                    'status' => 'unpaid',
                ]);
            }

            return response()->json(['message' => 'OK']);
        } catch (\Throwable $e) {
            Log::error('Midtrans notification handle error: '.$e->getMessage(), [
                'payload' => $request->all(),
            ]);

            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
