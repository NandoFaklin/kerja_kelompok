<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_kelompok';
    protected $table = 'kelompok';
    protected $fillable = [
        'mapel', // Tambahkan 'product_name' ke dalam array $fillable
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'user_id',
        'created_at',
        'update_at',
        'nama_kelompok',
    
    ];
}
