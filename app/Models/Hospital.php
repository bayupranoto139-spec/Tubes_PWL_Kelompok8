<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hospital extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name', 'code', 'city', 'address', 'logo', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function patientEnrollments()
    {
        return $this->hasMany(PatientEnrollment::class);
    }

    public function doctors()
    {
        return $this->hasManyThrough(Doctor::class, User::class, 'hospital_id', 'user_id');
    }

    public function staff()
    {
        return $this->hasManyThrough(Staff::class, User::class, 'hospital_id', 'user_id');
    }
}
