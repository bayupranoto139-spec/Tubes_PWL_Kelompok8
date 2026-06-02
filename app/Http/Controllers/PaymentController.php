<?php

namespace App\Http\Controllers;

use App\Models\Bill;

class PaymentController extends Controller
{
    public function index()
    {
        $bills = Bill::with('patient')->latest()->get();

        return view('payments.index', compact('bills'));
    }

    public function pay($id)
    {
        $bill = Bill::find($id);

        if ($bill) {
            $bill->status = 'paid';
            $bill->save();
        }

        return back();
    }
}