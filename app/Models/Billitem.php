<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_id', 'item_type', 'description',
        'quantity', 'unit_price', 'subtotal',
    ];

    protected $casts = [
        'quantity'   => 'integer',
        'unit_price' => 'decimal:2',
        'subtotal'   => 'decimal:2',
    ];

    
    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    
    protected static function booted(): void
    {
        static::saving(function (self $item) {
            $item->subtotal = $item->quantity * $item->unit_price;
        });

        static::saved(function (self $item) {
            $item->bill->recalculateTotal();
        });

        static::deleted(function (self $item) {
            $item->bill->recalculateTotal();
        });
    }

    
    public function scopeOfType($query, string $type)
    {
        return $query->where('item_type', $type);
    }
}
