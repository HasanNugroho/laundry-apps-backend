<?php

namespace App\Http\Controllers\API\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponser;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Models\Operasional;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Kiloan;
use App\Models\Satuan;
use App\Models\Waktu;
use Carbon\Carbon;
use Validator;

class PesananController extends Controller
{
    use ApiResponser;
    public function create(Request $request)
    {
        if($request->note){
            $validate = [
                'note' => 'required|string',
            ];
        }
        if($request->metode_pembayaran){
            $validate = [
                'metode_pembayaran' => 'required|string',
            ];
        }
        if($request->diskon){
            $validate = [
                'diskon' => 'required|string',
            ];
        }
        if($request->diskon){
            $validate = [
                'utang' => 'required|string',
            ];
        }
       
        $validate = [
            'idwaktu' => 'required|string',
            'jumlah' => 'required|integer',
            'idlayanan' => 'required|string',
            'status_pembayaran' => 'required|string',
            'tagihan' => 'required|integer',
            'subtotal' => 'required|integer',
            'bayar' => 'required|integer',
            'idpelanggan' => 'required|string'
        ];
        $validator = Validator::make($request->all(),$validate);

        if($validator->fails()){
            return $this->error('Failed!', [ 'message' => $validator->errors()], 400);       
        }

        // get data waktu
        // dd(Auth::user()['outlet_id']);
        $waktu = DB::table('waktus')
            ->where('id', $request->idwaktu)
            ->where('idoutlet', Auth::user()['outlet_id'])
            ->first();
        if($waktu){
            $deadline = Carbon::now()->addHours($waktu->waktu); 
        }else{
            return $this->error('Failed!', [ 'message' => 'Forbidden!'], 403);       
        }

        // dd($deadline->format('Y-m-d H:i:s'));
        $nota = IdGenerator::generate(['table' => 'pesanans', 'length' => 20, 'prefix' => $waktu->paket . "" . date('Ymd'). Str::random(7)]);
        $uuid = Str::uuid();

        if (Waktu::where('id', '=', $uuid)->exists()) {
            return $this->error('Failed!', [ 'message' => 'Data exists'], 400);       
        }
        if (Pesanan::where('nota_transaksi', '=', $nota)->exists()) {
            return $this->error('Failed!', [ 'message' => 'Data exists'], 400);       
        }

        $insert = [
            'id' => $uuid,
            'idpelanggan' => $request->idpelanggan,
            'note' => $request->note,
            'idwaktu' => $request->idwaktu,
            'idlayanan' => $request->idlayanan,
            'deadline' => $deadline->format('Y-m-d H:i:s'),
            'nota_transaksi' => $nota,
            'jumlah' => $request->jumlah,
            // 'jenis_layanan' => $request->jenis_layanan,
            'status' => 'ANTRIAN',
            'outletid' => Auth::user()['outlet_id'],
            'kasir' => Auth::user()['username'],
        ];
        $insertPembayaran = [
            'idpesanan' => $uuid,
            'subtotal' => $request->subtotal,
            'tagihan' => $request->tagihan,
            'utang' => $request->utang,
            'diskon' => $request->diskon,
            'bayar' => $request->bayar,
            'status' => $request->status_pembayaran ? Str::upper($request->status_pembayaran) : 'BELUM BAYAR',
            'metode_pembayaran' => $request->metode_pembayaran ? $request->metode_pembayaran : 'Cash',
        ];

        try {
            if(Pesanan::create($insert) && Pembayaran::create($insertPembayaran)){
                $pesanan = DB::table('pesanans')
                ->leftJoin('pelanggans', 'pesanans.idpelanggan', '=', 'pelanggans.id')
                ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
                ->leftJoin('services', 'pesanans.idlayanan', '=', 'services.id')
                ->leftJoin('waktus', 'pesanans.idwaktu', '=', 'waktus.id')
                ->rightJoin('pembayarans', 'pesanans.id', '=', 'pembayarans.idpesanan')
                ->where('pesanans.id', $uuid)
                ->select('pesanans.*', 'pelanggans.nama', 'pelanggans.whatsapp', 'pelanggans.alamat', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.sosial_media', 'services.nama_layanan', 'services.harga', 'services.kategori', 'services.jenis', 'services.item', 'pembayarans.status', 'pembayarans.metode_pembayaran', 'pembayarans.subtotal', 'pembayarans.diskon', 'pembayarans.utang', 'pembayarans.tagihan', 'pembayarans.bayar', 'waktus.nama as nama_waktu', 'waktus.waktu as durasi', 'waktus.paket as paket_waktu', 'waktus.jenis as jenis_waktu')
                ->get();
                
                return $this->success('Success!', [$nota, $pesanan]);
            }else{
                return $this->error('Failed!', 'data not insert!', 400);       
            }
        } catch (Throwable $th) {
            report($th);
            return $this->error('Failed!', [ 'message' => $th], 400);       
        }

    }

    public function getPesanan($outletid, $status)
    {
        // DB::enableQueryLog(); // Enable query log

        $pesanan = DB::table('pesanans')
            ->leftJoin('pelanggans', 'pesanans.idpelanggan', '=', 'pelanggans.id')
            ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
            ->leftJoin('services', 'pesanans.idlayanan', '=', 'services.id')
            ->leftJoin('waktus', 'pesanans.idwaktu', '=', 'waktus.id')
            ->rightJoin('pembayarans', 'pesanans.id', '=', 'pembayarans.idpesanan')
            ->where('pesanans.outletid', $outletid)
            ->where('pesanans.status', $status)
            ->select('pesanans.*', 'pelanggans.nama', 'pelanggans.whatsapp', 'pelanggans.alamat', 'outlets.nama_outlet', 'outlets.status_outlet', 'outlets.sosial_media', 'services.nama_layanan', 'services.harga', 'services.kategori', 'services.jenis', 'services.item', 'pembayarans.status', 'pembayarans.metode_pembayaran', 'pembayarans.subtotal', 'pembayarans.diskon', 'pembayarans.utang', 'pembayarans.tagihan', 'pembayarans.bayar', 'waktus.nama as nama_waktu', 'waktus.waktu as durasi', 'waktus.paket as paket_waktu', 'waktus.jenis as jenis_waktu')
            ->get();
        // dd(DB::getQueryLog()); // Show results of log

        
        if($pesanan){
            return $this->success('Success!', $pesanan);
        }else{
            return $this->error('Failed!', [ 'message' => 'Data Not Found'], 404);
        }
    }
    
    public function getPesanandetail($nota)
    {
        $pesanan = DB::table('pesanans')
            ->leftJoin('pelanggans', 'pesanans.idpelanggan', '=', 'pelanggans.id')
            ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
            ->rightJoin('pembayarans', 'pesanans.id', '=', 'pembayarans.idpesanan')
            ->where('pesanans.nota_transaksi', $nota)
            ->get();
        
        if($pesanan){
            return $this->success('Success!', $pesanan);
        }else{
            return $this->error('Failed!', [ 'message' => 'Data Not Found'], 404);
        }
    }

    public function updatestatuspesanan($id, Request $request)
    {
        $validator = Validator::make($request->all(),[
            'status' => 'required|string'
        ]);
        
        if($validator->fails()){
            return $this->error('Failed!', [ 'message' => $validator->errors()], 400);       
        }
        $update = Pesanan::where('id', $id)->update(['status' => $request->status]);
        if($update){
            $insert_pemasukan = DB::table('pesanans')
            ->leftJoin('services', 'pesanans.idlayanan', '=', 'services.id')
            ->rightJoin('pembayarans', 'pesanans.id', '=', 'pembayarans.idpesanan')
            ->select('pesanans.outletid', 'pesanans.jumlah', 'services.nama_layanan', 'services.item', 'pembayarans.tagihan')
            ->where('pesanans.id', $id)
            ->where(DB::raw('upper(pembayarans.status)'), 'LUNAS')
            ->get();
            print($insert_pemasukan);
            $uuid = Str::uuid();
            if(strtoupper($request->status) == 'SELESAI' && $insert_pemasukan){
                Operasional::create([
                    'id' => $uuid,
                    'nominal' => $insert_pemasukan[0]->tagihan,
                    'keterangan' => $insert_pemasukan[0]->nama_layanan . '-' . $insert_pemasukan[0]->jumlah . '-' . $insert_pemasukan[0]->item,
                    'jenis' => 'PEMASUKAN',
                    'outletid' => $insert_pemasukan[0]->outletid, 
                ]);

            }
            return $this->success('Success!');
        }else{
            return $this->error('Failed!', [ 'message' => 'Update Data Failed!'], 400);
        }
    }
    
    public function updatestatuspembayaran($id, Request $request)
    {
        $validator = Validator::make($request->all(),[
            'status' => 'required|string'
        ]);
        
        if($validator->fails()){
            return $this->error('Failed!', [ 'message' => $validator->errors()], 400);       
        }
        $updatePembayaran = ['status' => $request->status, 'utang' => 0];
        // dd($updatePembayaran);
        $update = Pembayaran::where('idpesanan', $id)->update($updatePembayaran);
        if($update){
            $uuid = Str::uuid();
            $insert_pemasukan = DB::table('pesanans')
            ->leftJoin('services', 'pesanans.idlayanan', '=', 'services.id')
            ->rightJoin('pembayarans', 'pesanans.id', '=', 'pembayarans.idpesanan')
            ->select('pesanans.outletid', 'pesanans.jumlah', 'services.nama_layanan', 'services.item', 'pembayarans.tagihan')
            ->where('pesanans.id', $id)
            ->where(DB::raw('upper(pembayarans.status)'), 'LUNAS')
            ->get();
            if(strtoupper($request->status) == 'LUNAS' && !isset($insert_pemasukan)){
                Operasional::create([
                    'id' => $uuid,
                    'nominal' => $insert_pemasukan[0]->tagihan,
                    'keterangan' => $insert_pemasukan[0]->nama_layanan . '-' . $insert_pemasukan[0]->jumlah . '-' . $insert_pemasukan[0]->item,
                    'jenis' => 'PEMASUKAN',
                    'outletid' => $insert_pemasukan[0]->outletid, 
                ]);
            }
            return $this->success('Success!');
        }else{
            return $this->error('Failed!', [ 'message' => 'Update Data Failed!'], 400);
        }
    }

    public function riwayat()
    {
        // $pesanan = Pesanan::select()->whereDate('created_at', Carbon::today())
        // ->where('outletid', Auth::user()['outlet_id'])
        // ->where('status', 'SELESAI')
        // ->get();

        $pesanan = DB::table('pesanans')
            ->leftJoin('pelanggans', 'pesanans.idpelanggan', '=', 'pelanggans.id')
            ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
            ->leftJoin('services', 'pesanans.idlayanan', '=', 'services.id')
            ->leftJoin('waktus', 'pesanans.idwaktu', '=', 'waktus.id')
            ->rightJoin('pembayarans', 'pesanans.id', '=', 'pembayarans.idpesanan')
            ->whereDate('pesanans.updated_at', Carbon::today())
            ->where('pesanans.outletid', Auth::user()['outlet_id'])
            ->where('pesanans.status', 'SELESAI')
            ->select('pelanggans.nama', 'pesanans.nota_transaksi', 'pembayarans.status', 'waktus.paket', 'services.nama_layanan', 'outlets.nama_outlet', 'outlets.status_outlet')
            ->get();
        if($pesanan){
            return $this->success('Success!', $pesanan);
        }else{
            return $this->error('Failed!', [ 'message' => 'Data Not Available!'], 400);
        }
    }

    public function riwayatAdmin(Request $request)
    {
        $user_outlet = Auth::user()->outlet_id;
        // $pesanan = Pesanan::select()->whereDate('created_at', Carbon::today())
        // ->where('outletid', Auth::user()['outlet_id'])
        // ->where('status', 'SELESAI')
        // ->get();

        $pesanan = DB::table('pesanans')
            ->leftJoin('pelanggans', 'pesanans.idpelanggan', '=', 'pelanggans.id')
            ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
            ->leftJoin('services', 'pesanans.idlayanan', '=', 'services.id')
            ->leftJoin('waktus', 'pesanans.idwaktu', '=', 'waktus.id')
            ->rightJoin('pembayarans', 'pesanans.id', '=', 'pembayarans.idpesanan')
            ->where('pesanans.status', 'SELESAI')
            ->where('outlets.id', $user_outlet)
            ->orWhere('outlets.parent', $user_outlet)
            ->select('pelanggans.nama', 'pesanans.nota_transaksi', 'pembayarans.status', 'waktus.paket', 'services.nama_layanan', 'outlets.nama_outlet', 'outlets.status_outlet')
            ->get();
        if($pesanan){
            return $this->success('Success!', $pesanan);
        }else{
            return $this->error('Failed!', [ 'message' => 'Data Not Available!'], 400);
        }
    }
}