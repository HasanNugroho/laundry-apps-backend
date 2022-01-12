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
    public function countpelangganOwner()
    {
        $user_outlet = Auth::user()->outlet_id;
        
        $currentmouth = DB::table('pelanggans')
        ->leftJoin('outlets', 'pelanggans.outletid', '=', 'outlets.id')
        ->whereMonth('pelanggans.created_at', date('m'))
        ->whereYear('pelanggans.created_at', date('Y'))
        ->where('outlets.id', $user_outlet)
        ->orWhere('outlets.parent', $user_outlet)
        ->count();
        
        // $currentmouth = Pelanggan::whereMonth('created_at', date('m'))
        // ->whereYear('created_at', date('Y'))
        // ->count();
        
        $dt     = Carbon::now();
        $past   = $dt->subMonth();
        $lastmouth = DB::table('pelanggans')
        ->leftJoin('outlets', 'pelanggans.outletid', '=', 'outlets.id')
        ->whereMonth('pelanggans.created_at', '<=' , $past->format('m'))
        ->whereYear('pelanggans.created_at', date('Y'))
        ->where('outlets.id', $user_outlet)
        ->orWhere('outlets.parent', $user_outlet)
        ->count();
        
        // $lastmouth = Pelanggan::whereMonth('created_at', '>', $past->format('m'))
        // ->whereYear('created_at', date('Y'))
        // ->count();
        
        $all = DB::table('pelanggans')
        ->leftJoin('outlets', 'pelanggans.outletid', '=', 'outlets.id')
        ->where('outlets.id', $user_outlet)
        ->orWhere('outlets.parent', $user_outlet)
        ->count();

        return $this->success('Success!', ['curentMouth' => $currentmouth, 'lastMouth' => $lastmouth, 'total' => $all]);
    }

    public function nominalutangOwner(Request $request)
    {
        $user_outlet = Auth::user()->outlet_id;
        $utang = DB::table('pembayarans')->where(DB::raw('upper(pembayarans.status)'), 'UTANG')
        ->whereBetween('pembayarans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
        ->rightJoin('pesanans', 'pesanans.id', '=', 'pembayarans.idpesanan')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->where('outlets.id', $user_outlet)
        ->orWhere('outlets.parent', $user_outlet)
        ->sum('utang');

        return $this->success('Success!', $utang);
    }
    
    public function nominalutangKasir()
    {
        $user_outlet = Auth::user()->outlet_id;
        $utang = DB::table('pembayarans')->where(DB::raw('upper(pembayarans.status)'), 'UTANG')
        ->rightJoin('pesanans', 'pesanans.id', '=', 'pembayarans.idpesanan')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->where('outlets.id', $user_outlet)
        ->sum('utang');

        return $this->success('Success!', $utang);
    }

    public function pendapatanOwner(Request $request)
    {
        $user_outlet = Auth::user()->outlet_id;
        // DB::enableQueryLog(); // Enable query log

        // $pendapatan = DB::table('operasionals')
        //     ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
        //     ->where('operasionals.created_at', '>=', Carbon::now()->subMonth())
        //     ->where('operasionals.jenis', 'PEMASUKAN')
        //     ->where('outlets.id', $user_outlet)
        //     ->orWhere('outlets.parent', $user_outlet)
        //     ->groupBy('date', 'outletid')
        //     ->orderBy('date', 'DESC')
        //     ->get(array(
        //         DB::raw('Date(operasionals.created_at) as date'),
        //         DB::raw('sum(operasionals.nominal) as "omset"'),
        //         // DB::raw('operasionals.outletid as outletid')
        //     ));
        if($request->from || $request->to){
            $pendapatan = DB::select('
            with recursive Date_Ranges AS (
                select \''. $request->from . '\' as Date
                union all
                select Date + interval 1 day
                from Date_Ranges
                where Date < \''. $request->to . '\'), 
                data_pemasukan AS (
                SELECT case when sum(o.nominal) IS NULL then 0 else sum(o.nominal) end as data_pemasukan, DATE_FORMAT(o.created_at, \'%Y-%m-%d\') as date from operasionals o LEFT JOIN outlets ou on o.outletid = ou.id where o.jenis = \'PEMASUKAN\' and ou.id = \''. $user_outlet . '\' or ou.parent = \''. $user_outlet . '\' GROUP BY DATE_FORMAT(o.created_at, \'%Y-%m-%d\')
                )
                
                SELECT dr.Date as date, (case when (SELECT dps.data_pemasukan from data_pemasukan dps where dps.date = dr.Date) IS NULL then 0 else (SELECT dps.data_pemasukan from data_pemasukan dps where dps.date = dr.Date) end) as omset FROM Date_Ranges dr GROUP BY dr.Date ORDER BY dr.Date desc
            ');
        }else{
            $pendapatan = DB::select('
            with recursive Date_Ranges AS (
                select CURRENT_DATE - INTERVAL 30 day as Date
                union all
                select Date + interval 1 day
                from Date_Ranges
                where Date < CURRENT_DATE), 
                data_pemasukan AS (
                SELECT case when sum(o.nominal) IS NULL then 0 else sum(o.nominal) end as data_pemasukan, DATE_FORMAT(o.created_at, \'%Y-%m-%d\') as date from operasionals o LEFT JOIN outlets ou on o.outletid = ou.id where o.jenis = \'PEMASUKAN\' and ou.id = \''. $user_outlet . '\' or ou.parent = \''. $user_outlet . '\' GROUP BY DATE_FORMAT(o.created_at, \'%Y-%m-%d\')
                )
                
                SELECT dr.Date as date, (case when (SELECT dps.data_pemasukan from data_pemasukan dps where dps.date = dr.Date) IS NULL then 0 else (SELECT dps.data_pemasukan from data_pemasukan dps where dps.date = dr.Date) end) as omset FROM Date_Ranges dr GROUP BY dr.Date ORDER BY dr.Date desc
            ');
        }
        
        // dd(DB::getQueryLog()); // Show results of log

        $totalpendapatan = DB::table('operasionals')
            ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
            ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addday(1)->toDateString()])
            ->where('operasionals.jenis', 'PEMASUKAN')
            ->where('outlets.id', $user_outlet)
            ->orWhere('outlets.parent', $user_outlet)
            ->get(DB::raw('sum(operasionals.nominal) as "pendapatan"'));
        
        $totalpengeluaran = DB::table('operasionals')
            ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
            ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addday(1)->toDateString()])
            ->where('operasionals.jenis', 'PENGELUARAN')
            ->where('outlets.id', $user_outlet)
            ->orWhere('outlets.parent', $user_outlet)
            ->get(DB::raw('sum(operasionals.nominal) as "pengeluaran"'));

        $totalpemasukan = $totalpendapatan[0]->pendapatan - $totalpengeluaran[0]->pengeluaran;

        return $this->success('Success!', ['omsetHarian' => $pendapatan, 'totalPemasukan' => $totalpemasukan]);
    }
    
    public function pendapatanKasir()
    {
        $user_outlet = Auth::user()->outlet_id;

        $pendapatan = DB::table('operasionals')
            ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
            ->where('operasionals.created_at', '>=', Carbon::now()->subMonth())
            ->where('operasionals.jenis', 'PEMASUKAN')
            ->where('outlets.id', $user_outlet)
            ->groupBy('date', 'outletid')
            ->orderBy('date', 'DESC')
            ->get(array(
                DB::raw('Date(operasionals.created_at) as date'),
                DB::raw('sum(operasionals.nominal) as "omset"'),
                // DB::raw('operasionals.outletid as outletid')
            ));

        $totalpendapatan = DB::table('operasionals')
            ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
            ->where('operasionals.jenis', 'PEMASUKAN')
            ->where('outlets.id', $user_outlet)
            ->get(DB::raw('sum(operasionals.nominal) as "pendapatan"'));
        
        $totalpengeluaran = DB::table('operasionals')
            ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
            ->where('operasionals.jenis', 'PENGELUARAN')
            ->where('outlets.id', $user_outlet)
            ->get(DB::raw('sum(operasionals.nominal) as "pengeluaran"'));

        $totalpemasukan = $totalpendapatan[0]->pendapatan - $totalpengeluaran[0]->pengeluaran;

        return $this->success('Success!', ['omsetHarian' => $pendapatan, 'totalPemasukan' => $totalpemasukan]);
    }

    public function pengeluaranOwner(Request $request)
    {
        $user_outlet = Auth::user()->outlet_id;

        // $pengeluaran = DB::table('operasionals')
        //     ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
        //     ->where('operasionals.created_at', '>=', Carbon::now()->subMonth())
        //     ->where('operasionals.jenis', 'PENGELUARAN')
        //     ->where('outlets.id', $user_outlet)
        //     ->orWhere('outlets.parent', $user_outlet)
        //     ->groupBy('date', 'outletid')
        //     ->orderBy('date', 'DESC')
        //     ->get(array(
        //         DB::raw('Date(operasionals.created_at) as date'),
        //         DB::raw('sum(operasionals.nominal) as "omset"'),
        //         // DB::raw('operasionals.outletid as outletid'),
        //     ));

        if($request->from || $request->to){
            $pendapatan = DB::select('
            with recursive Date_Ranges AS (
                select \''. $request->from . '\' as Date
                union all
                select Date + interval 1 day
                from Date_Ranges
                where Date < \''. $request->to . '\'), 
                data_pengeluaran AS (
                SELECT case when sum(o.nominal) IS NULL then 0 else sum(o.nominal) end as data_pengeluaran, DATE_FORMAT(o.created_at, \'%Y-%m-%d\') as date from operasionals o LEFT JOIN outlets ou on o.outletid = ou.id where o.jenis = \'PENGELUARAN\' and ou.id = \''. $user_outlet . '\' or ou.parent = \''. $user_outlet . '\' GROUP BY DATE_FORMAT(o.created_at, \'%Y-%m-%d\')
                )
                
                SELECT dr.Date as date, (case when (SELECT dps.data_pengeluaran from data_pengeluaran dps where dps.date = dr.Date) IS NULL then 0 else (SELECT dps.data_pengeluaran from data_pengeluaran dps where dps.date = dr.Date) end) as pengeluaran FROM Date_Ranges dr GROUP BY dr.Date ORDER BY dr.Date desc
            ');
        }else{
            $pendapatan = DB::select('
            with recursive Date_Ranges AS (
                select CURRENT_DATE - INTERVAL 30 day as Date
                union all
                select Date + interval 1 day
                from Date_Ranges
                where Date < CURRENT_DATE), 
                data_pengeluaran AS (
                SELECT case when sum(o.nominal) IS NULL then 0 else sum(o.nominal) end as data_pengeluaran, DATE_FORMAT(o.created_at, \'%Y-%m-%d\') as date from operasionals o LEFT JOIN outlets ou on o.outletid = ou.id where o.jenis = \'PENGELUARAN\' and ou.id = \''. $user_outlet . '\' or ou.parent = \''. $user_outlet . '\' GROUP BY DATE_FORMAT(o.created_at, \'%Y-%m-%d\')
                )
                
                SELECT dr.Date as date, (case when (SELECT dps.data_pengeluaran from data_pengeluaran dps where dps.date = dr.Date) IS NULL then 0 else (SELECT dps.data_pengeluaran from data_pengeluaran dps where dps.date = dr.Date) end) as pengeluaran FROM Date_Ranges dr GROUP BY dr.Date ORDER BY dr.Date desc
            ');
        }
        
        $totalpengeluaran = DB::table('operasionals')
            ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
            ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addday(1)->toDateString()])
            ->where('operasionals.jenis', 'PENGELUARAN')
            ->where('outlets.id', $user_outlet)
            ->orWhere('outlets.parent', $user_outlet)
            ->get(DB::raw('sum(operasionals.nominal) as "pengeluaran"'));


        return $this->success('Success!', ['pengeluaranHarian' => $pengeluaran, 'totalPengeluaran' => $totalpengeluaran[0]->pengeluaran]);
    }

    public function pengeluaranKasir()
    {
        $user_outlet = Auth::user()->outlet_id;

        $pengeluaran = DB::table('operasionals')
            ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
            // ->where('operasionals.created_at', '>=', Carbon::now()->subMonth())
            ->where('operasionals.jenis', 'PENGELUARAN')
            ->where('outlets.id', $user_outlet)
            ->groupBy('date', 'outletid')
            ->orderBy('date', 'DESC')
            ->get(array(
                DB::raw('Date(operasionals.created_at) as date'),
                DB::raw('sum(operasionals.nominal) as "omset"'),
                // DB::raw('operasionals.outletid as outletid'),
            ));
        
        $totalpengeluaran = DB::table('operasionals')
            ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
            ->where('operasionals.jenis', 'PENGELUARAN')
            ->where('outlets.id', $user_outlet)
            ->get(DB::raw('sum(operasionals.nominal) as "pengeluaran"'));


        return $this->success('Success!', ['pengeluaranHarian' => $pengeluaran, 'totalPengeluaran' => $totalpengeluaran[0]->pengeluaran]);
    }

    public function transaksiOwner(Request $request)
    {
        $user_outlet = Auth::user()->outlet_id;
        // $today = DB::table('pesanans')
        // ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        // ->whereDate('pesanans.updated_at',Carbon::today())
        // ->where(DB::raw('upper(pesanans.status)'), 'SELESAI')
        // ->where('outlets.id', $user_outlet)
        // ->orWhere('outlets.parent', $user_outlet)
        // ->count();

        // $yesterday = DB::table('pesanans')
        // ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        // ->whereDate('pesanans.updated_at', Carbon::yesterday())
        // ->where(DB::raw('upper(pesanans.status)'), 'SELESAI')
        // ->where('outlets.id', $user_outlet)
        // ->orWhere('outlets.parent', $user_outlet)
        // ->count();
        
        // $current_week = DB::table('pesanans')
        // ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        // ->whereBetween('pesanans.updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        // ->where(DB::raw('upper(pesanans.status)'), 'SELESAI')
        // ->where('outlets.id', $user_outlet)
        // ->orWhere('outlets.parent', $user_outlet)
        // ->count();

        // $thismouth = DB::table('pesanans')
        // ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        // ->whereMonth('pesanans.updated_at', Carbon::now()->format('m'))
        // ->whereYear('pesanans.updated_at', date('Y'))
        // ->where(DB::raw('upper(pesanans.status)'), 'SELESAI')
        // ->where('outlets.id', $user_outlet)
        // ->orWhere('outlets.parent', $user_outlet)
        // ->count();

        // $lastmouth = DB::table('pesanans')
        // ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        // ->whereMonth('pesanans.updated_at', Carbon::now()->subMonth()->format('m'))
        // ->whereYear('pesanans.updated_at', date('Y'))
        // ->where(DB::raw('upper(pesanans.status)'), 'SELESAI')
        // ->where('outlets.id', $user_outlet)
        // ->orWhere('outlets.parent', $user_outlet)
        // ->count();

        $all = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
        ->where(DB::raw('upper(pesanans.status)'), 'SELESAI')
        ->where('outlets.id', $user_outlet)
        ->orWhere('outlets.parent', $user_outlet)
        ->count();

        return $this->success('Success!', ['total' => $all]);
    }
    
    public function transaksiKasir()
    {
        $user_outlet = Auth::user()->outlet_id;
        $today = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->whereDate('pesanans.updated_at',Carbon::today())
        ->where(DB::raw('upper(pesanans.status)'), 'SELESAI')
        ->where('outlets.id', $user_outlet)
        ->count();

        $yesterday = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->whereDate('pesanans.updated_at', Carbon::yesterday())
        ->where(DB::raw('upper(pesanans.status)'), 'SELESAI')
        ->where('outlets.id', $user_outlet)
        ->count();
        
        $current_week = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->whereBetween('pesanans.updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        ->where(DB::raw('upper(pesanans.status)'), 'SELESAI')
        ->where('outlets.id', $user_outlet)
        ->count();

        $thismouth = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->whereMonth('pesanans.updated_at', Carbon::now()->format('m'))
        ->whereYear('pesanans.updated_at', date('Y'))
        ->where(DB::raw('upper(pesanans.status)'), 'SELESAI')
        ->where('outlets.id', $user_outlet)
        ->count();

        $lastmouth = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->whereMonth('pesanans.updated_at', Carbon::now()->subMonth()->format('m'))
        ->whereYear('pesanans.updated_at', date('Y'))
        ->where(DB::raw('upper(pesanans.status)'), 'SELESAI')
        ->where('outlets.id', $user_outlet)
        ->count();

        $all = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->where(DB::raw('upper(pesanans.status)'), 'SELESAI')
        ->where('outlets.id', $user_outlet)
        ->count();

        return $this->success('Success!', ['today' => $today, 'yesterday' => $yesterday, 'current_week' => $current_week, 'thismouth' => $thismouth, 'lastmouth' => $lastmouth, 'total' => $all]);
    }

    public function countTransaksiAdmin(Request $request)
    {
        $user_outlet = Auth::user()->outlet_id;
        $selesai = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
        ->where(DB::raw('upper(pesanans.status)'), 'SELESAI')
        ->where('outlets.id', $user_outlet)
        ->orWhere('outlets.parent', $user_outlet)
        ->count();

        $proses = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
        ->where(DB::raw('upper(pesanans.status)'), 'PROSES')
        ->where('outlets.id', $user_outlet)
        ->orWhere('outlets.parent', $user_outlet)
        ->count();
        
        $antrian = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
        ->where(DB::raw('upper(pesanans.status)'), 'ANTRIAN')
        ->where('outlets.id', $user_outlet)
        ->orWhere('outlets.parent', $user_outlet)
        ->count();

        $dibatalkan = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
        ->where(DB::raw('upper(pesanans.status)'), 'DIBATALKAN')
        ->where('outlets.id', $user_outlet)
        ->orWhere('outlets.parent', $user_outlet)
        ->count();

        $all = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
        ->where('outlets.id', $user_outlet)
        ->orWhere('outlets.parent', $user_outlet)
        ->count();

        return $this->success('Success!', ['selesai' => $selesai, 'proses' => $proses, 'antrian' => $antrian, 'dibatalkan' => $dibatalkan, 'total' => $all]);
    }
    
    public function countTransaksiKasir()
    {
        $user_outlet = Auth::user()->outlet_id;
        $selesai = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->where(DB::raw('upper(pesanans.status)'), 'SELESAI')
        ->where('outlets.id', $user_outlet)
        ->count();

        $proses = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->where(DB::raw('upper(pesanans.status)'), 'PROSES')
        ->where('outlets.id', $user_outlet)
        ->count();
        
        $antrian = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->where(DB::raw('upper(pesanans.status)'), 'ANTRIAN')
        ->where('outlets.id', $user_outlet)
        ->count();

        $dibatalkan = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->where(DB::raw('upper(pesanans.status)'), 'DIBATALKAN')
        ->where('outlets.id', $user_outlet)
        ->count();

        $all = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->where('outlets.id', $user_outlet)
        ->count();

        return $this->success('Success!', ['selesai' => $selesai, 'proses' => $proses, 'antrian' => $antrian, 'dibatalkan' => $dibatalkan, 'total' => $all]);
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
            'kasir' => Auth::user()->username,
            'outletid' => $user_outlet, 
        ]);

        if($insert){
            return $this->success('Success!',"successfully created data!");
        }else{
            return $this->error('Failed!', [ 'message' => 'created data failed!'], 400);
        }
    }

    public function countTransaksiOwner(Request $request)
    {
        $user_outlet = Auth::user()->outlet_id;
        $transaksi = DB::select('SELECT 
        COUNT(IF(upper(ps.status) = "DIBATALKAN", 1, NULL)) "dibatalkan",
        COUNT(IF(upper(ps.status) = "SELESAI", 1, NULL)) "selesai",
        COUNT(IF(upper(ps.status) = "PACKING", 1, NULL)) "packing",
        COUNT(IF(upper(ps.status) = "PROSES", 1, NULL)) "proses",
        COUNT(IF(upper(ps.status) = "ANTRIAN", 1, NULL)) "antrian"
        FROM
            pesanans ps
        LEFT JOIN
            outlets o 
        ON 
            ps.outletid = o.id
        WHERE 
            o.parent = ? or o.id = ? and DATE(ps.created_at) BETWEEN ? AND ? ' , [$user_outlet, $user_outlet, $request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()]);

        return $this->success('Success!', $transaksi);
    }

    public function daftarKasirOwner(Request $request)
    {
        $user_outlet = Auth::user()->outlet_id;
        $users = DB::table('users')
        ->leftJoin('outlets', 'users.outlet_id', '=', 'outlets.id')
        ->whereBetween('users.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
        ->where('outlets.id', $user_outlet)
        ->orWhere('outlets.parent', $user_outlet)
        ->select('users.uid','users.username', 'users.email', 'users.role', 'users.alamat', 'users.whatsapp', 'users.status', 'users.created_at as date_join', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.alamat')
        ->orderBy('users.created_at', 'DESC')
        ->get();

        return $this->success('Success!', $users);
    }
    
    public function operasionalOwner(Request $request)
    {
        $user_outlet = Auth::user()->outlet_id;
        $operasional = DB::table('operasionals')
        ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
        ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
        ->where('outlets.id', $user_outlet)
        ->orWhere('outlets.parent', $user_outlet)
        ->select('operasionals.*', 'outlets.nama_outlet')
        ->get();
        
        $totalPendapatan = DB::table('operasionals')
        ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
        ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
        ->where('operasionals.jenis', 'PEMASUKAN')
        ->where('outlets.id', $user_outlet)
        ->orWhere('outlets.parent', $user_outlet)
        ->select(DB::raw('sum(operasionals.nominal) as "pendapatan"'))
        ->get();

        $totalPengeluaran = DB::table('operasionals')
        ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
        ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
        ->where('operasionals.jenis', 'PENGELUARAN')
        ->where('outlets.id', $user_outlet)
        ->orWhere('outlets.parent', $user_outlet)
        ->select(DB::raw('sum(operasionals.nominal) as "pengeluaran"'))
        ->get();

        $omset = $totalPendapatan[0]->pendapatan - $totalPengeluaran[0]->pengeluaran;

        return $this->success('Success!', ['total_pendapatan' => $totalPendapatan[0]->pendapatan ? $totalPendapatan[0]->pendapatan : 0, 'total_pengeluaran' => $totalPengeluaran[0]->pengeluaran ? $totalPengeluaran[0]->pengeluaran : 0, 'omset' => $omset, 'data' => $operasional]);
    }
    
    public function operasionalKaryawan()
    {
        $user_outlet = Auth::user()->outlet_id;
        $operasional = DB::table('operasionals')
        ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
        ->where('outlets.id', $user_outlet)
        ->select('operasionals.*', 'outlets.nama_outlet')
        ->get();
        
        $totalPendapatan = DB::table('operasionals')
        ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
        ->where('operasionals.jenis', 'PENDAPATAN')
        ->where('outlets.id', $user_outlet)
        ->select(DB::raw('sum(operasionals.nominal) as "pendapatan"'))
        ->get();
        
        $totalPengeluaran = DB::table('operasionals')
        ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
        ->where('operasionals.jenis', 'PENGELUARAN')
        ->where('outlets.id', $user_outlet)
        ->select(DB::raw('sum(operasionals.nominal) as "pengeluaran"'))
        ->get();

        $omset = $totalPendapatan[0]->pendapatan - $totalPengeluaran[0]->pengeluaran;

        return $this->success('Success!', ['total_pendapatan' => $totalPendapatan[0]->pendapatan ? $totalPendapatan[0]->pendapatan : 0, 'total_pengeluaran' => $totalPengeluaran[0]->pengeluaran ? $totalPengeluaran[0]->pengeluaran : 0, 'omset' => $omset, 'data' => $operasional]);
    }
    
    public function searchAdmin(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'search' => 'required|string',
            'q' => 'required|string'
        ]);
        
        if($validator->fails()){
            return $this->error('Failed!', [ 'message' => $validator->errors()], 400);       
        }
        
        $search = '';
        $user_outlet = Auth::user()->outlet_id;
        if($request->search == 'kasir'){
            $search = DB::table('users')
            ->leftJoin('outlets', 'users.outlet_id', '=', 'outlets.id')
            ->where(function($query) use($request) {
                $query;
                $query->where('users.username', 'like', '%' . $request->q . '%');
                $query->orwhere('users.alamat', 'like', '%' . $request->q . '%');
                $query->orwhere('users.whatsapp', 'like', '%' . $request->q . '%');
                $query->orwhere('users.email', 'like', '%' . $request->q . '%');
                // $query->orwhere('outlets.nama_outlet', 'like', '%' . $request->q . '%');
                // $query->orwhere('outlets.alamat', 'like', '%' . $request->q . '%');
            })
            ->where('outlets.id', $user_outlet)
            ->orwhere('outlets.parent', $user_outlet)
            ->select('users.uid','users.username', 'users.email', 'users.role', 'users.alamat', 'users.whatsapp', 'users.status', 'users.created_at as date_join', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.alamat', 'outlets.sosial_media as sosial_media')
            ->get();
        }
        
        if($request->search == 'pelanggan'){
            $user_outlet = Auth::user()->outlet_id;
            $search = DB::table('pelanggans')
            ->leftJoin('outlets', 'pelanggans.outletid', '=', 'outlets.id')
            ->where('pelanggans.nama', 'like', '%' . $request->q . '%')
            ->where('outlets.id', $user_outlet)
            ->orWhere('outlets.parent', $user_outlet)
            ->select('pelanggans.id', 'pelanggans.nama', 'pelanggans.whatsapp', 'pelanggans.alamat', 'pelanggans.created_at')
            ->get();
        }
        
        if($request->search == 'pesanan'){
            $user_outlet = Auth::user()->outlet_id;
            if($request->status){
                $search = DB::table('pesanans')
                ->leftJoin('pelanggans', 'pesanans.idpelanggan', '=', 'pelanggans.id')
                ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
                ->leftJoin('services', 'pesanans.idlayanan', '=', 'services.id')
                ->leftJoin('waktus', 'pesanans.idwaktu', '=', 'waktus.id')
                ->rightJoin('pembayarans', 'pesanans.id', '=', 'pembayarans.idpesanan')
                ->where(function($query) use($request) {
                    $query;
                    $query->where(DB::raw('lower(pelanggans.nama)'), 'like', '%' . str::lower($request->q) . '%');
                    $query->orwhere(DB::raw('lower(services.nama_layanan)'), 'like', '%' . str::lower($request->q) . '%');
                    $query->orwhere('pelanggans.whatsapp', 'like', '%' . $request->q . '%');
                    // $query->orwhere('pesanans.kasir', 'like', '%' . $request->q . '%');
                    $query->orwhere('pesanans.nota_transaksi', 'like', '%' . $request->q . '%');
                    $query->orwhere('pesanans.created_at', 'like', '%' . $request->q . '%');
                    // $query->orwhere('waktus.nama', 'like', '%' . $request->q . '%');
                    // $query->orwhere('waktus.paket', 'like', '%' . $request->q . '%');
                    // $query->orwhere('pembayarans.diskon', 'like', '%' . $request->q . '%');
                    // $query->orwhere('outlets.nama_outlet', 'like', '%' . $request->q . '%');
                })
                // ->where('pesanans.status', 'SELESAI')
                ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addday(1)->toDateString()])
                ->where('pesanans.status', str::upper($request->status))
                ->where('outlets.id', $user_outlet)
                ->orWhere('outlets.parent', $user_outlet)
                ->select('pesanans.*', 'pelanggans.nama', 'pelanggans.whatsapp', 'pelanggans.alamat', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.sosial_media', 'services.nama_layanan', 'services.harga', 'services.kategori', 'services.jenis', 'services.item', 'pembayarans.status as statusPembayaran', 'pembayarans.metode_pembayaran', 'pembayarans.subtotal', 'pembayarans.diskon', 'pembayarans.utang', 'pembayarans.tagihan', 'pembayarans.bayar', 'waktus.nama as nama_waktu', 'waktus.waktu as durasi', 'waktus.paket as paket_waktu', 'waktus.jenis as jenis_waktu')
                ->get();
            }else{
                $search = DB::table('pesanans')
                ->leftJoin('pelanggans', 'pesanans.idpelanggan', '=', 'pelanggans.id')
                ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
                ->leftJoin('services', 'pesanans.idlayanan', '=', 'services.id')
                ->leftJoin('waktus', 'pesanans.idwaktu', '=', 'waktus.id')
                ->rightJoin('pembayarans', 'pesanans.id', '=', 'pembayarans.idpesanan')
                ->where(function($query) use($request) {
                    $query;
                    $query->where(DB::raw('lower(pelanggans.nama)'), 'like', '%' . str::lower($request->q) . '%');
                    $query->orwhere(DB::raw('lower(services.nama_layanan)'), 'like', '%' . str::lower($request->q) . '%');
                    $query->orwhere('pelanggans.whatsapp', 'like', '%' . $request->q . '%');
                    // $query->orwhere('pesanans.kasir', 'like', '%' . $request->q . '%');
                    $query->orwhere('pesanans.nota_transaksi', 'like', '%' . $request->q . '%');
                    $query->orwhere('pesanans.created_at', 'like', '%' . $request->q . '%');
                    // $query->orwhere('waktus.nama', 'like', '%' . $request->q . '%');
                    // $query->orwhere('waktus.paket', 'like', '%' . $request->q . '%');
                    // $query->orwhere('pembayarans.diskon', 'like', '%' . $request->q . '%');
                    // $query->orwhere('outlets.nama_outlet', 'like', '%' . $request->q . '%');
                })
                // ->where('pesanans.status', 'SELESAI')
                ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addday(1)->toDateString()])
                ->where('outlets.id', $user_outlet)
                ->orWhere('outlets.parent', $user_outlet)
                ->select('pesanans.*', 'pelanggans.nama', 'pelanggans.whatsapp', 'pelanggans.alamat', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.sosial_media', 'services.nama_layanan', 'services.harga', 'services.kategori', 'services.jenis', 'services.item', 'pembayarans.status as statusPembayaran', 'pembayarans.metode_pembayaran', 'pembayarans.subtotal', 'pembayarans.diskon', 'pembayarans.utang', 'pembayarans.tagihan', 'pembayarans.bayar', 'waktus.nama as nama_waktu', 'waktus.waktu as durasi', 'waktus.paket as paket_waktu', 'waktus.jenis as jenis_waktu')
                ->get();
            }
        }
        
        if($request->search == 'operasional'){
            $user_outlet = Auth::user()->outlet_id;
            $search = DB::table('operasionals')
            ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
            ->where(function($query) use($request) {
                $query;
                $query->where('operasionals.keterangan', 'like', '%' . $request->q . '%');
                $query->where('operasionals.jenis', 'like', '%' . $request->jenis . '%');
                $query->orWhere('operasionals.nominal', 'like', '%' . $request->q . '%');
            })
            ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addday(1)->toDateString()])
            // ->where('pesanans.status', 'SELESAI')
            ->where('outlets.id', $user_outlet)
            ->orWhere('outlets.parent', $user_outlet)
            ->select('operasionals.*')
            ->get();
        }
        
        return $this->success('Success!', $search);
    }
    
    public function searchKasir(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'search' => 'required|string',
            'q' => 'required|string'
        ]);
        
        if($validator->fails()){
            return $this->error('Failed!', [ 'message' => $validator->errors()], 400);       
        }
        
        $search = '';
        $user_outlet = Auth::user()->outlet_id;
        
        if(Str::lower($request->search) == 'pelanggan'){
            $user_outlet = Auth::user()->outlet_id;
            $search = DB::table('pelanggans')
            ->leftJoin('outlets', 'pelanggans.outletid', '=', 'outlets.id')
            ->where(DB::raw('lower(pelanggans.nama)'), 'like', '%' . str::lower($request->q) . '%')
            ->where('outlets.id', $user_outlet)
            ->select('pelanggans.id', 'pelanggans.nama', 'pelanggans.whatsapp', 'pelanggans.alamat', 'pelanggans.created_at')
            ->get();
        }
        
        if(Str::lower($request->search) == 'pesanan'){
            $user_outlet = Auth::user()->outlet_id;
            if($request->status){
                $search = DB::table('pesanans')
                ->leftJoin('pelanggans', 'pesanans.idpelanggan', '=', 'pelanggans.id')
                ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
                ->leftJoin('services', 'pesanans.idlayanan', '=', 'services.id')
                ->leftJoin('waktus', 'pesanans.idwaktu', '=', 'waktus.id')
                ->rightJoin('pembayarans', 'pesanans.id', '=', 'pembayarans.idpesanan')
                ->where(function($query) use($request) {
                    $query;
                    $query->where(DB::raw('lower(pelanggans.nama)'), 'like', '%' . str::lower($request->q) . '%');
                    $query->orwhere(DB::raw('lower(services.nama_layanan)'), 'like', '%' . str::lower($request->q) . '%');
                    $query->orwhere('pelanggans.whatsapp', 'like', '%' . $request->q . '%');
                    $query->orwhere('pesanans.nota_transaksi', 'like', '%' . $request->q . '%');
                })
                ->where('outlets.id', $user_outlet)
                ->where('pesanans.status', str::upper($request->status))
                ->select('pesanans.*', 'pelanggans.nama', 'pelanggans.whatsapp', 'pelanggans.alamat', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.sosial_media', 'services.nama_layanan', 'services.harga', 'services.kategori', 'services.jenis', 'services.item', 'pembayarans.status as statusPembayaran', 'pembayarans.metode_pembayaran', 'pembayarans.subtotal', 'pembayarans.diskon', 'pembayarans.utang', 'pembayarans.tagihan', 'pembayarans.bayar', 'waktus.nama as nama_waktu', 'waktus.waktu as durasi', 'waktus.paket as paket_waktu', 'waktus.jenis as jenis_waktu')
                ->get();
            }else{
                $search = DB::table('pesanans')
                ->leftJoin('pelanggans', 'pesanans.idpelanggan', '=', 'pelanggans.id')
                ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
                ->leftJoin('services', 'pesanans.idlayanan', '=', 'services.id')
                ->leftJoin('waktus', 'pesanans.idwaktu', '=', 'waktus.id')
                ->rightJoin('pembayarans', 'pesanans.id', '=', 'pembayarans.idpesanan')
                ->where(function($query) use($request) {
                    $query;
                    $query->where(DB::raw('lower(pelanggans.nama)'), 'like', '%' . str::lower($request->q) . '%');
                    $query->orwhere(DB::raw('lower(services.nama_layanan)'), 'like', '%' . str::lower($request->q) . '%');
                    $query->orwhere('pelanggans.whatsapp', 'like', '%' . $request->q . '%');
                    $query->orwhere('pesanans.nota_transaksi', 'like', '%' . $request->q . '%');
                })
                ->where('outlets.id', $user_outlet)
                ->select('pesanans.*', 'pelanggans.nama', 'pelanggans.whatsapp', 'pelanggans.alamat', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.sosial_media', 'services.nama_layanan', 'services.harga', 'services.kategori', 'services.jenis', 'services.item', 'pembayarans.status as statusPembayaran', 'pembayarans.metode_pembayaran', 'pembayarans.subtotal', 'pembayarans.diskon', 'pembayarans.utang', 'pembayarans.tagihan', 'pembayarans.bayar', 'waktus.nama as nama_waktu', 'waktus.waktu as durasi', 'waktus.paket as paket_waktu', 'waktus.jenis as jenis_waktu')
                ->get();
            }
        }
        
        // if(Str::lower($request->search) == 'antrian'){
        //     $user_outlet = Auth::user()->outlet_id;
        //     $search = DB::table('pesanans')
        //     ->leftJoin('pelanggans', 'pesanans.idpelanggan', '=', 'pelanggans.id')
        //     ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        //     ->leftJoin('services', 'pesanans.idlayanan', '=', 'services.id')
        //     ->leftJoin('waktus', 'pesanans.idwaktu', '=', 'waktus.id')
        //     ->rightJoin('pembayarans', 'pesanans.id', '=', 'pembayarans.idpesanan')
        //     ->where(function($query) use($request) {
        //         $query;
        //         $query->where(DB::raw('lower(pelanggans.nama)'), 'like', '%' . str::lower($request->q) . '%');
        //         $query->orwhere(DB::raw('lower(services.nama_layanan)'), 'like', '%' . str::lower($request->q) . '%');
        //         $query->orwhere('pelanggans.whatsapp', 'like', '%' . $request->q . '%');
        //         $query->orwhere('pesanans.nota_transaksi', 'like', '%' . $request->q . '%');
        //     })
        //     ->where('outlets.id', $user_outlet)
        //     ->where('pesanans.status', 'ANTRIAN')
        //     ->select('pesanans.*', 'pelanggans.nama', 'pelanggans.whatsapp', 'pelanggans.alamat', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.sosial_media', 'services.nama_layanan', 'services.harga', 'services.kategori', 'services.jenis', 'services.item', 'pembayarans.status as statusPembayaran', 'pembayarans.metode_pembayaran', 'pembayarans.subtotal', 'pembayarans.diskon', 'pembayarans.utang', 'pembayarans.tagihan', 'pembayarans.bayar', 'waktus.nama as nama_waktu', 'waktus.waktu as durasi', 'waktus.paket as paket_waktu', 'waktus.jenis as jenis_waktu')
        //     ->get();
        // }
        
        if(Str::lower($request->search) == 'operasional'){
            $user_outlet = Auth::user()->outlet_id;
            $search = DB::table('operasionals')
            ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
            ->where(function($query) use($request) {
                $query;
                $query->where('operasionals.keterangan', 'like', '%' . $request->q . '%');
                $query->where(DB::raw('upper(operasionals.jenis)'), 'like', '%' . str::upper($request->jenis) . '%');
                $query->orWhere('operasionals.nominal', 'like', '%' . $request->q . '%');
            })
            // ->where('pesanans.status', 'SELESAI')
            ->where('outlets.id', $user_outlet)
            ->select('operasionals.*')
            ->get();
        }
        
        return $this->success('Success!', $search);
    }

    public function getPesananAdmin(Request $request)
    {
        // DB::enableQueryLog(); // Enable query log

        $user_outlet = Auth::user()->outlet_id;
        if($request->status){
            if($request->from || $request->from){
                $pesanan = DB::table('pesanans')
                    ->leftJoin('pelanggans', 'pesanans.idpelanggan', '=', 'pelanggans.id')
                    ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
                    ->leftJoin('services', 'pesanans.idlayanan', '=', 'services.id')
                    ->leftJoin('waktus', 'pesanans.idwaktu', '=', 'waktus.id')
                    ->rightJoin('pembayarans', 'pesanans.id', '=', 'pembayarans.idpesanan')
                    ->where(DB::raw('upper(pesanans.status)'), Str::upper($request->status))
                    ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(90)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
                    ->where(function($query) use($user_outlet) {
                        $query;
                        $query->where('outlets.id', $user_outlet);
                        $query->orWhere('outlets.parent', $user_outlet);
                    })
                    ->select('pesanans.*', 'pelanggans.nama', 'pelanggans.whatsapp', 'pelanggans.alamat', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.sosial_media', 'services.nama_layanan', 'services.harga', 'services.kategori', 'services.jenis', 'services.item', 'pembayarans.status as statusPembayaran', 'pembayarans.metode_pembayaran', 'pembayarans.subtotal', 'pembayarans.diskon', 'pembayarans.utang', 'pembayarans.tagihan', 'pembayarans.bayar', 'waktus.nama as nama_waktu', 'waktus.waktu as durasi', 'waktus.paket as paket_waktu', 'waktus.jenis as jenis_waktu')
                    ->orderBy('created_at', 'DESC')
                    ->get();
            }else{
                $pesanan = DB::table('pesanans')
                    ->leftJoin('pelanggans', 'pesanans.idpelanggan', '=', 'pelanggans.id')
                    ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
                    ->leftJoin('services', 'pesanans.idlayanan', '=', 'services.id')
                    ->leftJoin('waktus', 'pesanans.idwaktu', '=', 'waktus.id')
                    ->rightJoin('pembayarans', 'pesanans.id', '=', 'pembayarans.idpesanan')
                    ->where(DB::raw('upper(pesanans.status)'), Str::upper($request->status))
                    ->where(function($query) use($user_outlet) {
                        $query;
                        $query->where('outlets.id', $user_outlet);
                        $query->orWhere('outlets.parent', $user_outlet);
                    })
                    ->select('pesanans.*', 'pelanggans.nama', 'pelanggans.whatsapp', 'pelanggans.alamat', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.sosial_media', 'services.nama_layanan', 'services.harga', 'services.kategori', 'services.jenis', 'services.item', 'pembayarans.status as statusPembayaran', 'pembayarans.metode_pembayaran', 'pembayarans.subtotal', 'pembayarans.diskon', 'pembayarans.utang', 'pembayarans.tagihan', 'pembayarans.bayar', 'waktus.nama as nama_waktu', 'waktus.waktu as durasi', 'waktus.paket as paket_waktu', 'waktus.jenis as jenis_waktu')
                    ->orderBy('created_at', 'DESC')
                    ->get();
            }
        }else{
            if ($request->from || $reqsuest->to) {
                $pesanan = DB::table('pesanans')
                    ->leftJoin('pelanggans', 'pesanans.idpelanggan', '=', 'pelanggans.id')
                    ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
                    ->leftJoin('services', 'pesanans.idlayanan', '=', 'services.id')
                    ->leftJoin('waktus', 'pesanans.idwaktu', '=', 'waktus.id')
                    ->rightJoin('pembayarans', 'pesanans.id', '=', 'pembayarans.idpesanan')
                    ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
                    ->where('outlets.id', $user_outlet)
                    ->orWhere('outlets.parent', $user_outlet)
                    ->select('pesanans.*', 'pelanggans.nama', 'pelanggans.whatsapp', 'pelanggans.alamat', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.sosial_media', 'services.nama_layanan', 'services.harga', 'services.kategori', 'services.jenis', 'services.item', 'pembayarans.status as statusPembayaran', 'pembayarans.metode_pembayaran', 'pembayarans.subtotal', 'pembayarans.diskon', 'pembayarans.utang', 'pembayarans.tagihan', 'pembayarans.bayar', 'waktus.nama as nama_waktu', 'waktus.waktu as durasi', 'waktus.paket as paket_waktu', 'waktus.jenis as jenis_waktu')
                    ->orderBy('created_at', 'DESC')
                    ->get();
            }else{
                $pesanan = DB::table('pesanans')
                    ->leftJoin('pelanggans', 'pesanans.idpelanggan', '=', 'pelanggans.id')
                    ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
                    ->leftJoin('services', 'pesanans.idlayanan', '=', 'services.id')
                    ->leftJoin('waktus', 'pesanans.idwaktu', '=', 'waktus.id')
                    ->rightJoin('pembayarans', 'pesanans.id', '=', 'pembayarans.idpesanan')
                    ->where('outlets.id', $user_outlet)
                    ->orWhere('outlets.parent', $user_outlet)
                    ->select('pesanans.*', 'pelanggans.nama', 'pelanggans.whatsapp', 'pelanggans.alamat', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.sosial_media', 'services.nama_layanan', 'services.harga', 'services.kategori', 'services.jenis', 'services.item', 'pembayarans.status as statusPembayaran', 'pembayarans.metode_pembayaran', 'pembayarans.subtotal', 'pembayarans.diskon', 'pembayarans.utang', 'pembayarans.tagihan', 'pembayarans.bayar', 'waktus.nama as nama_waktu', 'waktus.waktu as durasi', 'waktus.paket as paket_waktu', 'waktus.jenis as jenis_waktu')
                    ->orderBy('created_at', 'DESC')
                    ->get();

            }

        }
        // dd(DB::getQueryLog()); // Show results of log

        
        if($pesanan){
            return $this->success('Success!', $pesanan);
        }else{
            return $this->error('Failed!', [ 'message' => 'Data Not Found'], 404);
        }
    }
    public function report(Request $request)
    {
        $user_outlet = Auth::user()->outlet_id;
        // DB::enableQueryLog(); // Enable query log
        
        $kiloan = DB::table('pesanans')
            ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
            ->leftJoin('services', 'pesanans.idlayanan', '=', 'services.id')
            ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
            ->where('services.jenis', 'kiloan')
            ->where('outlets.id', $user_outlet)
            ->orWhere('outlets.parent', $user_outlet)
            ->select(DB::raw('sum(pesanans.jumlah)'))
            ->get();
        
        // $satuan = DB::table('pesanans')
        //     ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        //     ->leftJoin('services', 'pesanans.idlayanan', '=', 'services.id')
        //     ->where('services.jenis', 'satuan')
        //     ->where('outlets.id', $user_outlet)
        //     ->orWhere('outlets.parent', $user_outlet)
        //     ->select(DB::raw('sum(pesanans.jumlah)'))
        //     ->get();
        // dd(DB::getQueryLog()); // Show results of log


        return $this->success('Success!', $kiloan);
    }
   
    public function reportOperasional(Request $request)
    {
        $user_outlet = Auth::user()->outlet_id;
        $from = $request->from;
        $to = $request->to;

        $pendapatan = DB::table('operasionals')
            ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
            ->whereBetween('operasionals.created_at', [$from, $to])
            // ->where('operasionals.jenis', 'PEMASUKAN')
            ->where('outlets.id', $user_outlet)
            ->orWhere('outlets.parent', $user_outlet)
            ->get();

        $pendapatanharian = DB::select('
        with recursive Date_Ranges AS (
            select CURRENT_DATE - INTERVAL 30 day as Date
           union all
           select Date + interval 1 day
           from Date_Ranges
           where Date < CURRENT_DATE), 
           data_pemasukan AS (
           SELECT case when sum(o.nominal) IS NULL then 0 else sum(o.nominal) end as data_pemasukan, DATE_FORMAT(o.created_at, \'%Y-%m-%d\') as date from operasionals o LEFT JOIN outlets ou on o.outletid = ou.id where o.jenis = \'PEMASUKAN\' and ou.id = \''. $user_outlet . '\' or ou.parent = \''. $user_outlet . '\' GROUP BY DATE_FORMAT(o.created_at, \'%Y-%m-%d\')
           ),
           data_pengeluaran AS (
           SELECT case when sum(o.nominal) IS NULL then 0 else sum(o.nominal) end as data_pengeluaran, DATE_FORMAT(o.created_at, \'%Y-%m-%d\') as date from operasionals o LEFT JOIN outlets ou on o.outletid = ou.id where o.jenis = \'PENGELUARAN\' and ou.id = \''. $user_outlet . '\' or ou.parent = \''. $user_outlet . '\' GROUP BY DATE_FORMAT(o.created_at, \'%Y-%m-%d\')
           )
           
           SELECT dr.Date, (case when (SELECT dps.data_pemasukan from data_pemasukan dps where dps.date = dr.Date) IS NULL then 0 else (SELECT dps.data_pemasukan from data_pemasukan dps where dps.date = dr.Date) end) as data_pemasukan, (case when (SELECT dpn.data_pengeluaran from data_pengeluaran dpn where dpn.date = dr.Date) IS NULL then 0 else (SELECT dpn.data_pengeluaran from data_pengeluaran dpn where dpn.date = dr.Date) end) as data_pengeluaran FROM Date_Ranges dr GROUP BY dr.Date ORDER BY dr.Date
        ');

        return $this->success('Success!', ["report" => $pendapatan, "harian" => $pendapatanharian]);
    }
    
    public function totalPemasukanAdmin(Request $request)
    {
        $user_outlet = Auth::user()->outlet_id;

        $totalpendapatan = DB::table('operasionals')
            ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
            ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
            ->where('operasionals.jenis', 'PEMASUKAN')
            ->where('outlets.id', $user_outlet)
            ->orWhere('outlets.parent', $user_outlet)
            ->get(DB::raw('sum(operasionals.nominal) as "pendapatan"'));
        
        $totalpengeluaran = DB::table('operasionals')
            ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
            ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
            ->where('operasionals.jenis', 'PENGELUARAN')
            ->where('outlets.id', $user_outlet)
            ->orWhere('outlets.parent', $user_outlet)
            ->get(DB::raw('sum(operasionals.nominal) as "pengeluaran"'));
        
        $totalpemasukan = $totalpendapatan[0]->pendapatan - $totalpengeluaran[0]->pengeluaran;

        return $this->success('Success!', ['totalPendapatan' => $totalpemasukan]);
    }
    
    public function totalPemasukanKasir()
    {
        $user_outlet = Auth::user()->outlet_id;

        $totalpendapatan = DB::table('operasionals')
            ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
            ->where('operasionals.jenis', 'PEMASUKAN')
            ->where('outlets.id', $user_outlet)
            ->get(DB::raw('sum(operasionals.nominal) as "pendapatan"'));
        
        $totalpengeluaran = DB::table('operasionals')
            ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
            ->where('operasionals.jenis', 'PENGELUARAN')
            ->where('outlets.id', $user_outlet)
            ->get(DB::raw('sum(operasionals.nominal) as "pengeluaran"'));
        
        $totalpemasukan = $totalpendapatan[0]->pendapatan - $totalpengeluaran[0]->pengeluaran;

        return $this->success('Success!', ['totalPendapatan' => $totalpemasukan]);
    }
}
