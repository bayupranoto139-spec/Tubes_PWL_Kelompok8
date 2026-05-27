<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'specialization_id', 'licence_number',
        'consultation_fee', 'years_of_experience',
    ];

    protected $casts = [
        'consultation_fee'    => 'decimal:2',
        'years_of_experience' => 'integer',
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    
    public function getNameAttribute(): string
    {
        return $this->user->name;
    }

    public function hasScheduleOn(int $dayOfWeek): bool
    {
        return $this->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->exists();
    }
}
