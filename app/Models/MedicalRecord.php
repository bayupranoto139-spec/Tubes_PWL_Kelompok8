<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'appointment_id', 'visit_date',
        'diagnosis', 'treatment_plan', 'notes', 'case_status',
    ];

    protected $casts = [
        'visit_date'  => 'datetime',
        'case_status' => 'string',
    ];

    
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function doctor()
    {
        return $this->hasOneThrough(
            Doctor::class,
            Appointment::class,
            'id',           
            'id',           
            'appointment_id',
            'doctor_id'     
        );
    }

    public function patientEnrollment()
    {
        return $this->hasOneThrough(
            PatientEnrollment::class,
            Appointment::class,
            'id',
            'id',
            'appointment_id',
            'patient_enrollment_id'
        );
    }

    
    public function heal(): void
    {
        $this->update(['case_status' => 'healed']);
    }

    public function isActive(): bool
    {
        return $this->case_status === 'active';
    }

    public function isHealed(): bool
    {
        return $this->case_status === 'healed';
    }
}
