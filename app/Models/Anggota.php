<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_anggota';
    protected $table = 'anggota';
    protected $fillable = [
        'id_kelompok',
        'nama_anggota',
        'nim_or_nip_anggota',
        'role'

    ];
}
