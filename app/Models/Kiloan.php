<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kiloan extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_layanan',
        'harga',
        'idwaktu',
        'jenis',
        'status',
        'item',
        'idoutlet',
    ];
}
