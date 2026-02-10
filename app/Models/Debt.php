<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    protected $fillable = [
        'debtor_name', 'reason', 'total_amount',
        'paid_amount', 'status', 'debt_date', 'notes', 'user_id'
    ];

    protected $casts = [
        'debt_date'    => 'date',
        'total_amount' => 'decimal:2',
        'paid_amount'  => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(DebtPayment::class);
    }

    public function getRemainingAmountAttribute(): float
    {
        return $this->total_amount - $this->paid_amount;
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'paid'    => 'مسدد',
            'partial' => 'مسدد جزئياً',
            default   => 'غير مسدد',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'paid'    => 'success',
            'partial' => 'warning',
            default   => 'danger',
        };
    }

    public function updateStatus(): void
    {
        if ($this->paid_amount >= $this->total_amount) {
            $this->status = 'paid';
        } elseif ($this->paid_amount > 0) {
            $this->status = 'partial';
        } else {
            $this->status = 'unpaid';
        }
        $this->save();
    }
}
