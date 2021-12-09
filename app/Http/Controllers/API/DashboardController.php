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

    public function nominalutangOwner()
    {
        $user_outlet = Auth::user()->outlet_id;
        $utang = DB::table('pembayarans')->where(DB::raw('upper(pembayarans.status)'), 'UTANG')
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

    public function pendapatanOwner()
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

    public function pengeluaranOwner()
    {
        $user_outlet = Auth::user()->outlet_id;

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
        
        $totalpengeluaran = DB::table('operasionals')
            ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
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
            ->where('operasionals.created_at', '>=', Carbon::now()->subMonth())
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

    public function transaksiOwner()
    {
        $user_outlet = Auth::user()->outlet_id;
        $today = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->whereDate('pesanans.updated_at',Carbon::today())
        ->where(DB::raw('upper(pesanans.status)'), 'SELESAI')
        ->where('outlets.id', $user_outlet)
        ->orWhere('outlets.parent', $user_outlet)
        ->count();

        $yesterday = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->whereDate('pesanans.updated_at', Carbon::yesterday())
        ->where(DB::raw('upper(pesanans.status)'), 'SELESAI')
        ->where('outlets.id', $user_outlet)
        ->orWhere('outlets.parent', $user_outlet)
        ->count();
        
        $current_week = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->whereBetween('pesanans.updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        ->where(DB::raw('upper(pesanans.status)'), 'SELESAI')
        ->where('outlets.id', $user_outlet)
        ->orWhere('outlets.parent', $user_outlet)
        ->count();

        $thismouth = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->whereMonth('pesanans.updated_at', Carbon::now()->format('m'))
        ->whereYear('pesanans.updated_at', date('Y'))
        ->where(DB::raw('upper(pesanans.status)'), 'SELESAI')
        ->where('outlets.id', $user_outlet)
        ->orWhere('outlets.parent', $user_outlet)
        ->count();

        $lastmouth = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->whereMonth('pesanans.updated_at', Carbon::now()->subMonth()->format('m'))
        ->whereYear('pesanans.updated_at', date('Y'))
        ->where(DB::raw('upper(pesanans.status)'), 'SELESAI')
        ->where('outlets.id', $user_outlet)
        ->orWhere('outlets.parent', $user_outlet)
        ->count();

        $all = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->where(DB::raw('upper(pesanans.status)'), 'SELESAI')
        ->where('outlets.id', $user_outlet)
        ->orWhere('outlets.parent', $user_outlet)
        ->count();

        return $this->success('Success!', ['today' => $today, 'yesterday' => $yesterday, 'current_week' => $current_week, 'thismouth' => $thismouth, 'lastmouth' => $lastmouth, 'total' => $all]);
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

    public function countTransaksiAdmin()
    {
        $user_outlet = Auth::user()->outlet_id;
        $selesai = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->where(DB::raw('upper(pesanans.status)'), 'SELESAI')
        ->where('outlets.id', $user_outlet)
        ->orWhere('outlets.parent', $user_outlet)
        ->count();

        $proses = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->where(DB::raw('upper(pesanans.status)'), 'PROSES')
        ->where('outlets.id', $user_outlet)
        ->orWhere('outlets.parent', $user_outlet)
        ->count();
        
        $antrian = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->where(DB::raw('upper(pesanans.status)'), 'ANTRIAN')
        ->where('outlets.id', $user_outlet)
        ->orWhere('outlets.parent', $user_outlet)
        ->count();

        $dibatalkan = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
        ->where(DB::raw('upper(pesanans.status)'), 'DIBATALKAN')
        ->where('outlets.id', $user_outlet)
        ->orWhere('outlets.parent', $user_outlet)
        ->count();

        $all = DB::table('pesanans')
        ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
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

    public function countTransaksiOwner()
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

    public function daftarKasirOwner()
    {
        $user_outlet = Auth::user()->outlet_id;
        $users = DB::table('users')
        ->leftJoin('outlets', 'users.outlet_id', '=', 'outlets.id')
        ->where('outlets.id', $user_outlet)
        ->orWhere('outlets.parent', $user_outlet)
        ->select('users.uid','users.username', 'users.email', 'users.role', 'users.alamat', 'users.whatsapp', 'users.status', 'users.created_at as date_join', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.alamat')
        ->get();

        return $this->success('Success!', $users);
    }
    
    public function operasionalOwner()
    {
        $user_outlet = Auth::user()->outlet_id;
        $operasional = DB::table('operasionals')
        ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
        ->where('outlets.id', $user_outlet)
        ->orWhere('outlets.parent', $user_outlet)
        ->select('operasionals.*', 'outlets.nama_outlet')
        ->get();
        return $this->success('Success!', $operasional);
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
        
        if($request->search == 'pelanggan'){DB::enableQueryLog();
            $user_outlet = Auth::user()->outlet_id;
            $search = DB::table('pelanggans')
            ->leftJoin('outlets', 'pelanggans.outletid', '=', 'outlets.id')
            ->where('pelanggans.nama', 'like', '%' . $request->q . '%')
            ->where('outlets.id', $user_outlet)
            ->orWhere('outlets.parent', $user_outlet)
            ->select('pelanggans.nama', 'pelanggans.whatsapp', 'pelanggans.alamat')
            ->get();
        }
        
        if($request->search == 'pesanan'){DB::enableQueryLog();
            $user_outlet = Auth::user()->outlet_id;
            $search = DB::table('pesanans')
            ->leftJoin('pelanggans', 'pesanans.idpelanggan', '=', 'pelanggans.id')
            ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
            ->leftJoin('services', 'pesanans.idlayanan', '=', 'services.id')
            ->leftJoin('waktus', 'pesanans.idwaktu', '=', 'waktus.id')
            ->rightJoin('pembayarans', 'pesanans.id', '=', 'pembayarans.idpesanan')
            ->where(function($query) use($request) {
                $query;
                $query->where('pelanggans.nama', 'like', '%' . $request->q . '%');
                $query->orwhere('pelanggans.whatsapp', 'like', '%' . $request->q . '%');
                // $query->orwhere('services.nama_layanan', 'like', '%' . $request->q . '%');
                // $query->orwhere('pesanans.kasir', 'like', '%' . $request->q . '%');
                $query->orwhere('pesanans.nota_transaksi', 'like', '%' . $request->q . '%');
                // $query->orwhere('waktus.nama', 'like', '%' . $request->q . '%');
                // $query->orwhere('waktus.paket', 'like', '%' . $request->q . '%');
                // $query->orwhere('pembayarans.diskon', 'like', '%' . $request->q . '%');
                // $query->orwhere('outlets.nama_outlet', 'like', '%' . $request->q . '%');
            })
            // ->where('pesanans.status', 'SELESAI')
            ->where('outlets.id', $user_outlet)
            ->orWhere('outlets.parent', $user_outlet)
            ->select('pesanans.*', 'pelanggans.nama', 'pelanggans.whatsapp', 'pelanggans.alamat', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.sosial_media', 'services.nama_layanan', 'services.harga', 'services.kategori', 'services.jenis', 'services.item', 'pembayarans.status', 'pembayarans.metode_pembayaran', 'pembayarans.subtotal', 'pembayarans.diskon', 'pembayarans.utang', 'pembayarans.tagihan', 'pembayarans.bayar', 'waktus.nama as nama_waktu', 'waktus.waktu as durasi', 'waktus.paket as paket_waktu', 'waktus.jenis as jenis_waktu')
            ->get();
        }
        
        if($request->search == 'operasional'){DB::enableQueryLog();
            $user_outlet = Auth::user()->outlet_id;
            $search = DB::table('operasionals')
            ->leftJoin('outlets', 'operasionals.outletid', '=', 'outlets.id')
            ->where(function($query) use($request) {
                $query;
                $query->where('operasionals.keterangan', 'like', '%' . $request->q . '%');
                $query->where('operasionals.jenis', 'like', '%' . $request->jenis . '%');
                $query->orWhere('operasionals.nominal', 'like', '%' . $request->q . '%');
            })
            // ->where('pesanans.status', 'SELESAI')
            ->where('outlets.id', $user_outlet)
            ->orWhere('outlets.parent', $user_outlet)
            ->select('operasionals.*')
            ->get();
        }
        
        return $this->success('Success!', [$search]);
    }

    public function getPesananAdmin(Request $request)
    {
        $user_outlet = Auth::user()->outlet_id;
        if($request->status){
            $pesanan = DB::table('pesanans')
                ->leftJoin('pelanggans', 'pesanans.idpelanggan', '=', 'pelanggans.id')
                ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
                ->leftJoin('services', 'pesanans.idlayanan', '=', 'services.id')
                ->leftJoin('waktus', 'pesanans.idwaktu', '=', 'waktus.id')
                ->rightJoin('pembayarans', 'pesanans.id', '=', 'pembayarans.idpesanan')
                ->where(DB::raw('upper(pesanans.status)'), Str::upper($request->status))
                ->where('outlets.id', $user_outlet)
                ->orWhere('outlets.parent', $user_outlet)
                ->select('pesanans.*', 'pelanggans.nama', 'pelanggans.whatsapp', 'pelanggans.alamat', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.sosial_media', 'services.nama_layanan', 'services.harga', 'services.kategori', 'services.jenis', 'services.item', 'pembayarans.status', 'pembayarans.metode_pembayaran', 'pembayarans.subtotal', 'pembayarans.diskon', 'pembayarans.utang', 'pembayarans.tagihan', 'pembayarans.bayar', 'waktus.nama as nama_waktu', 'waktus.waktu as durasi', 'waktus.paket as paket_waktu', 'waktus.jenis as jenis_waktu')
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
                ->select('pesanans.*', 'pelanggans.nama', 'pelanggans.whatsapp', 'pelanggans.alamat', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.sosial_media', 'services.nama_layanan', 'services.harga', 'services.kategori', 'services.jenis', 'services.item', 'pembayarans.status', 'pembayarans.metode_pembayaran', 'pembayarans.subtotal', 'pembayarans.diskon', 'pembayarans.utang', 'pembayarans.tagihan', 'pembayarans.bayar', 'waktus.nama as nama_waktu', 'waktus.waktu as durasi', 'waktus.paket as paket_waktu', 'waktus.jenis as jenis_waktu')
                ->get();

        }
        // dd(DB::getQueryLog()); // Show results of log

        
        if($pesanan){
            return $this->success('Success!', $pesanan);
        }else{
            return $this->error('Failed!', [ 'message' => 'Data Not Found'], 404);
        }
    }
}
