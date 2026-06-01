<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'position', 'department'];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
    public function getNameAttribute(): string
    {
        return $this->user->name;
    }

    public function getHospitalAttribute()
    {
        return $this->user->hospital;
    }
}
