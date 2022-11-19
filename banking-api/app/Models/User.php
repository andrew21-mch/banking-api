<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Model
{
    use HasFactory, HasApiTokens, Notifiable, HasRoles;

    protected $fillable = [
        'customer_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'branch_code',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function transactions()
    {
        return $this->hasManyThrough(Transaction::class, Account::class);
    }
}
