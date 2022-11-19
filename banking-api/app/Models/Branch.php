<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_code',
        'branch_name',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function transactions()
    {
        return $this->hasManyThrough(Transaction::class, Account::class);
    }

    public function getBranchCode()
    {
        return $this->branch_code;
    }

    
}
