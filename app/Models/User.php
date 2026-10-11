<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['username', 'password_hash', 'role'];

    protected $hidden = ['password_hash'];

    // Map auth to password_hash column (not standard 'password')
    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    // Laravel auth uses 'username' field, not 'email'
    public function username(): string
    {
        return 'username';
    }

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
