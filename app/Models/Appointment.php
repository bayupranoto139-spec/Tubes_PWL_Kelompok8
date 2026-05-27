<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_enrollment_id', 'doctor_id', 'schedule_id',
        'scheduled_at', 'status', 'complaint',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'status'       => 'string',
    ];

    
    public function patientEnrollment()
    {
        return $this->belongsTo(PatientEnrollment::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }

    public function bill()
    {
        return $this->hasOne(Bill::class);
    }

    public function queue()
    {
        return $this->hasOne(Queue::class);
    }

    
    public function scopeToday($query)
    {
        return $query->whereDate('scheduled_at', today());
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    
    public function isWalkIn(): bool
    {
        return is_null($this->schedule_id);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
}
