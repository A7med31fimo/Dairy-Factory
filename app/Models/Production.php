<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    protected $fillable = [
        'product_type', 'product_name', 'quantity',
        'unit', 'production_date', 'notes', 'user_id'
    ];

    protected $casts = [
        'production_date' => 'date',
        'quantity'        => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Arabic product type labels
    public static function productTypes(): array
    {
        return [
            'milk'   => 'حليب',
            'yogurt' => 'زبادي',
            'butter' => 'زبدة',
            'cheese' => 'جبن',
            'cream'  => 'قشدة',
            'other'  => 'أخرى',
        ];
    }
}
