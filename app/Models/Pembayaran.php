<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;
    protected $fillable = [
        'idpesanan',
        'subtotal',
        'diskon',
        'tagihan',
        'status',
        'utang',
        'bayar',
        'metode_pembayaran',
    ];
}
