<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waktu extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'nama',
        'waktu',
        'jenis',
        'status',
        'paket',
        'idoutlet',
    ];
    protected $casts = [
        'id' => 'string',
    ];
}
