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
        'nama_pelanggan',
        'layanan',
        'deadline',
        'nota_transaksi',
        'status',
        'whatsapp',
        'note',
        'outletid',
        'kategori',
        'jumlah',
        'paket',
        'kasir',
    ];
    protected $casts = [
        'id' => 'string',
    ];
}
