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

        // Pastikan total_amount sinkron dengan bill items sebelum dikirim ke Midtrans
        $bill->recalculateTotal();
        $bill->refresh();

        $grossAmount = (int) round((float) $bill->total_amount);

        // Guard: total harus > 0
        if ($grossAmount <= 0) {
            Log::error("Midtrans createPayment: Bill ID={$bill->id} has total_amount={$bill->total_amount}. Aborting.");
            return redirect()->route('patient.bills')
                ->with('error', 'Tagihan tidak valid: total biaya adalah Rp 0. Hubungi admin.');
        }

        $orderId = 'BILL-' . $bill->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $bill->patientEnrollment->user->name ?? 'Pasien',
                'email'      => $bill->patientEnrollment->user->email ?? 'pasien@example.com',
                'phone'      => $bill->patientEnrollment->user->phone ?? '08000000000',
            ],
            'item_details' => $this->getItemDetails($bill, $grossAmount),
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            $bill->update([
                'snap_token'       => $snapToken,
                'reference_number' => $orderId,
            ]);

            Log::info("Midtrans: Snap token created for bill {$bill->id}, order_id={$orderId}, gross_amount={$grossAmount}");

            return view('payment.pay', compact('snapToken', 'bill'));

        } catch (\Exception $e) {
            Log::error('Midtrans createPayment error: ' . $e->getMessage());
            return redirect()->route('patient.bills')
                ->with('error', 'Gagal membuat sesi pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Build item_details untuk Midtrans.
     */
    private function getItemDetails(Bill $bill, int $grossAmount): array
    {
        $items      = [];
        $itemsTotal = 0;

        foreach ($bill->billItems as $item) {
            $price    = (int) round((float) $item->unit_price);
            $quantity = (int) $item->quantity;

            $items[] = [
                'id'       => (string) $item->id,
                'price'    => $price,
                'quantity' => $quantity,
                'name'     => mb_substr($item->description, 0, 50),
            ];

            $itemsTotal += $price * $quantity;
        }

        $diff = $grossAmount - $itemsTotal;
        if ($diff !== 0) {
            $items[] = [
                'id'       => 'ROUNDING',
                'price'    => $diff,
                'quantity' => 1,
                'name'     => 'Penyesuaian pembulatan',
            ];
        }

        return $items;
    }

    public function handleNotification(Request $request)
    {
        $this->bootMidtrans();

        try {
            $notification = new Notification();

            $orderId           = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus       = $notification->fraud_status ?? 'accept';
            $paymentType       = $notification->payment_type;
            $transactionId     = $notification->transaction_id;

            Log::info("Midtrans Notification Received", [
                'order_id' => $orderId,
                'status'   => $transactionStatus,
                'fraud'    => $fraudStatus,
                'payment'  => $paymentType,
            ]);

            $bill = null;
            if (preg_match('/^BILL-(\d+)-\d+$/', $orderId, $matches)) {
                $bill = Bill::find($matches[1]);
            }
            if (! $bill) {
                $bill = Bill::where('reference_number', $orderId)->first();
            }
            if (! $bill) {
                Log::error("Midtrans: Bill tidak ditemukan untuk order_id={$orderId}");
                return response()->json(['message' => 'Bill not found'], 404);
            }

            if ($transactionStatus === 'capture') {
                if ($fraudStatus === 'accept') {
                    $this->markBillPaid($bill, $paymentType, $transactionId);
                }
            } elseif ($transactionStatus === 'settlement') {
                $this->markBillPaid($bill, $paymentType, $transactionId);
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $bill->update([
                    'status'                  => 'unpaid',
                    'midtrans_transaction_id' => $transactionId,
                ]);
            }

            return response()->json(['message' => 'OK']);

        } catch (\Throwable $e) {
            Log::error('Midtrans handleNotification exception: ' . $e->getMessage());
            return response()->json(['message' => 'Server error'], 500);
        }
    }

    private function markBillPaid(Bill $bill, ?string $paymentType, ?string $transactionId): void
    {
        $bill->update([
            'status'                  => 'paid',
            'payment_date'            => now(),
            'payment_method'          => $paymentType ?? 'bank_transfer',
            'midtrans_transaction_id' => $transactionId,
        ]);

        Log::info("Midtrans: Bill {$bill->id} marked PAID", [
            'payment_type'   => $paymentType,
            'transaction_id' => $transactionId,
        ]);
    }

    public function success()  { return view('payment.success'); }
    public function unfinish() { return view('payment.unfinish'); }
    public function error()    { return view('payment.error'); }
}