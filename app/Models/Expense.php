<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'amount', 'category', 'expense_date', 'notes', 'user_id'
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount'       => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function categories(): array
    {
        return [
            'fuel'        => 'وقود',
            'maintenance' => 'صيانة',
            'salaries'    => 'رواتب',
            'packaging'   => 'تعبئة وتغليف',
            'utilities'   => 'مياه وكهرباء',
            'other'       => 'متفرقات',
        ];
    }
}
