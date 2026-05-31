<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;

class MidtransController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function createPayment(Bill $bill)
    {
        // Prevent duplicate payments
        if ($bill->status === 'paid') {
            return redirect()->back()->with('error', 'Bill sudah dibayar');
        }

        $orderId = 'BILL-'.$bill->id.'-'.time();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $bill->total_amount,
            ],
            'customer_details' => [
                'first_name' => $bill->patientEnrollment->user->name,
                'email' => $bill->patientEnrollment->user->email,
                'phone' => $bill->patientEnrollment->user->phone,
            ],
            'item_details' => $this->getItemDetails($bill),
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $bill->update([
                'snap_token' => $snapToken,
                'reference_number' => $orderId,
            ]);

            return view('payment.pay', compact('snapToken', 'bill'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat pembayaran: '.$e->getMessage());
        }
    }

    private function getItemDetails(Bill $bill)
    {
        $items = [];
        foreach ($bill->billItems as $item) {
            $items[] = [
                'id' => $item->id,
                'price' => (int) $item->unit_price,
                'quantity' => $item->quantity,
                'name' => substr($item->description, 0, 50),
            ];
        }

        return $items;
    }

    public function handleNotification(Request $request)
    {
        $notification = new Notification;

        $orderId = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status;

        // Find bill by reference_number (order_id)
        $bill = Bill::where('reference_number', $orderId)->first();

        if (! $bill) {
            return response()->json(['message' => 'Bill not found'], 404);
        }

        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $bill->update([
                'status' => 'paid',
                'payment_date' => now(),
                'payment_method' => $this->mapPaymentMethod($notification->payment_type),
                'midtrans_transaction_id' => $notification->transaction_id,
            ]);
        } elseif ($transactionStatus == 'pending') {
            $bill->status = 'unpaid';
            $bill->save();
        } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
            $bill->update([
                'status' => 'unpaid',
                'midtrans_transaction_id' => $notification->transaction_id,
            ]);
        }

        return response()->json(['message' => 'OK']);
    }

    private function mapPaymentMethod($paymentType)
    {
        return match ($paymentType) {
            'credit_card' => 'bank_transfer',
            'bank_transfer' => 'bank_transfer',
            'gopay' => 'qris',
            'qris' => 'qris',
            default => 'bank_transfer'
        };
    }

    public function callback(Request $request)
    {
        try {
            Log::info('Midtrans Callback Received:', $request->all());

            $serverKey = config('midtrans.server_key');
            $hashed = hash('sha512', $request->order_id.$request->status_code.$request->gross_amount.$serverKey);

            if ($hashed !== $request->signature_key) {
                Log::warning('Invalid signature for order_id: '.$request->order_id);

                return response()->json(['message' => 'Invalid signature'], 403);
            }

            // Ambil order_id dari request (contoh: 'BILL-1-1717123456')
            $orderId = $request->order_id;
            $billId = explode('-', $orderId)[1];

            $bill = Bill::find($billId);

            if (! $bill) {
                Log::error('Bill not found for order_id: '.$orderId);

                return response()->json(['message' => 'Bill not found'], 404);
            }

            $transactionStatus = $request->transaction_status;

            // Update status tagihan berdasarkan status transaksi
            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                $bill->update([
                    'status' => 'paid',
                    'payment_date' => now(),
                    'payment_method' => $request->payment_type,
                    'midtrans_transaction_id' => $request->transaction_id,
                ]);
                Log::info('Bill '.$bill->id.' updated to PAID.');
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $bill->update([
                    'status' => 'unpaid',
                    'midtrans_transaction_id' => $request->transaction_id,
                ]);
                Log::info('Bill '.$bill->id.' payment failed.');
            } elseif ($transactionStatus == 'pending') {
                $bill->update(['status' => 'unpaid']); // Atau status 'pending' jika Anda memilikinya
                Log::info('Bill '.$bill->id.' payment is pending.');
            }

            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {
            Log::error('Error processing callback: '.$e->getMessage());

            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function success()
    {
        return view('payment.success');
    }

    public function unfinish()
    {
        return view('payment.unfinish');
    }

    public function error()
    {
        return view('payment.error');
    }
}
