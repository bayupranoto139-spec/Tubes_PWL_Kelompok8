<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'generic_name', 'category', 'unit', 'price', 'is_active',
    ];

    protected $casts = [
        'price'     => 'decimal:2',
        'is_active' => 'boolean',
    ];

    
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
