<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponser;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Kiloan;
use App\Models\Satuan;
use App\Models\Waktu;
use Carbon\Carbon;
use Validator;

class DashboardController extends Controller
{
    use ApiResponser;
    public function FunctionName(Type $var = null)
    {
        # code...
    }
    //
}
