<?php

namespace App\Http\Controllers\API\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponser;
use Illuminate\Support\Str;
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
        if($request->idpelanggan){
            $validate = [
                'idpelanggan' => 'required|string',
            ];
        }
        if($request->nama_pelanggan){
            $validate = [
                'nama_pelanggan' => 'required|string',
            ];
        }
        if($request->note){
            $validate = [
                'note' => 'required|string',
            ];
        }
        if($request->whatsapp){
            $validate = [
                'whatsapp' => 'required|string',
            ];
        }
        if($request->alamat){
            $validate = [
                'alamat' => 'required|string',
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
       
        $validate = [
            'idwaktu' => 'required|string',
            'jumlah' => 'required|integer',
            'idlayanan' => 'required|string',
            'status_pembayaran' => 'required|string',
            'tagihan' => 'required|integer',
            'subtotal' => 'required|integer',
            'bayar' => 'required|integer',
            'jenis_layanan' => 'required|string',
        ];
        $validator = Validator::make($request->all(),$validate);
        // dd($validator);
        if($validator->fails()){
            return $this->error('Failed!', [ 'message' => $validator->errors()], 400);       
        }

        // dd($request);
        if($request->jenis_layanan == 'kiloan'){
            $datapesanan = DB::table('waktus')
            ->join('kiloans', 'waktus.id', '=', 'kiloans.idwaktu')
            ->select('waktus.id', 'waktus.waktu', 'waktus.paket', 'kiloans.nama_layanan', 'kiloans.jenis', 'kiloans.item')->where('kiloans.idwaktu', '=', $request->idlayanan)
            ->get();
        }
        
        if($request->jenis_layanan == 'satuan'){
            $datapesanan = DB::table('waktus')
            ->join('satuans', 'waktus.id', '=', 'satuans.idwaktu')
            ->select('waktus.id', 'waktus.waktu', 'waktus.paket', 'satuans.nama_layanan', 'satuans.kategori', 'satuans.jenis', 'satuans.item')->where('satuans.idwaktu', '=', $request->idlayanan)
            ->get();
            
            $insert = [
                'kategori' => $datapesanan->kategori ? $datapesanan->kategori : null,
            ];

        }
        $datapesanan = $datapesanan[0];
        $deadline = Carbon::now()->addHours($datapesanan->waktu)->format('Y M d H:i:s'); 
        // $current = Carbon::now()->addHours(48); 
        // $current = Carbon::create($current);

        $uuid = Str::uuid();
        $insert = [
            'id' => $uuid,
            'nama_pelanggan' => $request->nama_pelanggan,
            'whatsapp' => $request->whatsapp,
            'note' => $request->note,
            'deadline' => $deadline,
            'nota_transaksi' => $request->nota_transaksi,
            'jumlah' => $request->jumlah,
            'layanan' => $datapesanan->nama_layanan,
            'jenis_layanan' => $datapesanan->jenis,
            'paket' => $datapesanan->paket,
            'status' => 'antrian',
            'outletid' => Auth::user()['outlet_id'],
            'kasir' => Auth::user()['username'],

            
        ];
        $insertPembayaran = [
            'idpesanan' => $uuid,
            'subtotal' => $request->subtotal,
            'diskon' => $request->diskon,
            'tagihan' => $request->tagihan,
            'bayar' => $request->bayar,
            'status' => $request->status_pembayaran ? $request->status_pembayaran : 'Belum Bayar',
            'metode_pembayaran' => $request->metode_pembayaran ? $request->metode_pembayaran : 'Cash',
        ];

        return $this->success('Success!', [$insert, $insertPembayaran]);


    }
}