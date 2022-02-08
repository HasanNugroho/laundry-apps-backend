<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'idpelanggan',
        'note',
        'idwaktu',
        'idlayanan',
        'deadline',
        'nota_transaksi',
        'jumlah',
        'status',
        'outletid',
        'kasir',
    ];
    protected $casts = [
        'id' => 'string',
    ];
}
