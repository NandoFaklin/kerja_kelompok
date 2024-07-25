<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_task';
    protected $table = 'task';
    protected $fillable = [
        'nama_task',
        'nama_user',
        'id_kelompok',
        'nim_or_nip_task'
    ];

}
