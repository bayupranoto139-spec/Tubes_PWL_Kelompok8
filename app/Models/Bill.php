<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_enrollment_id',
        'appointment_id',
        'total_amount',
        'status',
        'payment_due_date',
        'payment_method',
        'payment_date',
        'reference_number',
        'snap_token',
        'midtrans_order_id',
        'midtrans_transaction_id',
    ];

    protected $casts = [
        'total_amount'     => 'decimal:2',
        'status'           => 'string',
        'payment_due_date' => 'date',
        'payment_date'     => 'datetime',
    ];

    public function patientEnrollment()
    {
        return $this->belongsTo(PatientEnrollment::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function billItems()
    {
        return $this->hasMany(BillItem::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isOverdue(): bool
    {
        return !$this->isPaid() && $this->payment_due_date->isPast();
    }

    public function recalculateTotal(): void
    {
        $this->update([
            'total_amount' => $this->billItems()->sum('subtotal'),
        ]);
    }

    public function markAsPaid(string $method, string $reference = null): void
    {
        $this->update([
            'status'           => 'paid',
            'payment_method'   => $method,
            'payment_date'     => now(),
            'reference_number' => $reference,
        ]);
    }
}
