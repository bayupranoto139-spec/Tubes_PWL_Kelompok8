<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // <--- PASTIKAN ADA KATA 'Eloquent' DI SINI

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'hospital_id', 'medical_record_number', 'blood_type',
        'allergies', 'emergency_contact_name', 'emergency_contact_phone',
        'insurance_provider', 'insurance_policy_number',
    ];


    protected $casts = [
        'blood_type' => 'string',
    ];


    protected static function booted()
{
    static::addGlobalScope('hospital', function ($query) {
        if (Auth::check() && Auth::user()->hospital_id) {
            $query->where('hospital_id', Auth::user()->hospital_id);
        }
    });
}

    // Relasi ke user (akun login pasien)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke rumah sakit
    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    // Relasi ke appointments
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    // Relasi ke medical records
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    // Relasi ke bills
    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    // Relasi ke queues
    public function queues()
    {
        return $this->hasMany(Queue::class);
    }
}
