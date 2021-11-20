<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetKecamatan extends Model
{
    use HasFactory;
    public $incrementing = FALSE;
    protected $fillable = [
        'id',
        'id_kota',
        'nama',
    ];
}
