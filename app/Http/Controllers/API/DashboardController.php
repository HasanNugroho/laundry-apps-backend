<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponser;
use App\Models\Pesanan;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Validator;

class DashboardController extends Controller
{
    use ApiResponser;
    public function countpelanggan()
    {
        $all = Pelanggan::count();
        
        $currentmouth = Pelanggan::whereMonth('updated_at', date('m'))
        ->whereYear('updated_at', date('Y'))
        ->count();
        
        $dt     = Carbon::now();
        $past   = $dt->subMonth();
        $lastmouth = Pelanggan::whereMonth('updated_at', '>', $past->format('m'))
        ->whereYear('updated_at', date('Y'))
        ->count();

        return $this->success('Success!', ['curentMouth' => $currentmouth, 'lastMouth' => $lastmouth, 'total' => $all]);
    }

    public function nominalutang()
    {
        $utang = DB::table('pembayarans')->where(DB::raw('upper(status)'), 'UTANG')->sum('utang');

        return $this->success('Success!', $utang);
    }

    public function pendapatan()
    {
        $pendapatan = Pembayaran::where('updated_at', '>=', Carbon::now()->subMonth())
            ->where(DB::raw('upper(status)'), 'LUNAS')
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->get(array(
                DB::raw('Date(updated_at) as date'),
                DB::raw('sum(tagihan) as "omset"')
            ));
        
        $totalPemasukan = Pembayaran::where(DB::raw('upper(status)'), 'LUNAS')
            ->sum('tagihan');
        return $this->success('Success!', ['omsetHarian' => $pendapatan, 'totalPemasukan' => $totalPemasukan]);
    }

    public function transaksi()
    {
        $today = Pesanan::where(DB::raw('upper(status)'), 'SELESAI')->whereDate('updated_at', Carbon::today())->count();
        $yesterday = Pesanan::where(DB::raw('upper(status)'), 'SELESAI')->whereDate('updated_at', Carbon::yesterday())->count();
        
        $current_week = Pesanan::where(DB::raw('upper(status)'), 'SELESAI')->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

        $thismouth = Pesanan::where(DB::raw('upper(status)'), 'SELESAI')->whereMonth('updated_at', Carbon::now()->format('m'))
        ->whereYear('updated_at', date('Y'))
        ->count();

        $lastmouth = Pesanan::where(DB::raw('upper(status)'), 'SELESAI')->whereMonth('updated_at', Carbon::now()->subMonth()->format('m'))
        ->whereYear('updated_at', date('Y'))
        ->count();

        $all = Pesanan::where(DB::raw('upper(status)'), 'SELESAI')->count();

        return $this->success('Success!', ['today' => $today, 'yesterday' => $yesterday, 'current_week' => $current_week, 'thismouth' => $thismouth, 'lastmouth' => $lastmouth, 'total' => $all]);
    }
}
