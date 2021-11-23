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
            'status' => $request->status_pembayaran ? $request->status_pembayaran : 'Belum Bayar',
            'metode_pembayaran' => $request->metode_pembayaran ? $request->metode_pembayaran : 'Cash',
        ];

        try {
            Pesanan::create($insert);
            Pembayaran::create($insertPembayaran);
            return $this->success('Success!', [$nota, $insert, $insertPembayaran]);
        } catch (Throwable $th) {
            report($th);

            return $this->error('Failed!', [ 'message' => $th], 400);       
        }

    }

    // public function create(Request $request)
    // {
    //     if($request->note){
    //         $validate = [
    //             'note' => 'required|string',
    //         ];
    //     }
    //     if($request->metode_pembayaran){
    //         $validate = [
    //             'metode_pembayaran' => 'required|string',
    //         ];
    //     }
    //     if($request->diskon){
    //         $validate = [
    //             'diskon' => 'required|string',
    //         ];
    //     }
    //     if($request->diskon){
    //         $validate = [
    //             'utang' => 'required|string',
    //         ];
    //     }
       
    //     $validate = [
    //         'idwaktu' => 'required|string',
    //         'jumlah' => 'required|integer',
    //         'idlayanan' => 'required|string',
    //         'status_pembayaran' => 'required|string',
    //         'tagihan' => 'required|integer',
    //         'subtotal' => 'required|integer',
    //         'bayar' => 'required|integer',
    //         'jenis_layanan' => 'required|string',
    //         'idpelanggan' => 'required|string'
    //     ];
    //     $validator = Validator::make($request->all(),$validate);

    //     if($validator->fails()){
    //         return $this->error('Failed!', [ 'message' => $validator->errors()], 400);       
    //     }

    //     $insert = [];
    //     // dd($request);
    //     if($request->jenis_layanan == 'kiloan'){
    //         try {
    //             $kiloan = DB::table('kiloans')
    //             ->where('id', $request->idlayanan)
    //             ->where('idwaktu', $request->idwaktu)
    //             ->where('idoutlet', Auth::user()['outlet_id'])
    //             ->first();
        

    //             $inputLayanan = $kiloan->nama_layanan;
    //             $inputitem = $kiloan->item;
    //             $inputjenisLayanan = $kiloan->jenis;
                
    //             $inputHarga = $kiloan->harga;

    //         } catch (Throwable $th) {
    //             report($th);
    //             return $this->error('Failed!', [ 'message' => "data not found!, check again"], 400);       
    //         }
    //     }
        
    //     if($request->jenis_layanan == 'satuan'){
    //         try {
    //             $satuan = DB::table('satuans')
    //                 ->where('idwaktu', $request->idwaktu)
    //                 ->where('id', $request->idlayanan)
    //                 ->where('idoutlet', Auth::user()['outlet_id'])
    //                 ->first();

    //             $input = Arr::add($insert, 'kategori' ,$satuan->kategori);

    //             $inputLayanan = $satuan->nama_layanan;
    //             $inputitem = $satuan->item;
    //             $inputjenisLayanan = $satuan->jenis;
                
    //             $inputHarga = $satuan->harga;
    //         } catch (Throwable $th) {
    //             report($th);

    //             return $this->error('Failed!', [ 'message' => "data not found!, check again"], 400);       
    //         }
    //     }

    //     // get data waktu
    //     $waktu = DB::table('waktus')
    //         ->where('id', $request->idwaktu)
    //         ->where('idoutlet', Auth::user()['outlet_id'])
    //         ->first();

    //     $deadline = Carbon::now()->addHours($waktu->waktu); 

    //     $nota = IdGenerator::generate(['table' => 'pesanans', 'length' => 20, 'prefix' => $waktu->paket . "" . date('Ymd')]);
    //     $uuid = Str::uuid();
    //     $insert = [
    //         'id' => $uuid,
    //         'idpelanggan' => $request->idpelanggan,
    //         'note' => $request->note,
    //         'deadline' => $deadline,
    //         'nota_transaksi' => $nota,
    //         'jumlah' => $request->jumlah,
    //         'paket' => $waktu->paket,
    //         'status' => 'antrian',
    //         'outletid' => Auth::user()['outlet_id'],
    //         'kasir' => Auth::user()['username'],
    //     ];
    //     $insertPembayaran = [
    //         'idpesanan' => $uuid,
    //         'subtotal' => $request->subtotal,
    //         'tagihan' => $request->tagihan,
    //         'utang' => $request->utang,
    //         'diskon' => $request->diskon,
    //         'bayar' => $request->bayar,
    //         'status' => $request->status_pembayaran ? $request->status_pembayaran : 'Belum Bayar',
    //         'metode_pembayaran' => $request->metode_pembayaran ? $request->metode_pembayaran : 'Cash',
    //     ];

    //     $insert = Arr::add($insert, 'layanan' ,$inputLayanan);
    //     $insert = Arr::add($insert, 'item' ,$inputitem);
    //     $insert = Arr::add($insert, 'jenis_layanan' ,$inputjenisLayanan);
        
    //     $insertPembayaran = Arr::add($insertPembayaran, 'harga' ,$inputHarga);
        
    //     try {
    //         Pesanan::create($insert);
    //         Pembayaran::create($insertPembayaran);
    //         return $this->success('Success!', [$nota, $insert, $insertPembayaran]);
    //     } catch (Throwable $th) {
    //         report($th);

    //         return $this->error('Failed!', [ 'message' => $th], 400);       
    //     }

    // }

    public function getPesanan($outletid, $status)
    {
        $pesanan = DB::table('pesanans')
            ->leftJoin('pelanggans', 'pesanans.idpelanggan', '=', 'pelanggans.id')
            ->leftJoin('outlets', 'pesanans.outletid', '=', 'outlets.id')
            ->leftJoin('services', 'pesanans.idlayanan', '=', 'services.id')
            ->rightJoin('pembayarans', 'pesanans.id', '=', 'pembayarans.idpesanan')
            ->where('pesanans.outletid', $outletid)
            ->where('pesanans.status', $status)
            ->get();
        
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

    public function updatestatus($id, Request $request)
    {
        $validator = Validator::make($request->all(),[
            'status' => 'required|string'
        ]);
        
        if($validator->fails()){
            return $this->error('Failed!', [ 'message' => $validator->errors()], 400);       
        }
        $update = Pesanan::where('id', $id)->update($request->all());
        
        if($update){
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
            // ->where('pesanans.status', 'SELESAI')
            ->select('pelanggans.nama', 'pesanans.nota_transaksi', 'pembayarans.status', 'waktus.paket', 'services.nama_layanan')
            ->get();
        if($pesanan){
            return $this->success('Success!', $pesanan);
        }else{
            return $this->error('Failed!', [ 'message' => 'Data Not Available!'], 400);
        }
    }
}