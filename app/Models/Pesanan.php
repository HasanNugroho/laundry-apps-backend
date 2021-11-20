<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'idpelanggan',
        'layanan',
        'deadline',
        'nota_transaksi',
        'status',
        'note',
        'outletid',
        'kategori',
        'jumlah',
        'jenis_layanan',
        'paket',
        'kasir',
    ];
    protected $casts = [
        'id' => 'string',
    ];
}
