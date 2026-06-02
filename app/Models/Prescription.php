<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_record_id', 'medication_id',
        'dosage', 'duration', 'quantity', 'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];


    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }

    public function getTotalPriceAttribute(): float
    {
        return $this->quantity * (float) $this->medication->price;
    }
}
