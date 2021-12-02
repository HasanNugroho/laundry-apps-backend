<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponser;
use App\Models\Pembayaran;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Models\Pelanggan;
use App\Models\Pesanan;
use App\Models\Invite;
use App\Models\Outlet;
use App\Models\Operasional;
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
        $user_outlet = Auth::user()->outlet_id;

        $pendapatan = DB::table('operasionals')
            ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
            ->where('operasionals.created_at', '>=', Carbon::now()->subMonth())
            ->where('operasionals.jenis', 'PEMASUKAN')
            ->where('outlets.id', $user_outlet)
            ->orWhere('outlets.parent', $user_outlet)
            ->groupBy('date', 'outletid')
            ->orderBy('date', 'DESC')
            ->get(array(
                DB::raw('Date(operasionals.created_at) as date'),
                DB::raw('sum(operasionals.nominal) as "omset"'),
                // DB::raw('operasionals.outletid as outletid')
            ));

        $pengeluaran = DB::table('operasionals')
            ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
            ->where('operasionals.created_at', '>=', Carbon::now()->subMonth())
            ->where('operasionals.jenis', 'PENGELUARAN')
            ->where('outlets.id', $user_outlet)
            ->orWhere('outlets.parent', $user_outlet)
            ->groupBy('date', 'outletid')
            ->orderBy('date', 'DESC')
            ->get(array(
                DB::raw('Date(operasionals.created_at) as date'),
                DB::raw('sum(operasionals.nominal) as "omset"'),
                // DB::raw('operasionals.outletid as outletid'),
            ));
        
        $totalpendapatan = DB::table('operasionals')
            ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
            ->where('operasionals.jenis', 'PEMASUKAN')
            ->where('outlets.id', $user_outlet)
            ->orWhere('outlets.parent', $user_outlet)
            ->get(DB::raw('sum(operasionals.nominal) as "pendapatan"'));
        
        $totalpengeluaran = DB::table('operasionals')
            ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
            ->where('operasionals.jenis', 'PENGELUARAN')
            ->where('outlets.id', $user_outlet)
            ->orWhere('outlets.parent', $user_outlet)
            ->get(DB::raw('sum(operasionals.nominal) as "pengeluaran"'));

        $totalpemasukan = $totalpendapatan[0]->pendapatan - $totalpengeluaran[0]->pengeluaran;

        return $this->success('Success!', ['omsetHarian' => $pendapatan, 'pengeluaranHarian' => $pengeluaran, 'totalPemasukan' => $totalpemasukan]);
    }

    public function transaksi()
    {
        // DB::enableQueryLog(); // Enable query log


        $user_outlet = Auth::user()->outlet_id;

        $today = Pesanan::where(DB::raw('upper(status)'), 'SELESAI')->whereDate('updated_at', Carbon::today())->count();
        // $today = DB::table('pesanans')
        // ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        // ->where('outlets.id', $user_outlet)
        // ->orWhere('outlets.parent', $user_outlet)
        // ->where(DB::raw('upper(pesanans.status)'), 'SELESAI')
        // ->whereDate('pesanans.updated_at',Carbon::today())
        // ->count();

        $yesterday = Pesanan::where(DB::raw('upper(status)'), 'SELESAI')->whereDate('updated_at', Carbon::yesterday())->count();
        // $yesterday = DB::table('pesanans')
        // ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        // ->where(DB::raw('upper(pesanans.status)'), 'SELESAI')
        // ->where('outlets.id', $user_outlet)
        // ->orWhere('outlets.parent', $user_outlet)
        // ->whereDate('pesanans.updated_at', Carbon::yesterday())
        // ->count();
        
        $current_week = Pesanan::where(DB::raw('upper(status)'), 'SELESAI')->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

        $thismouth = Pesanan::where(DB::raw('upper(status)'), 'SELESAI')->whereMonth('updated_at', Carbon::now()->format('m'))
        ->whereYear('updated_at', date('Y'))
        ->count();

        $lastmouth = Pesanan::where(DB::raw('upper(status)'), 'SELESAI')->whereMonth('updated_at', Carbon::now()->subMonth()->format('m'))
        ->whereYear('updated_at', date('Y'))
        ->count();
        // dd(DB::getQueryLog()); // Show results of log

        $all = Pesanan::where(DB::raw('upper(status)'), 'SELESAI')->count();

        return $this->success('Success!', ['today' => $today, 'yesterday' => $yesterday, 'current_week' => $current_week, 'thismouth' => $thismouth, 'lastmouth' => $lastmouth, 'total' => $all, 'time' => Carbon::today()]);
    }

    public function pengeluaran(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nominal' => 'required|integer',
            'keterangan' => 'required|string',
        ]);

        if($validator->fails()){
            return $this->error('Create Invite Failed!', [ 'message' => $validator->errors()], 400);       
        }

        $uuid = Str::uuid();
        $user_outlet = Auth::user()->outlet_id;
        $insert = Operasional::create([
            'id' => $uuid,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'jenis' => 'PENGELUARAN',
            'outletid' => $user_outlet, 
        ]);

        if($insert){
            return $this->success('Success!',"successfully created data!");
        }else{
            return $this->error('Failed!', [ 'message' => 'created data failed!'], 400);
        }
    }

    public function countTransaksi()
    {
        $user_outlet = Auth::user()->outlet_id;
        $transaksi = DB::select('SELECT 
        COUNT(IF(upper(ps.status) = "DIBATALKAN", 1, NULL)) "dibatalkan",
        COUNT(IF(upper(ps.status) = "SELESAI", 1, NULL)) "selesai",
        COUNT(IF(upper(ps.status) = "PACKING", 1, NULL)) "packing",
        COUNT(IF(upper(ps.status) = "PROSES", 1, NULL)) "proses",
        COUNT(IF(upper(ps.status) = "ANTERIAN", 1, NULL)) "antrian"
        FROM
            pesanans ps
        LEFT JOIN
            outlets o 
        ON 
            ps.outletid = o.id
        WHERE 
            o.parent = ? or o.id = ?', [$user_outlet, $user_outlet]);

        return $this->success('Success!', $transaksi);
    }

}
