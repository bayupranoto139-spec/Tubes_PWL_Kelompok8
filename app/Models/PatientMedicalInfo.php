<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientMedicalInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'blood_type',
        'allergies',
        'emergency_contact_name',
        'emergency_contact_phone',
        'insurance_provider',
        'insurance_policy_number',
    ];

    protected $casts = [
        'blood_type' => 'string',
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
    public function hasInsurance(): bool
    {
        return !is_null($this->insurance_provider);
    }

    public function hasAllergies(): bool
    {
        return !is_null($this->allergies);
    }
}
