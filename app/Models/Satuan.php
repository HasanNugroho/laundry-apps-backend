<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'nama_layanan',
        'harga',
        'kategori',
        'idwaktu',
        'jenis',
        'item',
        'status',
        'idoutlet',
    ];
    protected $casts = [
        'id' => 'string',
    ];
}
