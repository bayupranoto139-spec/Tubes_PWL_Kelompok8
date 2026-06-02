<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id', 'queue_date', 'queue_number', 'type',
        'priority', 'status', 'called_at', 'started_at', 'completed_at',
    ];

    protected $casts = [
        'queue_date'   => 'date',
        'called_at'    => 'datetime',
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
        'priority'     => 'integer',
        'queue_number' => 'integer',
    ];

    
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
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

    
    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('queue_date', today());
    }

    
    public static function getNextQueue(int $doctorId): ?self
    {
        return self::today()
            ->waiting()
            ->whereHas('appointment', fn($q) => $q->where('doctor_id', $doctorId))
            ->orderBy('priority')
            ->orderBy('queue_number')
            ->first();
    }

    public function isWalkIn(): bool
    {
        return $this->type === 'walk_in';
    }

    public function call(): void
    {
        $this->update(['status' => 'called', 'called_at' => now()]);
    }

    public function start(): void
    {
        $this->update(['status' => 'in_progress', 'started_at' => now()]);
    }

    public function complete(): void
    {
        $this->update(['status' => 'completed', 'completed_at' => now()]);
    }

    public function skip(): void
    {
        $this->update(['status' => 'skipped']);
    }
}
