<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id', 'day_of_week', 'start_time', 'end_time',
        'max_patients', 'is_active',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'day_of_week'  => 'integer',
        'max_patients' => 'integer',
    ];

   
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

   
    public function remainingQuota(string $date): int
    {
        $booked = $this->appointments()
            ->whereDate('scheduled_at', $date)
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->whereNull('deleted_at')
            ->count();

        return max(0, $this->max_patients - $booked);
    }

    public function isFullOn(string $date): bool
    {
        return $this->remainingQuota($date) === 0;
    }

    public function getDayLabelAttribute(): string
    {
        return ['', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'][$this->day_of_week] ?? '—';
    }
}
