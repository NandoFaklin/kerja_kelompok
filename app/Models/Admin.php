<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens as SanctumHasApiTokens;

class Admin extends Model
{
    use SanctumHasApiTokens,HasFactory;

    protected $table = 'validasi_user'; // Nama tabel di database

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nim_or_nip',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
