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
    private function bootMidtrans(): void
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.is_3ds');
    }

    public function createPayment(Bill $bill)
    {
        $this->bootMidtrans();

        if ($bill->status === 'paid') {
            return redirect()->route('patient.bills')->with('info', 'Tagihan ini sudah dibayar.');
        }

        $orderId = 'BILL-' . $bill->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $bill->total_amount,
            ],
            'customer_details' => [
                'first_name' => $bill->patientEnrollment->user->name ?? 'Pasien',
                'email'      => $bill->patientEnrollment->user->email ?? 'pasien@example.com',
                'phone'      => $bill->patientEnrollment->user->phone ?? '08000000000',
            ],
            'item_details' => $this->getItemDetails($bill),
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            $bill->update([
                'snap_token'       => $snapToken,
                'reference_number' => $orderId,
            ]);

            Log::info("Midtrans: Snap token created for bill {$bill->id}, order_id={$orderId}");

            return view('payment.pay', compact('snapToken', 'bill'));

        } catch (\Exception $e) {
            Log::error('Midtrans createPayment error: ' . $e->getMessage());
            return redirect()->route('patient.bills')
                ->with('error', 'Gagal membuat sesi pembayaran: ' . $e->getMessage());
        }
    }

    private function getItemDetails(Bill $bill): array
    {
        $items = [];
        foreach ($bill->billItems as $item) {
            $items[] = [
                'id'       => (string) $item->id,
                'price'    => (int) $item->unit_price,
                'quantity' => (int) $item->quantity,
                'name'     => substr($item->description, 0, 50),
            ];
        }
        return $items;
    }

    public function handleNotification(Request $request)
    {
        $this->bootMidtrans();

        try {
            // Midtrans Notification object otomatis validasi signature key
            $notification = new Notification();

            $orderId           = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus       = $notification->fraud_status ?? 'accept';
            $paymentType       = $notification->payment_type;
            $transactionId     = $notification->transaction_id;

            Log::info("Midtrans Notification Received", [
                'order_id'   => $orderId,
                'status'     => $transactionStatus,
                'fraud'      => $fraudStatus,
                'payment'    => $paymentType,
            ]);

            // Cari bill: format order_id adalah "BILL-{id}-{timestamp}"
            $bill = null;

            // Cara 1: parse bill ID dari order_id
            if (preg_match('/^BILL-(\d+)-\d+$/', $orderId, $matches)) {
                $bill = Bill::find($matches[1]);
            }

            // Cara 2: fallback cari by reference_number
            if (! $bill) {
                $bill = Bill::where('reference_number', $orderId)->first();
            }

            if (! $bill) {
                Log::error("Midtrans: Bill tidak ditemukan untuk order_id={$orderId}");
                return response()->json(['message' => 'Bill not found'], 404);
            }

            Log::info("Midtrans: Found bill ID={$bill->id}, current status={$bill->status}");

            // Update status berdasarkan transaction_status dari Midtrans
            if ($transactionStatus === 'capture') {
                if ($fraudStatus === 'accept') {
                    $this->markBillPaid($bill, $paymentType, $transactionId);
                } else {
                    // fraudStatus === 'challenge' → tunggu review manual
                    Log::warning("Midtrans: Bill {$bill->id} flagged as fraud challenge");
                }

            } elseif ($transactionStatus === 'settlement') {
                // settlement = final confirmed (bank transfer, etc.)
                $this->markBillPaid($bill, $paymentType, $transactionId);

            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $bill->update([
                    'status'                  => 'unpaid',
                    'midtrans_transaction_id' => $transactionId,
                ]);
                Log::info("Midtrans: Bill {$bill->id} payment {$transactionStatus}");

            } elseif ($transactionStatus === 'pending') {
                // Masih menunggu bayar (misal: VA belum ditransfer)
                Log::info("Midtrans: Bill {$bill->id} payment pending");
            }

            return response()->json(['message' => 'OK']);

        } catch (\Throwable $e) {
            Log::error('Midtrans handleNotification exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['message' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    private function markBillPaid(Bill $bill, ?string $paymentType, ?string $transactionId): void
    {
        $updated = $bill->update([
            'status'                  => 'paid',
            'payment_date'            => now(),
            'payment_method'          => $paymentType ?? 'bank_transfer',
            'midtrans_transaction_id' => $transactionId,
        ]);

        Log::info("Midtrans: Bill {$bill->id} marked PAID", [
            'payment_type'   => $paymentType,
            'transaction_id' => $transactionId,
            'db_updated'     => $updated,
        ]);
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
