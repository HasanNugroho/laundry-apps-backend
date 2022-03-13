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
    public function countpelangganOwner(Request $request)
    {
        $user_outlet = Auth::user()->outlet_id;
        if ($request->outlet){
            if($request->outlet != $user_outlet){
                $currentmouth = DB::table('pelanggans')
                ->leftJoin('outlets', 'pelanggans.outletid', '=', 'outlets.id')
                ->whereMonth('pelanggans.created_at', date('m'))
                ->whereYear('pelanggans.created_at', date('Y'))
                ->where('outlets.id', $request->outlet)
                ->where('outlets.parent', $user_outlet)
                ->count();
            }else{
                $currentmouth = DB::table('pelanggans')
                ->leftJoin('outlets', 'pelanggans.outletid', '=', 'outlets.id')
                ->whereMonth('pelanggans.created_at', date('m'))
                ->whereYear('pelanggans.created_at', date('Y'))
                ->where('outlets.id', $request->outlet)
                ->count();
            }
        }else{
            $currentmouth = DB::table('pelanggans')
            ->leftJoin('outlets', 'pelanggans.outletid', '=', 'outlets.id')
            ->whereMonth('pelanggans.created_at', date('m'))
            ->whereYear('pelanggans.created_at', date('Y'))
            ->where('outlets.id', $user_outlet)
            ->orWhere('outlets.parent', $user_outlet)
            ->count();
        }
        
        // $currentmouth = Pelanggan::whereMonth('created_at', date('m'))
        // ->whereYear('created_at', date('Y'))
        // ->count();
        
        $dt     = Carbon::now();
        $past   = $dt->subMonth();
        if ($request->outlet){
            if($request->outlet != $user_outlet){
                $lastmouth = DB::table('pelanggans')
                ->leftJoin('outlets', 'pelanggans.outletid', '=', 'outlets.id')
                ->whereMonth('pelanggans.created_at', '<=' , $past->format('m'))
                ->whereYear('pelanggans.created_at', date('Y'))
                ->where('outlets.id', $request->outlet)
                ->where('outlets.parent', $user_outlet)
                ->count();
            }else{
                $lastmouth = DB::table('pelanggans')
                ->leftJoin('outlets', 'pelanggans.outletid', '=', 'outlets.id')
                ->whereMonth('pelanggans.created_at', '<=' , $past->format('m'))
                ->whereYear('pelanggans.created_at', date('Y'))
                ->where('outlets.id', $request->outlet)
                ->count();
            }
        }else{
            $lastmouth = DB::table('pelanggans')
            ->leftJoin('outlets', 'pelanggans.outletid', '=', 'outlets.id')
            ->whereMonth('pelanggans.created_at', '<=' , $past->format('m'))
            ->whereYear('pelanggans.created_at', date('Y'))
            ->where('outlets.id', $user_outlet)
            ->orWhere('outlets.parent', $user_outlet)
            ->count();
        }
        
        // $lastmouth = Pelanggan::whereMonth('created_at', '>', $past->format('m'))
        // ->whereYear('created_at', date('Y'))
        // ->count();
        
        if ($request->outlet){
            if($request->outlet != $user_outlet){
                $all = DB::table('pelanggans')
                ->leftJoin('outlets', 'pelanggans.outletid', '=', 'outlets.id')
                ->where('outlets.id', $request->outlet)
                ->where('outlets.parent', $user_outlet)
                ->count();
            }else{
                $all = DB::table('pelanggans')
                ->leftJoin('outlets', 'pelanggans.outletid', '=', 'outlets.id')
                ->where('outlets.id', $request->outlet)
                ->count();
            }
        }else{
            $all = DB::table('pelanggans')
            ->leftJoin('outlets', 'pelanggans.outletid', '=', 'outlets.id')
            ->where('outlets.id', $user_outlet)
            ->orWhere('outlets.parent', $user_outlet)
            ->count();
        }

        return $this->success('Success!', ['curentMouth' => $currentmouth, 'lastMouth' => $lastmouth, 'total' => $all]);
    }

    public function nominalutangOwner(Request $request)
    {
        $user_outlet = Auth::user()->outlet_id;
        $outletQuery = '';
        if($request->outlet){
            if($request->outlet != $user_outlet){
                $outletQuery = ' and outlets.id = \'' . $request->outlet .'\' ';
            }else{
                $outletQuery = ' and (outlets.id = \'' . $user_outlet . '\' or outlets.parent = \'' . $user_outlet . '\') ';
            }
        }else{
            $outletQuery = ' and (outlets.id = \'' . $user_outlet . '\' or outlets.parent = \'' . $user_outlet . '\') ';
        }
        
        // query date 
        $queryDate = '';
        if ($request->today){
            $queryDate = ' DATE_FORMAT(pesanans.updated_at, \'%Y-%m-%d\') = \'' . Carbon::today() . '\' and ';
        }else{
            $queryDate = ' DATE_FORMAT(pesanans.updated_at, \'%Y-%m-%d\') between \'' . ($request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString()) . '\' and \'' . ($request->to ? $request->to : Carbon::now()->addday(1)->toDateString()) . '\' and ';
        }
        $utang = DB::select('select sum(pembayarans.tagihan) as utang from pesanans inner join outlets on pesanans.outletid = outlets.id inner join pembayarans on pesanans.id = pembayarans.idpesanan where ' . $queryDate . ' pesanans.status != \'DIBATALKAN\' and (pembayarans.status = \'BELUM BAYAR\' or pembayarans.status = \'UTANG\') ' . $outletQuery);

        // if ($request->outlet){
        //     if($request->outlet != $user_outlet){
        //         $utang = DB::table('pembayarans')
        //         ->where(DB::raw('upper(pembayarans.status)'), 'UTANG')
        //         ->orWhere(DB::raw('upper(pembayarans.status)'), 'BELUM BAYAR')
        //         ->whereBetween('pembayarans.updated_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
        //         ->rightJoin('pesanans', 'pesanans.id', '=', 'pembayarans.idpesanan')
        //         ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        //         ->where('pesanans.status', '!=', 'DIBATALKAN')
        //         ->where('outlets.id', $request->outlet)
        //         ->where('outlets.parent', $user_outlet)
        //         ->get(DB::raw('sum(pembayarans.tagihan) as utang'));
        //     }else{
        //         $utang = DB::table('pembayarans')->where(DB::raw('upper(pembayarans.status)'), 'UTANG')
        //         ->orWhere(DB::raw('upper(pembayarans.status)'), 'BELUM BAYAR')
        //         ->whereBetween('pembayarans.updated_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
        //         ->rightJoin('pesanans', 'pesanans.id', '=', 'pembayarans.idpesanan')
        //         ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        //         ->where('pesanans.status', '!=', 'DIBATALKAN')
        //         ->where('outlets.id', $request->outlet)
        //         ->get(DB::raw('sum(pembayarans.tagihan) as utang'));
        //     }
        // }else{
        //     $utang = DB::table('pembayarans')->where(DB::raw('upper(pembayarans.status)'), 'UTANG')
        //     ->orWhere(DB::raw('upper(pembayarans.status)'), 'BELUM BAYAR')
        //     ->whereBetween('pembayarans.updated_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
        //     ->rightJoin('pesanans', 'pesanans.id', '=', 'pembayarans.idpesanan')
        //     ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        //     ->where('pesanans.status', '!=', 'DIBATALKAN')
        //     ->where('outlets.id', $user_outlet)
        //     ->orWhere('outlets.parent', $user_outlet)
        //     ->get(DB::raw('sum(pembayarans.tagihan) as utang'));
        // }

        return $this->success('Success!', $utang[0]->utang ? $utang[0]->utang : 0);
    }
    
    public function nominalutangKasir()
    {
        $user_outlet = Auth::user()->outlet_id;
        $utang = DB::table('pembayarans')->where(DB::raw('upper(pembayarans.status)'), 'UTANG')
        ->orWhere(DB::raw('upper(pembayarans.status)'), 'BELUM BAYAR')
        ->rightJoin('pesanans', 'pesanans.id', '=', 'pembayarans.idpesanan')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->where('outlets.id', $user_outlet)
        ->get(DB::raw('sum(pembayarans.tagihan) as utang'));

        return $this->success('Success!', $utang[0]->utang ? $utang[0]->utang : 0);
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
        if ($request->outlet){
            if($request->outlet != $user_outlet){
                $query = '(ou.id = \''. $request->outlet . '\' and ou.parent = \''. $user_outlet . '\')';
            }else{
                $query = '(ou.id = \''. $request->outlet . '\')';
            }
        }else{
            $query = '(ou.id = \''. $user_outlet . '\' or ou.parent = \''. $user_outlet . '\')';
        }

        if($request->from != FALSE || $request->to != FALSE){
            $pendapatan = DB::select('
            with recursive Date_Ranges AS (
                select \''. $request->from . '\' as Date
                union all
                select Date + interval 1 day
                from Date_Ranges
                where Date < \''. $request->to . '\'), 
                data_pemasukan AS (
                SELECT case when sum(pmb.tagihan) IS NULL then 0 else sum(pmb.tagihan) end as data_pemasukan, DATE_FORMAT(p.updated_at, \'%Y-%m-%d\') as date from pesanans p LEFT JOIN outlets ou on p.outletid = ou.id INNER JOIN pembayarans pmb on p.id = pmb.idpesanan where (p.status != \'DIBATALKAN\' and pmb.status != \'BELUM BAYAR\' and pmb.status != \'UTANG\') AND ' . $query . ' GROUP BY DATE_FORMAT(p.updated_at, \'%Y-%m-%d\')
                )
                
                SELECT dr.Date as date, (case when (SELECT dps.data_pemasukan from data_pemasukan dps where dps.date = dr.Date) IS NULL then 0 else (SELECT dps.data_pemasukan from data_pemasukan dps where dps.date = dr.Date) end) as omset FROM Date_Ranges dr GROUP BY dr.Date ORDER BY dr.Date asc
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
                SELECT case when sum(pmb.tagihan) IS NULL then 0 else sum(pmb.tagihan) end as data_pemasukan, DATE_FORMAT(p.updated_at, \'%Y-%m-%d\') as date from pesanans p LEFT JOIN outlets ou on p.outletid = ou.id INNER JOIN pembayarans pmb on p.id = pmb.idpesanan where (p.status != \'DIBATALKAN\' and pmb.status != \'BELUM BAYAR\' and pmb.status != \'UTANG\') AND ' . $query . ' GROUP BY DATE_FORMAT(p.updated_at, \'%Y-%m-%d\')
                )
                
                SELECT dr.Date as date, (case when (SELECT dps.data_pemasukan from data_pemasukan dps where dps.date = dr.Date) IS NULL then 0 else (SELECT dps.data_pemasukan from data_pemasukan dps where dps.date = dr.Date) end) as omset FROM Date_Ranges dr GROUP BY dr.Date ORDER BY dr.Date asc
            ');
        }
        
        // dd(DB::getQueryLog()); // Show results of log
        // if ($request->outlet){
        //     if($request->outlet != $user_outlet){
        //         $totalpendapatan = DB::table('operasionals')
        //             ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
        //             ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addday(1)->toDateString()])
        //             ->where('operasionals.jenis', 'PEMASUKAN')
        //             ->where('outlets.id', $request->outlet)
        //             ->where('outlets.parent', $user_outlet)
        //             ->get(DB::raw('sum(operasionals.nominal) as "pendapatan"'));
        //     }else{
        //         $totalpendapatan = DB::table('operasionals')
        //             ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
        //             ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addday(1)->toDateString()])
        //             ->where('operasionals.jenis', 'PEMASUKAN')
        //             ->where('outlets.id', $request->outlet)
        //             ->get(DB::raw('sum(operasionals.nominal) as "pendapatan"'));
        //     }
        // }else{
        //     $totalpendapatan = DB::table('operasionals')
        //         ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
        //         ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addday(1)->toDateString()])
        //         ->where('operasionals.jenis', 'PEMASUKAN')
        //         ->where('outlets.id', $user_outlet)
        //         ->orWhere('outlets.parent', $user_outlet)
        //         ->get(DB::raw('sum(operasionals.nominal) as "pendapatan"'));
        // }

        if ($request->outlet){
            if($request->outlet != $user_outlet){
                $totalpendapatan = DB::table('operasionals')
                    ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
                    ->leftJoin('pesanans', 'operasionals.idpesanan', '=', 'pesanans.id')
                    ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addday(1)->toDateString()])
                    ->where('pesanans.status', 'SELESAI')
                    ->where('operasionals.jenis', 'PEMASUKAN')
                    ->where('outlets.id', $request->outlet)
                    ->where('outlets.parent', $user_outlet)
                    ->get(DB::raw('sum(operasionals.nominal) as "pendapatan"'));
            }else{
                $totalpendapatan = DB::table('operasionals')
                    ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
                    ->leftJoin('pesanans', 'operasionals.idpesanan', '=', 'pesanans.id')
                    ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addday(1)->toDateString()])
                    ->where('pesanans.status', 'SELESAI')
                    ->where('operasionals.jenis', 'PEMASUKAN')
                    ->where('outlets.id', $request->outlet)
                    ->get(DB::raw('sum(operasionals.nominal) as "pendapatan"'));
            }
        }else{
            $totalpendapatan = DB::table('operasionals')
                ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
                ->leftJoin('pesanans', 'operasionals.idpesanan', '=', 'pesanans.id')
                ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addday(1)->toDateString()])
                ->where('pesanans.status', 'SELESAI')
                ->where('operasionals.jenis', 'PEMASUKAN')
                ->where('outlets.id', $user_outlet)
                ->orWhere('outlets.parent', $user_outlet)
                ->get(DB::raw('sum(operasionals.nominal) as "pendapatan"'));
        }

        if ($request->outlet){
            if($request->outlet != $user_outlet){
                $totalpengeluaran = DB::table('operasionals')
                    ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
                    ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addday(1)->toDateString()])
                    ->where('operasionals.jenis', 'PENGELUARAN')
                    ->where('outlets.id', $request->outlet)
                    ->where('outlets.parent', $user_outlet)
                    ->get(DB::raw('sum(operasionals.nominal) as "pengeluaran"'));
            }else{
                $totalpengeluaran = DB::table('operasionals')
                    ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
                    ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addday(1)->toDateString()])
                    ->where('operasionals.jenis', 'PENGELUARAN')
                    ->where('outlets.id', $request->outlet)
                    ->get(DB::raw('sum(operasionals.nominal) as "pengeluaran"'));
            }
        }else{
            $totalpengeluaran = DB::table('operasionals')
                ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
                ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addday(1)->toDateString()])
                ->where('operasionals.jenis', 'PENGELUARAN')
                ->where('outlets.id', $user_outlet)
                ->orWhere('outlets.parent', $user_outlet)
                ->get(DB::raw('sum(operasionals.nominal) as "pengeluaran"'));
        }

        $totalpemasukan = $totalpendapatan[0]->pendapatan - $totalpengeluaran[0]->pengeluaran;

        return $this->success('Success!', ['omsetHarian' => $pendapatan, 'totalPemasukan' => $totalpemasukan]);
    }
    
    public function pemasukanOwner(Request $request)
    {
        $user_outlet = Auth::user()->outlet_id;

        // query outlet
        $outletQuery = '';
        if($request->outlet){
            if($request->outlet != $user_outlet){
                $outletQuery = ' and outlets.id = \'' . $request->outlet .'\' ';
            }else{
                $outletQuery = ' and (outlets.id = \'' . $user_outlet . '\' or outlets.parent = \'' . $user_outlet . '\') ';
            }
        }else{
            $outletQuery = ' and (outlets.id = \'' . $user_outlet . '\' or outlets.parent = \'' . $user_outlet . '\') ';
        }
        
        // query date 
        $queryDate = '';
        if ($request->today){
            $queryDate = ' DATE_FORMAT(pesanans.updated_at, \'%Y-%m-%d\') = \'' . Carbon::today() . '\' and ';
        }else{
            $queryDate = ' DATE_FORMAT(pesanans.updated_at, \'%Y-%m-%d\') between \'' . ($request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString()) . '\' and \'' . ($request->to ? $request->to : Carbon::now()->addday(1)->toDateString()) . '\' and ';
        }
        $pemasukan = DB::select('select sum(pembayarans.tagihan) as pemasukan from pesanans inner join outlets on pesanans.outletid = outlets.id inner join pembayarans on pesanans.id = pembayarans.idpesanan where ' . $queryDate . ' pesanans.status != \'DIBATALKAN\' and (pembayarans.status != \'BELUM BAYAR\' and pembayarans.status != \'UTANG\') ' . $outletQuery);
        // if ($request->today) {
        //     if ($request->outlet){
        //         if($request->outlet != $user_outlet){
        //             $pemasukan = DB::table('pesanans')
        //                 ->join('outlets', 'pesanans.outletid', '=', 'outlets.id')
        //                 ->join('pembayarans', 'pesanans.id', '=', 'pembayarans.idpesanan')
        //                 ->whereDate('pesanans.updated_at', Carbon::today())
        //                 ->where(function($query) use($request, $user_outlet) {
        //                     $query;
        //                     $query->where('pesanans.status', '!=', 'DIBATALKAN');
        //                     $query->where('pembayarans.status', '!=', 'BELUM BAYAR');
        //                     $query->where('pembayarans.status', '!=', 'UTANG');
        //                 })
        //                 ->where('pesanans.status', '!=', 'DIBATALKAN')
        //                 ->where('pembayarans.status', '!=', 'BELUM BAYAR')
        //                 ->where('pembayarans.status', '!=', 'UTANG')
        //                 ->where('outlets.id', $request->outlet)
        //                 ->where('outlets.parent', $user_outlet)
        //                 ->get(DB::raw('sum(pembayarans.tagihan) as "pemasukan"'));
        //         }else{
        //             $pemasukan = DB::table('pesanans')
        //                 ->join('outlets', 'pesanans.outletid', '=', 'outlets.id')
        //                 ->join('pembayarans', 'pesanans.id', '=', 'pembayarans.idpesanan')
        //                 ->whereDate('pesanans.updated_at', Carbon::today())
        //                 ->where('pesanans.status', '!=', 'DIBATALKAN')
        //                 ->where('pembayarans.status', '!=', 'BELUM BAYAR')
        //                 ->where('pembayarans.status', '!=', 'UTANG')
        //                 ->where('outlets.id', $request->outlet)
        //                 ->get(DB::raw('sum(pembayarans.tagihan) as "pemasukan"'));
        //         }
        //     }else{
        //         $pemasukan = DB::table('pesanans')
        //             ->join('outlets', 'pesanans.outletid', '=', 'outlets.id')
        //             ->join('pembayarans', 'pesanans.id', '=', 'pembayarans.idpesanan')
        //             ->whereDate('pesanans.updated_at', Carbon::today())
        //             ->where('pesanans.status', '!=', 'DIBATALKAN')
        //             ->where('pembayarans.status', '!=', 'BELUM BAYAR')
        //             ->where('pembayarans.status', '!=', 'UTANG')
        //             ->where('outlets.id', $user_outlet)
        //             ->orWhere('outlets.parent', $user_outlet)
        //             ->get(DB::raw('sum(pembayarans.tagihan) as "pemasukan"'));
        //     }
        // }else{
        //     if ($request->outlet){
        //         if($request->outlet != $user_outlet){
        //             $pemasukan = DB::table('pesanans')
        //                 ->join('outlets', 'pesanans.outletid', '=', 'outlets.id')
        //                 ->join('pembayarans', 'pesanans.id', '=', 'pembayarans.idpesanan')
        //                 ->whereBetween('pesanans.updated_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addday(1)->toDateString()])
        //                 ->where('pesanans.status', '!=', 'DIBATALKAN')
        //                 ->where('pembayarans.status', '!=', 'BELUM BAYAR')
        //                 ->where('pembayarans.status', '!=', 'UTANG')
        //                 ->where('outlets.id', $request->outlet)
        //                 ->where('outlets.parent', $user_outlet)
        //                 ->get(DB::raw('sum(pembayarans.tagihan) as "pemasukan"'));
        //         }else{
        //             $pemasukan = DB::table('pesanans')
        //                 ->join('outlets', 'pesanans.outletid', '=', 'outlets.id')
        //                 ->join('pembayarans', 'pesanans.id', '=', 'pembayarans.idpesanan')
        //                 ->whereBetween('pesanans.updated_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addday(1)->toDateString()])
        //                 ->where('pesanans.status', '!=', 'DIBATALKAN')
        //                 ->where('pembayarans.status', '!=', 'BELUM BAYAR')
        //                 ->where('pembayarans.status', '!=', 'UTANG')
        //                 ->where('outlets.id', $request->outlet)
        //                 ->get(DB::raw('sum(pembayarans.tagihan) as "pemasukan"'));
        //         }
        //     }else{
        //         $pemasukan = DB::table('pesanans')
        //             ->join('outlets', 'pesanans.outletid', '=', 'outlets.id')
        //             ->join('pembayarans', 'pesanans.id', '=', 'pembayarans.idpesanan')
        //             ->whereBetween('pesanans.updated_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addday(1)->toDateString()])
        //             ->where('pesanans.status', '!=', 'DIBATALKAN')
        //             ->where('pembayarans.status', '!=', 'BELUM BAYAR')
        //             ->where('pembayarans.status', '!=', 'UTANG')
        //             ->where('outlets.id', $user_outlet)
        //             ->orWhere('outlets.parent', $user_outlet)
        //             ->get(DB::raw('sum(pembayarans.tagihan) as "pemasukan"'));
        //     }
        // }

        return $this->success('Success!', ['pemasukan' => $pemasukan[0]->pemasukan]);
    }
    
    public function pemasukanKasir(Request $request)
    {
        $user_outlet = Auth::user()->outlet_id;
        // if ($request->today) {
        $pemasukan = DB::table('pesanans')
            ->join('outlets', 'pesanans.outletid', '=', 'outlets.id')
            ->join('pembayarans', 'pesanans.id', '=', 'pembayarans.idpesanan')
            ->whereDate('pesanans.updated_at', Carbon::today())
            ->where('pesanans.status', '!=', 'DIBATALKAN')
            ->where('pembayarans.status', '!=', 'BELUM BAYAR')
            ->where('pembayarans.status', '!=', 'UTANG')
            ->where('outlets.id', $user_outlet)
            ->get(DB::raw('sum(pembayarans.bayar) as "pemasukan"'));
        // }else{
        //     $pemasukan = DB::table('pesanans')
        //         ->join('outlets', 'pesanans.outletid', '=', 'outlets.id')
        //         ->join('pembayarans', 'pesanans.id', '=', 'pembayarans.idpesanan')
        //         ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addday(1)->toDateString()])
        //         ->where('pesanans.status', '!=', 'DIBATALKAN')
        //         ->where('outlets.id', $user_outlet)
        //         ->get(DB::raw('sum(pembayarans.bayar) as "pemasukan"'));
        // }

        return $this->success('Success!', ['pemasukan' => $pemasukan[0]->pemasukan]);
    }
    
    public function pendapatanKasir()
    {
        $user_outlet = Auth::user()->outlet_id;

        $pendapatan = DB::table('operasionals')
            ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
            ->leftJoin('pesanans', 'operasionals.idpesanan', '=', 'pesanans.id')
            ->where('operasionals.created_at', '>=', Carbon::now()->subMonth())
            ->where('pesanans.status', 'SELESAI')
            ->where('operasionals.jenis', 'PEMASUKAN')
            ->where('outlets.id', $user_outlet)
            ->groupBy('date', 'operasionals.outletid')
            ->orderBy('date', 'DESC')
            ->get(array(
                DB::raw('Date(operasionals.created_at) as date'),
                DB::raw('sum(operasionals.nominal) as "omset"'),
                // DB::raw('operasionals.outletid as outletid')
            ));

        $totalpendapatan = DB::table('operasionals')
            ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
            ->leftJoin('pesanans', 'operasionals.idpesanan', '=', 'pesanans.id')
            ->where('pesanans.status', 'SELESAI')
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
        if ($request->outlet){
            if($request->outlet != $user_outlet){
                $query = 'ou.id = \''. $request->outlet . '\' and ou.parent = \''. $user_outlet . '\'';
            }else{
                $query = 'ou.id = \''. $request->outlet;
            }
        }else{
            $query = 'ou.id = \''. $user_outlet . '\' or ou.parent = \''. $user_outlet . '\'';
        }

        if($request->from != FALSE || $request->to != FALSE){
            $pendapatan = DB::select('
            with recursive Date_Ranges AS (
                select \''. $request->from . '\' as Date
                union all
                select Date + interval 1 day
                from Date_Ranges
                where Date < \''. $request->to . '\'), 
                data_pengeluaran AS (
                SELECT case when sum(o.nominal) IS NULL then 0 else sum(o.nominal) end as data_pengeluaran, DATE_FORMAT(o.created_at, \'%Y-%m-%d\') as date from operasionals o LEFT JOIN outlets ou on o.outletid = ou.id where o.jenis = \'PENGELUARAN\' and ' . $query . ' GROUP BY DATE_FORMAT(o.created_at, \'%Y-%m-%d\')
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
                SELECT case when sum(o.nominal) IS NULL then 0 else sum(o.nominal) end as data_pengeluaran, DATE_FORMAT(o.created_at, \'%Y-%m-%d\') as date from operasionals o LEFT JOIN outlets ou on o.outletid = ou.id where o.jenis = \'PENGELUARAN\' and ' . $query . ' GROUP BY DATE_FORMAT(o.created_at, \'%Y-%m-%d\')
                )
                
                SELECT dr.Date as date, (case when (SELECT dps.data_pengeluaran from data_pengeluaran dps where dps.date = dr.Date) IS NULL then 0 else (SELECT dps.data_pengeluaran from data_pengeluaran dps where dps.date = dr.Date) end) as pengeluaran FROM Date_Ranges dr GROUP BY dr.Date ORDER BY dr.Date desc
            ');
        }
        
        if ($request->outlet){
            if($request->outlet != $user_outlet){
                $totalpengeluaran = DB::table('operasionals')
                    ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
                    ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addday(1)->toDateString()])
                    ->where('operasionals.jenis', 'PENGELUARAN')
                    ->where('outlets.id', $request->outlet)
                    ->where('outlets.parent', $user_outlet)
                    ->get(DB::raw('sum(operasionals.nominal) as "pengeluaran"'));
            }else{
                $totalpengeluaran = DB::table('operasionals')
                    ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
                    ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addday(1)->toDateString()])
                    ->where('operasionals.jenis', 'PENGELUARAN')
                    ->where('outlets.id', $user_outlet)
                    ->get(DB::raw('sum(operasionals.nominal) as "pengeluaran"'));
            }
        }else{
            $totalpengeluaran = DB::table('operasionals')
                ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
                ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addday(1)->toDateString()])
                ->where('operasionals.jenis', 'PENGELUARAN')
                ->where('outlets.id', $user_outlet)
                ->orWhere('outlets.parent', $user_outlet)
                ->get(DB::raw('sum(operasionals.nominal) as "pengeluaran"'));
        }


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

        if ($request->outlet) {
            if($request->outlet != $user_outlet){
                $all = DB::table('pesanans')
                ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
                ->whereBetween('pesanans.updated_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
                // ->where(DB::raw('upper(pesanans.status)'),'!=', 'DIBATALKAN')
                ->where('outlets.id', $request->outlet)
                ->where('outlets.parent', $user_outlet)
                ->count();
            }else{
                $all = DB::table('pesanans')
                ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
                ->whereBetween('pesanans.updated_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
                // ->where(DB::raw('upper(pesanans.status)'),'!=', 'DIBATALKAN')
                ->where('outlets.id', $request->outlet)
                ->count();
            }
        }else{
            $all = DB::table('pesanans')
            ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
            ->whereBetween('pesanans.updated_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
            // ->where(DB::raw('upper(pesanans.status)'),'!=', 'DIBATALKAN')
            ->where('outlets.id', $user_outlet)
            ->orWhere('outlets.parent', $user_outlet)
            ->count();
        }


        return $this->success('Success!', ['total' => $all]);
    }
    
    public function transaksiKasir()
    {
        $user_outlet = Auth::user()->outlet_id;
        $today = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->whereDate('pesanans.updated_at',Carbon::today())
        ->where(DB::raw('upper(pesanans.status)'),'!=', 'DIBATALKAN')
        ->where('outlets.id', $user_outlet)
        ->count();

        $yesterday = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->whereDate('pesanans.updated_at', Carbon::yesterday())
        ->where(DB::raw('upper(pesanans.status)'),'!=', 'DIBATALKAN')
        ->where('outlets.id', $user_outlet)
        ->count();
        
        $current_week = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->whereBetween('pesanans.updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        ->where(DB::raw('upper(pesanans.status)'),'!=', 'DIBATALKAN')
        ->where('outlets.id', $user_outlet)
        ->count();

        $thismouth = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->whereMonth('pesanans.updated_at', Carbon::now()->format('m'))
        ->whereYear('pesanans.updated_at', date('Y'))
        ->where(DB::raw('upper(pesanans.status)'),'!=', 'DIBATALKAN')
        ->where('outlets.id', $user_outlet)
        ->count();

        $lastmouth = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->whereMonth('pesanans.updated_at', Carbon::now()->subMonth()->format('m'))
        ->whereYear('pesanans.updated_at', date('Y'))
        ->where(DB::raw('upper(pesanans.status)'),'!=', 'DIBATALKAN')
        ->where('outlets.id', $user_outlet)
        ->count();

        $all = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->where(DB::raw('upper(pesanans.status)'),'!=', 'DIBATALKAN')
        ->where('outlets.id', $user_outlet)
        ->count();

        return $this->success('Success!', ['today' => $today, 'yesterday' => $yesterday, 'current_week' => $current_week, 'thismouth' => $thismouth, 'lastmouth' => $lastmouth, 'total' => $all]);
    }

    public function countTransaksiAdmin(Request $request)
    {
        $user_outlet = Auth::user()->outlet_id;
        if ($request->outlet) {
            if($request->outlet != $user_outlet){
                $selesai = DB::table('pesanans')
                ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
                ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
                ->where(DB::raw('upper(pesanans.status)'), 'SELESAI')
                ->where('outlets.id', $request->outlet)
                ->where('outlets.parent', $user_outlet)
                ->count();
            }else{
                $selesai = DB::table('pesanans')
                ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
                ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
                ->where(DB::raw('upper(pesanans.status)'), 'SELESAI')
                ->where('outlets.id', $request->outlet)
                ->count();
            }
        }else{
            $selesai = DB::table('pesanans')
            ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
            ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
            ->where(DB::raw('upper(pesanans.status)'), 'SELESAI')
            ->where('outlets.id', $user_outlet)
            ->orWhere('outlets.parent', $user_outlet)
            ->count();
        }

        if ($request->outlet) {
            if($request->outlet != $user_outlet){
                $proses = DB::table('pesanans')
                ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
                ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
                ->where(DB::raw('upper(pesanans.status)'), 'PROSES')
                ->where('outlets.id', $request->outlet)
                ->where('outlets.parent', $user_outlet)
                ->count();
            }else{
                $proses = DB::table('pesanans')
                ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
                ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
                ->where(DB::raw('upper(pesanans.status)'), 'PROSES')
                ->where('outlets.id', $request->outlet)
                ->count();
            }
        }else{
            $proses = DB::table('pesanans')
            ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
            ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
            ->where(DB::raw('upper(pesanans.status)'), 'PROSES')
            ->where('outlets.id', $user_outlet)
            ->orWhere('outlets.parent', $user_outlet)
            ->count();
        }
        
        if ($request->outlet) {
            if($request->outlet != $user_outlet){
                $antrian = DB::table('pesanans')
                ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
                ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
                ->where(DB::raw('upper(pesanans.status)'), 'ANTRIAN')
                ->where('outlets.id', $request->outlet)
                ->where('outlets.parent', $user_outlet)
                ->count();
            }else{
                $antrian = DB::table('pesanans')
                ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
                ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
                ->where(DB::raw('upper(pesanans.status)'), 'ANTRIAN')
                ->where('outlets.id', $request->outlet)
                ->count();
            }
        }else{
            $antrian = DB::table('pesanans')
            ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
            ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
            ->where(DB::raw('upper(pesanans.status)'), 'ANTRIAN')
            ->where('outlets.id', $user_outlet)
            ->orWhere('outlets.parent', $user_outlet)
            ->count();
        }

        if ($request->outlet) {
            if($request->outlet != $user_outlet){
                $dibatalkan = DB::table('pesanans')
                ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
                ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
                ->where(DB::raw('upper(pesanans.status)'), 'DIBATALKAN')
                ->where('outlets.id', $request->outlet)
                ->where('outlets.parent', $user_outlet)
                ->count();
            }else{
                $dibatalkan = DB::table('pesanans')
                ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
                ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
                ->where(DB::raw('upper(pesanans.status)'), 'DIBATALKAN')
                ->where('outlets.id', $request->outlet)
                ->count();
            }
        }else{
            $dibatalkan = DB::table('pesanans')
            ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
            ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
            ->where(DB::raw('upper(pesanans.status)'), 'DIBATALKAN')
            ->where('outlets.id', $user_outlet)
            ->orWhere('outlets.parent', $user_outlet)
            ->count();
        }

        if ($request->outlet) {
            if($request->outlet != $user_outlet){
                $all = DB::table('pesanans')
                ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
                ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
                ->where('outlets.id', $request->outlet)
                ->where('outlets.parent', $user_outlet)
                ->count();
            }else{
                $all = DB::table('pesanans')
                ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
                ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
                ->where('outlets.id', $request->outlet)
                ->count();
            }
        }else{
            $all = DB::table('pesanans')
            ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
            ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
            ->where('outlets.id', $user_outlet)
            ->orWhere('outlets.parent', $user_outlet)
            ->count();
        }

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

    public function countTransaksiOwner(Request $request)
    {
        $user_outlet = Auth::user()->outlet_id;
        if ($request->outlet){
            if ($request->outlet != $user_outlet){
                $query = 'o.parent = \'' . $user_outlet . '\' and o.id = \'' . $request->outlet . '\'';
            }else{
                $query = 'o.id = \'' . $request->outlet . '\'';
            }
        }else{
            $query = 'o.parent = \'' . $user_outlet . '\' or o.id = \'' . $user_outlet . '\'';
        }
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
            ' . $query . ' and DATE(ps.created_at) BETWEEN ? AND ? ' , [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()]);

        return $this->success('Success!', $transaksi);
    }

    public function daftarKasirOwner(Request $request)
    {
        $user_outlet = Auth::user()->outlet_id;
        if ($request->from != FALSE || $request->to != FALSE){
            if ($request->outlet){
                if ($request->outlet != $user_outlet){
                    $users = DB::table('users')
                    ->leftJoin('outlets', 'users.outlet_id', '=', 'outlets.id')
                    ->whereBetween('users.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
                    ->where('outlets.id', $request->outlet)
                    ->where('outlets.parent', $user_outlet)
                    ->select('users.uid','users.username', 'users.email', 'users.role', 'users.alamat', 'users.whatsapp', 'users.status', 'users.created_at as date_join', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.alamat')
                    ->orderBy('users.created_at', 'DESC')
                    ->get();
                }else{
                    $users = DB::table('users')
                    ->leftJoin('outlets', 'users.outlet_id', '=', 'outlets.id')
                    ->whereBetween('users.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
                    ->where('outlets.id', $request->outlet)
                    ->select('users.uid','users.username', 'users.email', 'users.role', 'users.alamat', 'users.whatsapp', 'users.status', 'users.created_at as date_join', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.alamat')
                    ->orderBy('users.created_at', 'DESC')
                    ->get();
                }
            }else{
                $users = DB::table('users')
                ->leftJoin('outlets', 'users.outlet_id', '=', 'outlets.id')
                ->whereBetween('users.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
                ->where('outlets.id', $user_outlet)
                ->orWhere('outlets.parent', $user_outlet)
                ->select('users.uid','users.username', 'users.email', 'users.role', 'users.alamat', 'users.whatsapp', 'users.status', 'users.created_at as date_join', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.alamat')
                ->orderBy('users.created_at', 'DESC')
                ->get();
            }
        }else{
            if ($request->outlet){
                if ($request->outlet != $user_outlet){
                    $users = DB::table('users')
                    ->leftJoin('outlets', 'users.outlet_id', '=', 'outlets.id')
                    ->where('outlets.id', $request->outlet)
                    ->where('outlets.parent', $user_outlet)
                    ->select('users.uid','users.username', 'users.email', 'users.role', 'users.alamat', 'users.whatsapp', 'users.status', 'users.created_at as date_join', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.alamat')
                    ->orderBy('users.created_at', 'DESC')
                    ->get();
                }else{
                    $users = DB::table('users')
                    ->leftJoin('outlets', 'users.outlet_id', '=', 'outlets.id')
                    ->where('outlets.id', $request->outlet)
                    ->select('users.uid','users.username', 'users.email', 'users.role', 'users.alamat', 'users.whatsapp', 'users.status', 'users.created_at as date_join', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.alamat')
                    ->orderBy('users.created_at', 'DESC')
                    ->get();
                }
            }else{
                $users = DB::table('users')
                ->leftJoin('outlets', 'users.outlet_id', '=', 'outlets.id')
                ->where('outlets.id', $request->outlet)
                ->orWhere('outlets.parent', $user_outlet)
                ->select('users.uid','users.username', 'users.email', 'users.role', 'users.alamat', 'users.whatsapp', 'users.status', 'users.created_at as date_join', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.alamat')
                ->orderBy('users.created_at', 'DESC')
                ->get();
            }
        }

        return $this->success('Success!', $users);
    }
    
    public function operasionalOwner(Request $request)
    {
        $user_outlet = Auth::user()->outlet_id;
        // DB::enableQueryLog(); // Enable query log

        // if ($request->outlet) {
        //     if ($request->outlet != $user_outlet) {
        //         $operasional = DB::table('operasionals')
        //         ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
        //         ->leftJoin('pesanans', 'operasionals.idpesanan', '=', 'pesanans.id')
        //         ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
        //         ->where('pesanans.status', 'SELESAI')
        //         ->orwhere('operasionals.jenis', 'PENGELUARAN')
        //         ->where('outlets.id', $request->outlet)
        //         ->where('outlets.parent', $user_outlet)
        //         ->select('operasionals.*', 'outlets.nama_outlet')
        //         ->get();
        //     }else{
        //         $operasional = DB::table('operasionals')
        //         ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
        //         ->leftJoin('pesanans', 'operasionals.idpesanan', '=', 'pesanans.id')
        //         ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
        //         ->where('pesanans.status', 'SELESAI')
        //         ->orwhere('operasionals.jenis', 'PENGELUARAN')
        //         ->where('outlets.id', $request->outlet)
        //         ->select('operasionals.*', 'outlets.nama_outlet')
        //         ->get();
        //     }
        // }else{
        //     $operasional = DB::table('operasionals')
        //     ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
        //     ->leftJoin('pesanans', 'operasionals.idpesanan', '=', 'pesanans.id')
        //     ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
        //     ->where('pesanans.status', 'SELESAI')
        //     ->orwhere('operasionals.jenis', 'PENGELUARAN')
        //     ->where('outlets.id', $user_outlet)
        //     ->orWhere('outlets.parent', $user_outlet)
        //     ->select('operasionals.*', 'outlets.nama_outlet')
        //     ->get();
        // }
        $outletQuery = '';
        if($request->outlet){
            if($request->outlet != $user_outlet){
                $outletQuery = ' and ot.id = \'' . $request->outlet .'\' ';
            }else{
                $outletQuery = ' and (ot.id = \'' . $user_outlet . '\' or ot.parent = \'' . $user_outlet . '\') ';
            }
        }else{
            $outletQuery = ' and (ot.id = \'' . $user_outlet . '\' or ot.parent = \'' . $user_outlet . '\') ';
        }
        
        // query date 
        $queryDate = '';
        if ($request->today){
            $queryDate = ' and date(o.updated_at) = \'' . Carbon::today() . '\' ';
        }else{
            $queryDate = ' and date(o.updated_at) between \'' . ($request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString()) . '\' and \'' . ($request->to ? $request->to : Carbon::now()->addday(1)->toDateString()) . '\' ';
        }
        $operasional = DB::select('select ps.*, o.*, ot.nama_outlet, ps.status from operasionals o left join outlets ot on o.outletid = ot.id left join pesanans ps on o.idpesanan = ps.id where (ps.status = \'SELESAI\' or o.jenis = \'PENGELUARAN\' or (o.jenis = \'PEMASUKAN\' and o.idpesanan is null ))  ' .$outletQuery. ' '.$queryDate.' order by o.updated_at desc');
        
        
        $totalPendapatan = DB::select('select sum(o.nominal) as "pendapatan" from operasionals o left join outlets ot on o.outletid = ot.id left join pesanans ps on o.idpesanan = ps.id where (ps.status = \'SELESAI\' and o.jenis = \'PEMASUKAN\' or (o.jenis = \'PEMASUKAN\' and o.idpesanan is null )) ' .$outletQuery. ' '.$queryDate.'');
        
        $totalPengeluaran = DB::select('select sum(o.nominal) as "pengeluaran" from operasionals o left join outlets ot on o.outletid = ot.id left join pesanans ps on o.idpesanan = ps.id where o.jenis = \'PENGELUARAN\' ' .$outletQuery. ' '.$queryDate.'');

        // dd(DB::getQueryLog()); // Show results of log

        // if ($request->outlet) {
        //     if ($request->outlet != $user_outlet) {
        //         $totalPendapatan = DB::table('operasionals')
        //         ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
        //         ->leftJoin('pesanans', 'operasionals.idpesanan', '=', 'pesanans.id')
        //         ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
        //         ->where('pesanans.status', 'SELESAI')
        //         ->where('operasionals.jenis', 'PEMASUKAN')
        //         ->where('outlets.id', $request->outlet)
        //         ->where('outlets.parent', $user_outlet)
        //         ->select(DB::raw('sum(operasionals.nominal) as "pendapatan"'))
        //         ->get();
        //     }else{
        //         $totalPendapatan = DB::table('operasionals')
        //         ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
        //         ->leftJoin('pesanans', 'operasionals.idpesanan', '=', 'pesanans.id')
        //         ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
        //         ->where('pesanans.status', 'SELESAI')
        //         ->where('operasionals.jenis', 'PEMASUKAN')
        //         ->where('outlets.id', $request->outlet)
        //         ->select(DB::raw('sum(operasionals.nominal) as "pendapatan"'))
        //         ->get();
        //     }
        // }else{
        //     $totalPendapatan = DB::table('operasionals')
        //     ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
        //     ->leftJoin('pesanans', 'operasionals.idpesanan', '=', 'pesanans.id')
        //     ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
        //     ->where('pesanans.status', 'SELESAI')
        //     ->where('operasionals.jenis', 'PEMASUKAN')
        //     ->where('outlets.id', $user_outlet)
        //     ->orWhere('outlets.parent', $user_outlet)
        //     ->select(DB::raw('sum(operasionals.nominal) as "pendapatan"'))
        //     ->get();
        // }

        // if ($request->outlet) {
        //     if ($request->outlet != $user_outlet) {
        //         $totalPengeluaran = DB::table('operasionals')
        //         ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
        //         ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
        //         ->where('operasionals.jenis', 'PENGELUARAN')
        //         ->where('outlets.id', $request->outlet)
        //         ->where('outlets.parent', $user_outlet)
        //         ->select(DB::raw('sum(operasionals.nominal) as "pengeluaran"'))
        //         ->get();
        //     }else{
        //         $totalPengeluaran = DB::table('operasionals')
        //         ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
        //         ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
        //         ->where('operasionals.jenis', 'PENGELUARAN')
        //         ->where('outlets.id', $request->outlet)
        //         ->select(DB::raw('sum(operasionals.nominal) as "pengeluaran"'))
        //         ->get();
        //     }
        // }else{
        //     $totalPengeluaran = DB::table('operasionals')
        //     ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
        //     ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
        //     ->where('operasionals.jenis', 'PENGELUARAN')
        //     ->where('outlets.id', $user_outlet)
        //     ->orWhere('outlets.parent', $user_outlet)
        //     ->select(DB::raw('sum(operasionals.nominal) as "pengeluaran"'))
        //     ->get();
        // }

        $omset = $totalPendapatan[0]->pendapatan - $totalPengeluaran[0]->pengeluaran;

        return $this->success('Success!', ['total_pendapatan' => $totalPendapatan[0]->pendapatan ? $totalPendapatan[0]->pendapatan : 0, 'total_pengeluaran' => $totalPengeluaran[0]->pengeluaran ? $totalPengeluaran[0]->pengeluaran : 0, 'omset' => $omset, 'data' => $operasional]);
    }
    
    public function operasionalKaryawan()
    {
        $user_outlet = Auth::user()->outlet_id;
        // DB::enableQueryLog(); // Enable query log

        // $operasional = DB::table('operasionals')
        // ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
        // ->leftJoin('pesanans', 'operasionals.idpesanan', '=', 'pesanans.id')
        // ->where('pesanans.status', 'SELESAI')
        // ->orwhere('operasionals.jenis', 'PENGELUARAN')
        // ->where('outlets.id', $user_outlet)
        // ->select('operasionals.*', 'outlets.nama_outlet', 'pesanans.status')
        // ->get();
        $operasional = DB::select('select o.*, ot.nama_outlet, ps.status from operasionals o left join outlets ot on o.outletid = ot.id left join pesanans ps on o.idpesanan = ps.id where ot.id = \'' . $user_outlet.'\' and (ps.status = \'SELESAI\' or o.jenis = \'PENGELUARAN\')');
        // dd(DB::getQueryLog()); // Show results of log
        
        $totalPendapatan = DB::select('select sum(o.nominal) as "pendapatan" from operasionals o left join outlets ot on o.outletid = ot.id left join pesanans ps on o.idpesanan = ps.id where (ps.status = \'SELESAI\' and o.jenis = \'PEMASUKAN\') and ot.id = \''.$user_outlet.'\'');
        // $totalPendapatan = DB::table('operasionals')
        // ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
        // ->leftJoin('pesanans', 'operasionals.idpesanan', '=', 'pesanans.id')
        // ->where('operasionals.jenis', 'PEMASUKAN')
        // ->where('outlets.id', $user_outlet)
        // ->select(DB::raw('sum(operasionals.nominal) as "pendapatan"'))
        // ->get();
        
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
            if ($request->outlet) {
                if ($request->outlet != $user_outlet) {
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
                    ->where('outlets.id', $request->outlet)
                    ->where('outlets.parent', $user_outlet)
                    ->select('users.uid','users.username', 'users.email', 'users.role', 'users.alamat', 'users.whatsapp', 'users.status', 'users.created_at as date_join', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.alamat', 'outlets.sosial_media as sosial_media')
                    ->get();
                }else{
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
                    ->where('outlets.id', $request->outlet)
                    ->select('users.uid','users.username', 'users.email', 'users.role', 'users.alamat', 'users.whatsapp', 'users.status', 'users.created_at as date_join', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.alamat', 'outlets.sosial_media as sosial_media')
                    ->get();
                }
            }else{
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
        }
        
        if($request->search == 'pelanggan'){
            $user_outlet = Auth::user()->outlet_id;
            if ($request->outlet) {
                if ($request->outlet != $user_outlet) {
                    $search = DB::table('pelanggans')
                    ->leftJoin('outlets', 'pelanggans.outletid', '=', 'outlets.id')
                    ->where('pelanggans.nama', 'like', '%' . $request->q . '%')
                    ->where('outlets.id', $request->outlet)
                    ->where('outlets.parent', $user_outlet)
                    ->select('pelanggans.id', 'pelanggans.nama', 'pelanggans.whatsapp', 'pelanggans.alamat', 'pelanggans.created_at')
                    ->get();
                }else{
                    $search = DB::table('pelanggans')
                    ->leftJoin('outlets', 'pelanggans.outletid', '=', 'outlets.id')
                    ->where('pelanggans.nama', 'like', '%' . $request->q . '%')
                    ->where('outlets.id', $request->outlet)
                    ->select('pelanggans.id', 'pelanggans.nama', 'pelanggans.whatsapp', 'pelanggans.alamat', 'pelanggans.created_at')
                    ->get();
                }
            }else{
                $search = DB::table('pelanggans')
                ->leftJoin('outlets', 'pelanggans.outletid', '=', 'outlets.id')
                ->where('pelanggans.nama', 'like', '%' . $request->q . '%')
                ->where('outlets.id', $user_outlet)
                ->orWhere('outlets.parent', $user_outlet)
                ->select('pelanggans.id', 'pelanggans.nama', 'pelanggans.whatsapp', 'pelanggans.alamat', 'pelanggans.created_at')
                ->get();
            }
        }
        
        if($request->search == 'pesanan'){
            $user_outlet = Auth::user()->outlet_id;
            if($request->status){
                if ($request->outlet) {
                    if ($request->outlet != $user_outlet) {
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
                            $query->orwhere('pesanans.created_at', 'like', '%' . $request->q . '%');
                        })
                        ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addday(1)->toDateString()])
                        ->where('pesanans.status', str::upper($request->status))
                        ->where('outlets.id', $request->outlet)
                        ->where('outlets.parent', $user_outlet)
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
                            $query->orwhere('pesanans.created_at', 'like', '%' . $request->q . '%');
                        })
                        ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addday(1)->toDateString()])
                        ->where('pesanans.status', str::upper($request->status))
                        ->where('outlets.id', $request->outlet)
                        ->select('pesanans.*', 'pelanggans.nama', 'pelanggans.whatsapp', 'pelanggans.alamat', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.sosial_media', 'services.nama_layanan', 'services.harga', 'services.kategori', 'services.jenis', 'services.item', 'pembayarans.status as statusPembayaran', 'pembayarans.metode_pembayaran', 'pembayarans.subtotal', 'pembayarans.diskon', 'pembayarans.utang', 'pembayarans.tagihan', 'pembayarans.bayar', 'waktus.nama as nama_waktu', 'waktus.waktu as durasi', 'waktus.paket as paket_waktu', 'waktus.jenis as jenis_waktu')
                        ->get();
                    }
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
                        $query->orwhere('pesanans.created_at', 'like', '%' . $request->q . '%');
                    })
                    ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addday(1)->toDateString()])
                    ->where('pesanans.status', str::upper($request->status))
                    ->where('outlets.id', $user_outlet)
                    ->orWhere('outlets.parent', $user_outlet)
                    ->select('pesanans.*', 'pelanggans.nama', 'pelanggans.whatsapp', 'pelanggans.alamat', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.sosial_media', 'services.nama_layanan', 'services.harga', 'services.kategori', 'services.jenis', 'services.item', 'pembayarans.status as statusPembayaran', 'pembayarans.metode_pembayaran', 'pembayarans.subtotal', 'pembayarans.diskon', 'pembayarans.utang', 'pembayarans.tagihan', 'pembayarans.bayar', 'waktus.nama as nama_waktu', 'waktus.waktu as durasi', 'waktus.paket as paket_waktu', 'waktus.jenis as jenis_waktu')
                    ->get();
                }
            }else{
                if ($request->outlet) {
                    if ($request->outlet != $user_outlet) {
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
                            $query->orwhere('pesanans.created_at', 'like', '%' . $request->q . '%');
                        })
                        ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addday(1)->toDateString()])
                        ->where('outlets.id', $request->outlet)
                        ->where('outlets.parent', $user_outlet)
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
                            $query->orwhere('pesanans.created_at', 'like', '%' . $request->q . '%');
                        })
                        ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addday(1)->toDateString()])
                        ->where('outlets.id', $request->outlet)
                        ->select('pesanans.*', 'pelanggans.nama', 'pelanggans.whatsapp', 'pelanggans.alamat', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.sosial_media', 'services.nama_layanan', 'services.harga', 'services.kategori', 'services.jenis', 'services.item', 'pembayarans.status as statusPembayaran', 'pembayarans.metode_pembayaran', 'pembayarans.subtotal', 'pembayarans.diskon', 'pembayarans.utang', 'pembayarans.tagihan', 'pembayarans.bayar', 'waktus.nama as nama_waktu', 'waktus.waktu as durasi', 'waktus.paket as paket_waktu', 'waktus.jenis as jenis_waktu')
                        ->get();
                    }
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
                        $query->orwhere('pesanans.created_at', 'like', '%' . $request->q . '%');
                    })
                    ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addday(1)->toDateString()])
                    ->where('outlets.id', $user_outlet)
                    ->orWhere('outlets.parent', $user_outlet)
                    ->select('pesanans.*', 'pelanggans.nama', 'pelanggans.whatsapp', 'pelanggans.alamat', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.sosial_media', 'services.nama_layanan', 'services.harga', 'services.kategori', 'services.jenis', 'services.item', 'pembayarans.status as statusPembayaran', 'pembayarans.metode_pembayaran', 'pembayarans.subtotal', 'pembayarans.diskon', 'pembayarans.utang', 'pembayarans.tagihan', 'pembayarans.bayar', 'waktus.nama as nama_waktu', 'waktus.waktu as durasi', 'waktus.paket as paket_waktu', 'waktus.jenis as jenis_waktu')
                    ->get();
                }
            }
        }
        // DB::enableQueryLog(); // Enable query log
        
        if($request->search == 'operasional'){
            $user_outlet = Auth::user()->outlet_id;
            if ($request->outlet) {
                if ($request->outlet != $user_outlet) {
                    $search = DB::table('operasionals')
                    ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
                    ->leftJoin('pesanans', 'operasionals.idpesanan', '=', 'pesanans.id')
                    ->where(function($query) use($request) {
                        $query;
                        $query->where('operasionals.keterangan', 'like', '%' . $request->q . '%');
                        $query->where('operasionals.jenis', 'like', '%' . $request->jenis . '%');
                        $query->orWhere('operasionals.nominal', 'like', '%' . $request->q . '%');
                    })
                    ->where(function($query) use($request) {
                        $query;
                        $query->where('pesanans.status', 'SELESAI');
                        $query->orWhere('operasionals.jenis','PENGELUARAN');
                    })
                    ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addday(1)->toDateString()])
                    ->where('outlets.id', $request->outlet)
                    ->where('outlets.parent', $user_outlet)
                    ->select('operasionals.*')
                    ->get();
                }else{
                    $search = DB::table('operasionals')
                    ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
                    ->leftJoin('pesanans', 'operasionals.idpesanan', '=', 'pesanans.id')
                    ->where(function($query) use($request) {
                        $query;
                        $query->where('operasionals.jenis', 'like', '%' . $request->jenis . '%');
                        $query->where('operasionals.keterangan', 'like', '%' . $request->q . '%');
                        $query->orWhere('operasionals.nominal', 'like', '%' . $request->q . '%');
                    })
                    ->where(function($query) use($request) {
                        $query;
                        $query->where('pesanans.status', 'SELESAI');
                        $query->orWhere('operasionals.jenis','PENGELUARAN');
                    })
                    ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addday(1)->toDateString()])
                    ->where('outlets.id', $request->outlet)
                    ->select('operasionals.*')
                    ->get();
                }
            }else{
                $search = DB::table('operasionals')
                ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
                ->leftJoin('pesanans', 'operasionals.idpesanan', '=', 'pesanans.id')
                ->where(function($query) use($request) {
                    $query;
                    $query->where('operasionals.jenis', 'like', '%' . $request->jenis . '%');
                    $query->where('operasionals.keterangan', 'like', '%' . $request->q . '%');
                    $query->orWhere('operasionals.nominal', 'like', '%' . $request->q . '%');
                })
                ->where(function($query) use($request) {
                    $query;
                    $query->where('pesanans.status', 'SELESAI');
                    $query->orWhere('operasionals.jenis','PENGELUARAN');
                })
                ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addday(1)->toDateString()])
                ->where('outlets.id', $user_outlet)
                ->orWhere('outlets.parent', $user_outlet)
                ->select('operasionals.*','pesanans.status')
                ->get();
            }
        }
        // dd(DB::getQueryLog()); // Show results of log

        
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
            ->leftJoin('pesanans', 'operasionals.idpesanan', '=', 'pesanans.id')
            ->where(function($query) use($request) {
                $query;
                $query->where('operasionals.keterangan', 'like', '%' . $request->q . '%');
                $query->where(DB::raw('upper(operasionals.jenis)'), 'like', '%' . str::upper($request->jenis) . '%');
                $query->orWhere('operasionals.nominal', 'like', '%' . $request->q . '%');
            })
            ->where(function($query) use($request) {
                $query;
                $query->where('pesanans.status', 'SELESAI');
                $query->orWhere('operasionals.jenis','PENGELUARAN');
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
        DB::enableQueryLog(); // Enable query log

        $user_outlet = Auth::user()->outlet_id;
        // if($request->status){
        //     if($request->from != FALSE || $request->to != FALSE){
        //         $pesanan = DB::table('pesanans')
        //             ->leftJoin('pelanggans', 'pesanans.idpelanggan', '=', 'pelanggans.id')
        //             ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        //             ->leftJoin('services', 'pesanans.idlayanan', '=', 'services.id')
        //             ->leftJoin('waktus', 'pesanans.idwaktu', '=', 'waktus.id')
        //             ->rightJoin('pembayarans', 'pesanans.id', '=', 'pembayarans.idpesanan')
        //             ->where(DB::raw('upper(pesanans.status)'), Str::upper($request->status))
        //             ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(90)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
        //             ->where(function($query) use($user_outlet) {
        //                 $query;
        //                 $query->where('outlets.id', $user_outlet);
        //                 $query->orWhere('outlets.parent', $user_outlet);
        //             })
        //             ->select('pesanans.*', 'pelanggans.nama', 'pelanggans.whatsapp', 'pelanggans.alamat', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.sosial_media', 'services.nama_layanan', 'services.harga', 'services.kategori', 'services.jenis', 'services.item', 'pembayarans.status as statusPembayaran', 'pembayarans.metode_pembayaran', 'pembayarans.subtotal', 'pembayarans.diskon', 'pembayarans.utang', 'pembayarans.tagihan', 'pembayarans.bayar', 'waktus.nama as nama_waktu', 'waktus.waktu as durasi', 'waktus.paket as paket_waktu', 'waktus.jenis as jenis_waktu')
        //             ->orderBy('created_at', 'DESC')
        //             ->get();
        //     }else{
        //         $pesanan = DB::table('pesanans')
        //             ->leftJoin('pelanggans', 'pesanans.idpelanggan', '=', 'pelanggans.id')
        //             ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        //             ->leftJoin('services', 'pesanans.idlayanan', '=', 'services.id')
        //             ->leftJoin('waktus', 'pesanans.idwaktu', '=', 'waktus.id')
        //             ->rightJoin('pembayarans', 'pesanans.id', '=', 'pembayarans.idpesanan')
        //             ->where(DB::raw('upper(pesanans.status)'), Str::upper($request->status))
        //             ->where(function($query) use($user_outlet) {
        //                 $query;
        //                 $query->where('outlets.id', $user_outlet);
        //                 $query->orWhere('outlets.parent', $user_outlet);
        //             })
        //             ->select('pesanans.*', 'pelanggans.nama', 'pelanggans.whatsapp', 'pelanggans.alamat', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.sosial_media', 'services.nama_layanan', 'services.harga', 'services.kategori', 'services.jenis', 'services.item', 'pembayarans.status as statusPembayaran', 'pembayarans.metode_pembayaran', 'pembayarans.subtotal', 'pembayarans.diskon', 'pembayarans.utang', 'pembayarans.tagihan', 'pembayarans.bayar', 'waktus.nama as nama_waktu', 'waktus.waktu as durasi', 'waktus.paket as paket_waktu', 'waktus.jenis as jenis_waktu')
        //             ->orderBy('created_at', 'DESC')
        //             ->get();
        //     }
        // }else{
        //     if ($request->from != FALSE || $request->to != FALSE) {
        //         $pesanan = DB::table('pesanans')
        //             ->leftJoin('pelanggans', 'pesanans.idpelanggan', '=', 'pelanggans.id')
        //             ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        //             ->leftJoin('services', 'pesanans.idlayanan', '=', 'services.id')
        //             ->leftJoin('waktus', 'pesanans.idwaktu', '=', 'waktus.id')
        //             ->rightJoin('pembayarans', 'pesanans.id', '=', 'pembayarans.idpesanan')
        //             ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
        //             ->where('outlets.id', $user_outlet)
        //             ->orWhere('outlets.parent', $user_outlet)
        //             ->select('pesanans.*', 'pelanggans.nama', 'pelanggans.whatsapp', 'pelanggans.alamat', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.sosial_media', 'services.nama_layanan', 'services.harga', 'services.kategori', 'services.jenis', 'services.item', 'pembayarans.status as statusPembayaran', 'pembayarans.metode_pembayaran', 'pembayarans.subtotal', 'pembayarans.diskon', 'pembayarans.utang', 'pembayarans.tagihan', 'pembayarans.bayar', 'waktus.nama as nama_waktu', 'waktus.waktu as durasi', 'waktus.paket as paket_waktu', 'waktus.jenis as jenis_waktu')
        //             ->orderBy('created_at', 'DESC')
        //             ->get();
        //     }else{
        //         $pesanan = DB::table('pesanans')
        //             ->leftJoin('pelanggans', 'pesanans.idpelanggan', '=', 'pelanggans.id')
        //             ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        //             ->leftJoin('services', 'pesanans.idlayanan', '=', 'services.id')
        //             ->leftJoin('waktus', 'pesanans.idwaktu', '=', 'waktus.id')
        //             ->rightJoin('pembayarans', 'pesanans.id', '=', 'pembayarans.idpesanan')
        //             ->where('outlets.id', $user_outlet)
        //             ->orWhere('outlets.parent', $user_outlet)
        //             ->select('pesanans.*', 'pelanggans.nama', 'pelanggans.whatsapp', 'pelanggans.alamat', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.sosial_media', 'services.nama_layanan', 'services.harga', 'services.kategori', 'services.jenis', 'services.item', 'pembayarans.status as statusPembayaran', 'pembayarans.metode_pembayaran', 'pembayarans.subtotal', 'pembayarans.diskon', 'pembayarans.utang', 'pembayarans.tagihan', 'pembayarans.bayar', 'waktus.nama as nama_waktu', 'waktus.waktu as durasi', 'waktus.paket as paket_waktu', 'waktus.jenis as jenis_waktu')
        //             ->orderBy('created_at', 'DESC')
        //             ->get();

        //     }

        // }

        $dateQuery = '';
        if($request->from != FALSE && $request->to != FALSE && $request->from != 'false' && $request->to != 'false'){
            $dateQuery = ' and DATE_FORMAT(pesanans.created_at, \'%Y-%m-%d\') between \'' . ($request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString()) . '\' and \'' . ($request->to ? $request->to : Carbon::now()->addday(1)->toDateString()). '\'';
        }

        $outletQuery = '';
        if ($request->outlet) {
            if ($request->outlet != $user_outlet) {
                $outletQuery = '(outlets.id = \'' .$request->outlet. '\' and outlets.parent = \'' .$user_outlet. '\')';
            }else{
                $outletQuery = '(outlets.id = \'' .$request->outlet. '\')';
            }
        }else{
            $outletQuery = '(outlets.id = \'' .$user_outlet. '\' or outlets.parent = \'' .$user_outlet. '\')';
        }

        $statusQuery = '';
        if ($request->status) {
            if (Str::upper($request->status) == 'UTANG' || Str::upper($request->status) == 'BELUM BAYAR' ){
                $statusQuery = ' and (upper(pembayarans.status) = \'BELUM BAYAR\' or upper(pembayarans.status) = \'UTANG\')';
            }else{
                $statusQuery = ' and upper(pesanans.status) = \'' . Str::upper($request->status) .'\'';
            }
        }

                // dd(DB::getQueryLog()); // Show results of log

        $query = $outletQuery . $dateQuery . $statusQuery;

        // dd('select pesanans.*, pelanggans.nama, pelanggans.whatsapp, pelanggans.alamat, outlets.nama_outlet, outlets.status_outlet, outlets.sosial_media, services.nama_layanan, services.harga, services.kategori, services.jenis, services.item, pembayarans.status as statusPembayaran, pembayarans.metode_pembayaran, pembayarans.subtotal, pembayarans.diskon, pembayarans.utang, pembayarans.tagihan, pembayarans.bayar, waktus.nama as nama_waktu, waktus.waktu as durasi, waktus.paket as paket_waktu, waktus.jenis as jenis_waktu from pesanans left join pelanggans on pesanans.idpelanggan = pelanggans.id left join outlets on pesanans.outletid = outlets.id left join services on pesanans.idlayanan = services.id left join waktus on pesanans.idwaktu = waktus.id right join pembayarans on pesanans.id = pembayarans.idpesanan where ' . $query . ' order by created_at desc');
        
        $pesanan = DB::select('select pesanans.*, pelanggans.nama, pelanggans.whatsapp, pelanggans.alamat, outlets.nama_outlet, outlets.status_outlet, outlets.sosial_media, services.nama_layanan, services.harga, services.kategori, services.jenis, services.item, pembayarans.status as statusPembayaran, pembayarans.metode_pembayaran, pembayarans.subtotal, pembayarans.diskon, pembayarans.utang, pembayarans.tagihan, pembayarans.bayar, waktus.nama as nama_waktu, waktus.waktu as durasi, waktus.paket as paket_waktu, waktus.jenis as jenis_waktu from pesanans left join pelanggans on pesanans.idpelanggan = pelanggans.id left join outlets on pesanans.outletid = outlets.id left join services on pesanans.idlayanan = services.id left join waktus on pesanans.idwaktu = waktus.id right join pembayarans on pesanans.id = pembayarans.idpesanan where ' . $query . ' order by created_at desc');
        
        return $this->success('Success!', $pesanan);
    }
    public function report(Request $request)
    {
        $user_outlet = Auth::user()->outlet_id;
        // DB::enableQueryLog(); // Enable query log
        
        if ($request->outlet){
            if ($request->outlet != $user_outlet) {
                $kiloan = DB::table('pesanans')
                    ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
                    ->leftJoin('services', 'pesanans.idlayanan', '=', 'services.id')
                    ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
                    ->where('services.jenis', 'kiloan')
                    ->where('outlets.id', $request->outlet)
                    ->where('outlets.parent', $user_outlet)
                    ->select(DB::raw('sum(pesanans.jumlah)'))
                    ->get();
            }else{
                $kiloan = DB::table('pesanans')
                    ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
                    ->leftJoin('services', 'pesanans.idlayanan', '=', 'services.id')
                    ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
                    ->where('services.jenis', 'kiloan')
                    ->where('outlets.id', $request->outlet)
                    ->select(DB::raw('sum(pesanans.jumlah)'))
                    ->get();
            }
        }else{
            $kiloan = DB::table('pesanans')
                ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
                ->leftJoin('services', 'pesanans.idlayanan', '=', 'services.id')
                ->whereBetween('pesanans.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
                ->where('services.jenis', 'kiloan')
                ->where('outlets.id', $user_outlet)
                ->orWhere('outlets.parent', $user_outlet)
                ->select(DB::raw('sum(pesanans.jumlah)'))
                ->get();
        }
        
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
        if ($request->outlet) {
            if ($request->outlet != $user_outlet) {
                $query = 'and ou.id = \''. $request->outlet . '\' and ou.parent = \''. $user_outlet . '\'';
            }else{
                $query = 'and ou.id = \''. $request->outlet . '\'';
            }
        }else{
            $query = 'and ou.id = \''. $user_outlet . '\' or ou.parent = \''. $user_outlet . '\'';
        }
        if($request->from != FALSE && $request->to != FALSE && $request->from != TRUE && $request->to != TRUE){
            $pendapatanharian = DB::select('
                with recursive Date_Ranges AS (
                select \''. $request->from . '\' as Date
                union all
                select Date + interval 1 day
                from Date_Ranges
                where Date < \''. $request->to . '\'), 
                data_pemasukan AS (
                SELECT case when sum(o.nominal) IS NULL then 0 else sum(o.nominal) end as data_pemasukan, DATE_FORMAT(o.created_at, \'%Y-%m-%d\') as date from operasionals o LEFT JOIN outlets ou on o.outletid = ou.id left join pesanans ps on o.idpesanan = ps.id where o.jenis = \'PEMASUKAN\' and ps.status = \'SELESAI\' ' . $query . ' GROUP BY DATE_FORMAT(o.created_at, \'%Y-%m-%d\')
                ),
                data_pengeluaran AS (
                SELECT case when sum(o.nominal) IS NULL then 0 else sum(o.nominal) end as data_pengeluaran, DATE_FORMAT(o.created_at, \'%Y-%m-%d\') as date from operasionals o LEFT JOIN outlets ou on o.outletid = ou.id where o.jenis = \'PENGELUARAN\' ' . $query . ' GROUP BY DATE_FORMAT(o.created_at, \'%Y-%m-%d\')
                )
               
                SELECT dr.Date, (case when (SELECT dps.data_pemasukan from data_pemasukan dps where dps.date = dr.Date) IS NULL then 0 else (SELECT dps.data_pemasukan from data_pemasukan dps where dps.date = dr.Date) end) as data_pemasukan, (case when (SELECT dpn.data_pengeluaran from data_pengeluaran dpn where dpn.date = dr.Date) IS NULL then 0 else (SELECT dpn.data_pengeluaran from data_pengeluaran dpn where dpn.date = dr.Date) end) as data_pengeluaran, ABS((case when (SELECT dps.data_pemasukan from data_pemasukan dps where dps.date = dr.Date) IS NULL then 0 else (SELECT dps.data_pemasukan from data_pemasukan dps where dps.date = dr.Date) end) - (case when (SELECT dpn.data_pengeluaran from data_pengeluaran dpn where dpn.date = dr.Date) IS NULL then 0 else (SELECT dpn.data_pengeluaran from data_pengeluaran dpn where dpn.date = dr.Date) end)) AS total FROM Date_Ranges dr GROUP BY dr.Date ORDER BY dr.Date
            ');
        }else{
            $pendapatanharian = DB::select('
            with recursive Date_Ranges AS (
                select CURRENT_DATE - INTERVAL 30 day as Date
               union all
               select Date + interval 1 day
               from Date_Ranges
               where Date < CURRENT_DATE + interval 1 day), 
               data_pemasukan AS (
               SELECT case when sum(o.nominal) IS NULL then 0 else sum(o.nominal) end as data_pemasukan, DATE_FORMAT(o.created_at, \'%Y-%m-%d\') as date from operasionals o LEFT JOIN outlets ou on o.outletid = ou.id left join pesanans ps on o.idpesanan = ps.id where o.jenis = \'PEMASUKAN\' and ps.status = \'SELESAI\' ' . $query . ' GROUP BY DATE_FORMAT(o.created_at, \'%Y-%m-%d\')
               ),
               data_pengeluaran AS (
               SELECT case when sum(o.nominal) IS NULL then 0 else sum(o.nominal) end as data_pengeluaran, DATE_FORMAT(o.created_at, \'%Y-%m-%d\') as date from operasionals o LEFT JOIN outlets ou on o.outletid = ou.id where o.jenis = \'PENGELUARAN\' ' . $query . ' GROUP BY DATE_FORMAT(o.created_at, \'%Y-%m-%d\')
               )
               
               SELECT dr.Date, (case when (SELECT dps.data_pemasukan from data_pemasukan dps where dps.date = dr.Date) IS NULL then 0 else (SELECT dps.data_pemasukan from data_pemasukan dps where dps.date = dr.Date) end) as data_pemasukan, (case when (SELECT dpn.data_pengeluaran from data_pengeluaran dpn where dpn.date = dr.Date) IS NULL then 0 else (SELECT dpn.data_pengeluaran from data_pengeluaran dpn where dpn.date = dr.Date) end) as data_pengeluaran, ABS((case when (SELECT dps.data_pemasukan from data_pemasukan dps where dps.date = dr.Date) IS NULL then 0 else (SELECT dps.data_pemasukan from data_pemasukan dps where dps.date = dr.Date) end) - (case when (SELECT dpn.data_pengeluaran from data_pengeluaran dpn where dpn.date = dr.Date) IS NULL then 0 else (SELECT dpn.data_pengeluaran from data_pengeluaran dpn where dpn.date = dr.Date) end)) AS total FROM Date_Ranges dr GROUP BY dr.Date ORDER BY dr.Date
            ');
        }

        // DB::enableQueryLog(); // Enable query log
        if ($request->outlet){
            if($request->outlet != $user_outlet){
                $totalPendapatan = DB::table('operasionals')
                ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
                ->leftJoin('pesanans', 'operasionals.idpesanan', '=', 'pesanans.id')
                ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
                ->where('pesanans.status', 'SELESAI')
                ->where('operasionals.jenis', 'PEMASUKAN')
                ->where('outlets.id', $request->outlet)
                ->where('outlets.parent', $user_outlet)
                ->select(DB::raw('sum(operasionals.nominal) as "pendapatan"'))
                ->get();
            }else{
                $totalPendapatan = DB::table('operasionals')
                ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
                ->leftJoin('pesanans', 'operasionals.idpesanan', '=', 'pesanans.id')
                ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
                ->where('pesanans.status', 'SELESAI')
                ->where('operasionals.jenis', 'PEMASUKAN')
                ->where('outlets.id', $request->outlet)
                ->select(DB::raw('sum(operasionals.nominal) as "pendapatan"'))
                ->get();
            }
        }else{
            $totalPendapatan = DB::table('operasionals')
            ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
            ->leftJoin('pesanans', 'operasionals.idpesanan', '=', 'pesanans.id')
            ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
            ->where('pesanans.status', 'SELESAI')
            ->where('operasionals.jenis', 'PEMASUKAN')
            ->where('outlets.id', $user_outlet)
            ->orWhere('outlets.parent', $user_outlet)
            ->select(DB::raw('sum(operasionals.nominal) as "pendapatan"'))
            ->get();
        }
        // dd(DB::getQueryLog()); // Show results of log


        if ($request->outlet){
            if($request->outlet != $user_outlet){
                $totalPengeluaran = DB::table('operasionals')
                ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
                ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
                ->where('operasionals.jenis', 'PENGELUARAN')
                ->where('outlets.id', $request->outlet)
                ->where('outlets.parent', $user_outlet)
                ->select(DB::raw('sum(operasionals.nominal) as "pengeluaran"'))
                ->get();
            }else{
                $totalPengeluaran = DB::table('operasionals')
                ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
                ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
                ->where('operasionals.jenis', 'PENGELUARAN')
                ->where('outlets.id', $request->outlet)
                ->select(DB::raw('sum(operasionals.nominal) as "pengeluaran"'))
                ->get();
            }
        }else{
            $totalPengeluaran = DB::table('operasionals')
            ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
            ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
            ->where('operasionals.jenis', 'PENGELUARAN')
            ->where('outlets.id', $user_outlet)
            ->orWhere('outlets.parent', $user_outlet)
            ->select(DB::raw('sum(operasionals.nominal) as "pengeluaran"'))
            ->get();
        }
        
        $pendapatanArr = [];
        foreach($pendapatanharian as $dataHarian){
            // $data = {};
            $data['Date'] = $dataHarian->Date;
            $data['data_pemasukan'] = $dataHarian->data_pemasukan;
            $data['data_pengeluaran'] = $dataHarian->data_pengeluaran;
            $data['total'] =  $dataHarian->data_pemasukan-$dataHarian->data_pengeluaran ;
            array_push($pendapatanArr,$data);
        }

        $omset = ($totalPendapatan[0]->pendapatan ? $totalPendapatan[0]->pendapatan : 0) - ($totalPengeluaran[0]->pengeluaran ? $totalPengeluaran[0]->pengeluaran : 0);

        return $this->success('Success!', ["harian" => $pendapatanArr, "total_pendapatan" => $totalPendapatan[0]->pendapatan ? $totalPendapatan[0]->pendapatan : 0, "total_pengeluaran" => $totalPengeluaran[0]->pengeluaran ? $totalPengeluaran[0]->pengeluaran : 0, "omset" => $omset]);
    }
    
    public function totalPemasukanAdmin(Request $request)
    {
        $user_outlet = Auth::user()->outlet_id;
        if ($request->outlet){
            if($request->outlet != $user_outlet){
                $totalpendapatan = DB::table('operasionals')
                    ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
                    ->leftJoin('pesanans', 'operasionals.idpesanan', '=', 'pesanans.id')
                    ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
                    ->where('pesanans.status', 'SELESAI')
                    ->where('operasionals.jenis', 'PEMASUKAN')
                    ->where('outlets.id', $request->outlet)
                    ->where('outlets.parent', $user_outlet)
                    ->get(DB::raw('sum(operasionals.nominal) as "pendapatan"'));
            }else{
                $totalpendapatan = DB::table('operasionals')
                    ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
                    ->leftJoin('pesanans', 'operasionals.idpesanan', '=', 'pesanans.id')
                    ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
                    ->where('pesanans.status', 'SELESAI')
                    ->where('operasionals.jenis', 'PEMASUKAN')
                    ->where('outlets.id', $request->outlet)
                    ->get(DB::raw('sum(operasionals.nominal) as "pendapatan"'));
            }
        }else{
            $totalpendapatan = DB::table('operasionals')
                ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
                ->leftJoin('pesanans', 'operasionals.idpesanan', '=', 'pesanans.id')
                ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
                ->where('pesanans.status', 'SELESAI')
                ->where('operasionals.jenis', 'PEMASUKAN')
                ->where('outlets.id', $user_outlet)
                ->orWhere('outlets.parent', $user_outlet)
                ->get(DB::raw('sum(operasionals.nominal) as "pendapatan"'));
        }
        
        if ($request->outlet){
            if($request->outlet != $user_outlet){
                $totalpengeluaran = DB::table('operasionals')
                    ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
                    ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
                    ->where('operasionals.jenis', 'PENGELUARAN')
                    ->where('outlets.id', $request->outlet)
                    ->where('outlets.parent', $user_outlet)
                    ->get(DB::raw('sum(operasionals.nominal) as "pengeluaran"'));
            }else{
                $totalpengeluaran = DB::table('operasionals')
                    ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
                    ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
                    ->where('operasionals.jenis', 'PENGELUARAN')
                    ->where('outlets.id', $request->outlet)
                    ->get(DB::raw('sum(operasionals.nominal) as "pengeluaran"'));
            }
        }else{
            $totalpengeluaran = DB::table('operasionals')
                ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
                ->whereBetween('operasionals.created_at', [$request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString(), $request->to ? $request->to : Carbon::now()->addWeeks(1)->toDateString()])
                ->where('operasionals.jenis', 'PENGELUARAN')
                ->where('outlets.id', $user_outlet)
                ->orWhere('outlets.parent', $user_outlet)
                ->get(DB::raw('sum(operasionals.nominal) as "pengeluaran"'));
        }
        
        $totalpemasukan = $totalpendapatan[0]->pendapatan - $totalpengeluaran[0]->pengeluaran;

        return $this->success('Success!', ['totalPendapatan' => $totalpemasukan]);
    }
    
    public function totalPemasukanKasir()
    {
        $user_outlet = Auth::user()->outlet_id;

        $totalpendapatan = DB::table('operasionals')
            ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
            ->leftJoin('pesanans', 'operasionals.idpesanan', '=', 'pesanans.id')
            ->whereDate('operasionals.created_at', Carbon::today())
            ->where('pesanans.status', 'SELESAI')
            ->where('operasionals.jenis', 'PEMASUKAN')
            ->where('outlets.id', $user_outlet)
            ->get(DB::raw('sum(operasionals.nominal) as "pendapatan"'));
        
        $totalpengeluaran = DB::table('operasionals')
            ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
            ->leftJoin('pesanans', 'operasionals.idpesanan', '=', 'pesanans.id')
            ->whereDate('operasionals.created_at', Carbon::today())
            ->where('pesanans.status', 'SELESAI')
            ->where('operasionals.jenis', 'PENGELUARAN')
            ->where('outlets.id', $user_outlet)
            ->get(DB::raw('sum(operasionals.nominal) as "pengeluaran"'));
        
        $totalpemasukan = $totalpendapatan[0]->pendapatan - $totalpengeluaran[0]->pengeluaran;

        return $this->success('Success!', ['totalPendapatan' => $totalpemasukan, 'pendapatan' => $totalpendapatan[0]->pendapatan ? $totalpendapatan[0]->pendapatan : 0, 'pengeluaran' => $totalpengeluaran[0]->pengeluaran ? $totalpengeluaran[0]->pengeluaran : 0]);
    }

    public function reportTransaksi(Request $request)
    {
        // dd($request->from, $request->to);
        $user_outlet = Auth::user()->outlet_id;
        if ($request->outlet) {
            if ($request->outlet != $user_outlet) {
                $query = 'and ou.id = \''. $request->outlet . '\' and ou.parent = \''. $user_outlet . '\'';
            }else{
                $query = 'and ou.id = \''. $request->outlet . '\'';
            }
        }else{
            $query = 'and ou.id = \''. $user_outlet . '\' or ou.parent = \''. $user_outlet . '\'';
        }

        if($request->from != FALSE && $request->to != FALSE){
            $report = DB::select('
                with recursive Date_Ranges AS (
                select \''. $request->from . '\' as Date
                union all
                select Date + interval 1 day
                from Date_Ranges
                where Date < \''. $request->to . '\'), 
                lunas AS (
                SELECT case when sum(p.tagihan) IS NULL then 0 else sum(p.tagihan) end as lunas, DATE_FORMAT(p.created_at, \'%Y-%m-%d\') as date
                from pesanans ps 
                inner join outlets ou on ou.id = ps.outletid
                inner join pembayarans p on p.idpesanan = ps.id 
                where (p.status = \'LUNAS\' and ps.status != \'DIBATALKAN\') ' . $query . ' GROUP BY DATE_FORMAT(p.created_at, \'%Y-%m-%d\')
                ),
                utang AS (
                SELECT case when sum(p.tagihan) IS NULL then 0 else sum(p.tagihan) end as utang, DATE_FORMAT(p.created_at, \'%Y-%m-%d\') as date
                from pesanans ps 
                inner join outlets ou on ou.id = ps.outletid
                inner join pembayarans p on p.idpesanan = ps.id 
                where (p.status = \'BELUM BAYAR\' or p.status = \'UTANG\') and ps.status != \'DIBATALKAN\' ' . $query . ' GROUP BY DATE_FORMAT(p.created_at, \'%Y-%m-%d\')
                ),
                total AS (
                SELECT count(ps.id) as total, DATE_FORMAT(p.created_at, \'%Y-%m-%d\') as date
                from pesanans ps 
                inner join outlets ou on ou.id = ps.outletid
                inner join pembayarans p on p.idpesanan = ps.id 
                where (p.tagihan is not null and ps.status != \'DIBATALKAN\') ' . $query . ' GROUP BY DATE_FORMAT(p.created_at, \'%Y-%m-%d\')
                )
                
                SELECT dr.Date, 
                (case when (SELECT ln.lunas from lunas ln where ln.date = dr.Date) IS NULL then 0 else (SELECT ln.lunas from lunas ln where ln.date = dr.Date) end) as lunas, 
                (case when (SELECT ut.utang from utang ut where ut.date = dr.Date) IS NULL then 0 else (SELECT ut.utang from utang ut where ut.date = dr.Date) end) as utang,
                (case when (SELECT t.total from total t where t.date = dr.Date) IS NULL then 0 else (SELECT t.total from total t where t.date = dr.Date) end) as total_transaksi FROM Date_Ranges dr GROUP BY dr.Date ORDER BY dr.Date
            ');
        }else{
            $report = DB::select('
                with recursive Date_Ranges AS (
                select CURRENT_DATE - INTERVAL 30 day as Date
                union all
                select Date + interval 1 day
                from Date_Ranges
                where Date < CURRENT_DATE + interval 1 day), 
                lunas AS (
                SELECT case when sum(p.tagihan) IS NULL then 0 else sum(p.tagihan) end as lunas, DATE_FORMAT(p.created_at, \'%Y-%m-%d\') as date
                from pesanans ps 
                inner join outlets ou on ou.id = ps.outletid
                inner join pembayarans p on p.idpesanan = ps.id 
                where (p.status = \'LUNAS\' and ps.status != \'DIBATALKAN\') ' . $query . ' GROUP BY DATE_FORMAT(p.created_at, \'%Y-%m-%d\')
                ),
                utang AS (
                SELECT case when sum(p.tagihan) IS NULL then 0 else sum(p.tagihan) end as utang, DATE_FORMAT(p.created_at, \'%Y-%m-%d\') as date
                from pesanans ps 
                inner join outlets ou on ou.id = ps.outletid
                inner join pembayarans p on p.idpesanan = ps.id 
                where (p.status = \'BELUM BAYAR\' or p.status = \'UTANG\') and ps.status != \'DIBATALKAN\' ' . $query . ' GROUP BY DATE_FORMAT(p.created_at, \'%Y-%m-%d\')
                ),
                total AS (
                SELECT count(ps.id) as total, DATE_FORMAT(p.created_at, \'%Y-%m-%d\') as date
                from pesanans ps 
                inner join outlets ou on ou.id = ps.outletid
                inner join pembayarans p on p.idpesanan = ps.id 
                where (p.tagihan is not null and ps.status != \'DIBATALKAN\') ' . $query . ' GROUP BY DATE_FORMAT(p.created_at, \'%Y-%m-%d\')
                )
                
                SELECT dr.Date, 
                (case when (SELECT ln.lunas from lunas ln where ln.date = dr.Date) IS NULL then 0 else (SELECT ln.lunas from lunas ln where ln.date = dr.Date) end) as lunas, 
                (case when (SELECT ut.utang from utang ut where ut.date = dr.Date) IS NULL then 0 else (SELECT ut.utang from utang ut where ut.date = dr.Date) end) as utang,
                (case when (SELECT t.total from total t where t.date = dr.Date) IS NULL then 0 else (SELECT t.total from total t where t.date = dr.Date) end) as total_transaksi FROM Date_Ranges dr GROUP BY dr.Date ORDER BY dr.Date
            ');
        }

        $totalData = DB::select('
            SELECT sum((SELECT ps2.jumlah from pesanans ps2 LEFT JOIN services se2 on ps2.idlayanan = se2.id WHERE ps2.id = ps.id and se2.jenis = \'kiloan\')) as total_kiloan, sum((SELECT ps2.jumlah from pesanans ps2 LEFT JOIN services se2 on ps2.idlayanan = se2.id WHERE ps2.id = ps.id and se2.jenis = \'satuan\')) as total_item, sum((SELECT p2.tagihan from pembayarans p2 WHERE p2.idpesanan = ps.id and p2.status = \'LUNAS\')) as total_pemasukan , sum((SELECT p2.tagihan from pembayarans p2 WHERE p2.idpesanan = ps.id and (p2.status = \'UTANG\'or p2.status = \'BELUM BAYAR\'))) as total_utang, COUNT(ps.id) as total_transaksi FROM pesanans ps
            INNER JOIN pembayarans p on ps.id = p.idpesanan
            INNER JOIN outlets ou on ps.outletid = ou.id
            INNER JOIN services se on ps.idlayanan = se.id
            WHERE ps.status != \'DIBATALKAN\' and (DATE_FORMAT(ps.created_at, \'%Y-%m-%d\') between \'' . ($request->from ? $request->from : Carbon::now()->subDays(30)->startOfDay()->toDateString()) . '\' and \'' . ($request->to ? $request->to : Carbon::now()->addday(1)->toDateString()). '\') ' . $query 
        );
        
        return $this->success('Success!', [
            'report_harian' => $report,
            'total_kiloan' => $totalData[0]->total_kiloan ? $totalData[0]->total_kiloan : 0, 
            'total_item' => $totalData[0]->total_item ? $totalData[0]->total_item : 0, 
            'total_pemasukan' => $totalData[0]->total_pemasukan ? $totalData[0]->total_pemasukan : 0, 
            'total_utang' => $totalData[0]->total_utang ? $totalData[0]->total_utang : 0, 
            'total_transaksi' => $totalData[0]->total_transaksi ? $totalData[0]->total_transaksi : 0, 
        ]);
    }
    public function keuanganKasir()
    {
        $user_outlet = Auth::user()->outlet_id;
        $keuangan = DB::select('select ps.*, pl.nama as namaPelanggan, o.jenis as jenisOperasional, o.jenis_service, o.kasir, o.keterangan, o.item_name as namaBarang, o.satuan, o.harga as hargaBarang, o.jumlah as jumlahBarang,  o.nominal, o.outletid, os.nama_outlet, se.nama_layanan ,se.harga, se.jenis, se.item, o.created_at as operasionalCreatedDate, o.updated_at as opeasionalUpdatedDate, pb.status as statusPembayaran from operasionals o left JOIN outlets os on o.outletid = os.id left JOIN pesanans ps on o.idpesanan = ps.id LEFT JOIN services se on ps.idlayanan = se.id left join pembayarans pb on ps.id = pb.idpesanan left join pelanggans pl on ps.idpelanggan = pl.id where (ps.status != \'DIBATALKAN\' and (pb.status != \'BELUM BAYAR\' and pb.status != \'UTANG\') or o.jenis = \'PENGELUARAN\' or (o.jenis = \'PEMASUKAN\' and o.idpesanan is null)) and o.outletid = \'' . $user_outlet . '\' and date(o.created_at) = \'' . date('Y-m-d') . '\'');

        $totalPemasukan = DB::select('select sum(o.nominal) as totalPemasukan from operasionals o left JOIN outlets os on o.outletid = os.id left JOIN pesanans ps on o.idpesanan = ps.id LEFT JOIN services se on ps.idlayanan = se.id left join pembayarans pb on ps.id = pb.idpesanan where o.outletid = \'' . $user_outlet . '\' and date(o.created_at) = \'' . date('Y-m-d') . '\' and o.jenis = \'PEMASUKAN\' and (pb.status != \'BELUM BAYAR\' and pb.status != \'UTANG\' and ps.status != \'DIBATALKAN\' or (o.jenis = \'PEMASUKAN\' and o.idpesanan is null))');
        
        $totalPengeluaran = DB::select('select sum(o.nominal) as totalPengeluaran from operasionals o left JOIN outlets os on o.outletid = os.id left JOIN pesanans ps on o.idpesanan = ps.id LEFT JOIN services se on ps.idlayanan = se.id where o.outletid = \'' . $user_outlet . '\' and date(o.created_at) = \'' . date('Y-m-d') . '\' and o.jenis = \'PENGELUARAN\'');

        $total['totalPemasukan'] = $totalPemasukan[0]->totalPemasukan ? $totalPemasukan[0]->totalPemasukan : 0;
        $total['totalPengeluaran'] = $totalPengeluaran[0]->totalPengeluaran ? $totalPengeluaran[0]->totalPengeluaran : 0;


        return $this->success('Success!', [$keuangan, $total]);
    }



    public function pengeluaran(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'harga' => 'required|integer',
            'item_name' => 'required|string',
            'jumlah' => 'required|integer',
            'keterangan' => 'string',
            'satuan' => 'string',
        ]);

        if($validator->fails()){
            return $this->error('Create Expenditure Failed!', [ 'message' => $validator->errors()], 400);       
        }

        $uuid = Str::uuid();
        $user_outlet = Auth::user()->outlet_id;
        $nominal = $request->jumlah * $request->harga;
        $insert = Operasional::create([
            'id' => $uuid,
            'nominal' => $nominal,
            'keterangan' => $request->keterangan,
            'satuan' => $request->satuan,
            'item_name' => $request->item_name,
            'jumlah' => $request->jumlah,
            'harga' => $request->harga,
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

    public function pemasukan(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'harga' => 'required|integer',
            'item_name' => 'required|string',
            'jumlah' => 'required|integer',
            'keterangan' => 'string',
            'satuan' => 'string',
        ]);

        if($validator->fails()){
            return $this->error('Create Expenditure Failed!', [ 'message' => $validator->errors()], 400);       
        }

        $uuid = Str::uuid();
        $user_outlet = Auth::user()->outlet_id;
        $nominal = $request->jumlah * $request->harga;
        $insert = Operasional::create([
            'id' => $uuid,
            'nominal' => $nominal,
            'keterangan' => $request->keterangan,
            'satuan' => $request->satuan,
            'item_name' => $request->item_name,
            'jumlah' => $request->jumlah,
            'harga' => $request->harga,
            'jenis' => 'PEMASUKAN',
            'kasir' => Auth::user()->username,
            'outletid' => $user_outlet, 
        ]);

        if($insert){
            return $this->success('Success!',"successfully created data!");
        }else{
            return $this->error('Failed!', [ 'message' => 'created data failed!'], 400);
        }
    }
}
