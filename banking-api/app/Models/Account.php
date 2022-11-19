<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'account_type',
        'customer_id',
        'branch_code',
        'balance',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    
}
