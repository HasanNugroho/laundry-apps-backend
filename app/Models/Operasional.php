<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operasional extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'nominal',
        'keterangan',
        'idpesanan',
        'kasir',
        'jenis_service',
        'jenis',
        'outletid',
    ];
}
