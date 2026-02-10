<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];
    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function milkCollections()
    {
        return $this->hasMany(MilkCollection::class);
    }

    public function productions()
    {
        return $this->hasMany(Production::class);
    }

    public function distributions()
    {
        return $this->hasMany(Distribution::class);
    }

    public function debts()
    {
        return $this->hasMany(Debt::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
