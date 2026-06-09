<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{

    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'hospital_id', 'name', 'email', 'password', 'role',
        'phone', 'address', 'gender', 'date_of_birth', 'is_active',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'is_active'         => 'boolean',
        'date_of_birth'     => 'date',
        'email_verified_at' => 'datetime',
    ];

    
    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function patientMedicalInfo()
    {
        return $this->hasOne(PatientMedicalInfo::class);
    }

    public function patientEnrollments()
    {
        return $this->hasMany(PatientEnrollment::class);
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdminRs(): bool
    {
        return $this->role === 'admin_rs';
    }

    public function isDokter(): bool
    {
        return $this->role === 'dokter';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    public function isPasien(): bool
    {
        return $this->role === 'pasien';
    }
}