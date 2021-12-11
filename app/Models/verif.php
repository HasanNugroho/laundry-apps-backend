<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class verif extends Model
{
    use HasFactory;
    protected $fillable = [
        'userid',
        'token'
    ];
}
