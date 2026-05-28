<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isPegawai(): bool
    {
        return $this->role === 'PEGAWAI';
    }

    public function isSdm(): bool
    {
        return $this->role === 'DIVISI-SDM';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'ADMIN';
    }

    public function perdin()
    {
        return $this->hasMany(Perdin::class);
    }
}
