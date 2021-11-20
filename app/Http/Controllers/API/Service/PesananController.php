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
       
        $validate = [
            'idwaktu' => 'required|string',
            'jumlah' => 'required|integer',
            'idlayanan' => 'required|string',
            'status_pembayaran' => 'required|string',
            'tagihan' => 'required|integer',
            'subtotal' => 'required|integer',
            'bayar' => 'required|integer',
            'jenis_layanan' => 'required|string',
            'idpelanggan' => 'required|string'
        ];
        $validator = Validator::make($request->all(),$validate);

        if($validator->fails()){
            return $this->error('Failed!', [ 'message' => $validator->errors()], 400);       
        }

        $insert = [];
        // dd($request);
        if($request->jenis_layanan == 'kiloan'){
            try {
                $kiloan = DB::table('kiloans')
                ->where('id', $request->idlayanan)
                ->where('idwaktu', $request->idwaktu)
                ->where('idoutlet', Auth::user()['outlet_id'])
                ->first();
        

                $inputLayanan = $kiloan->nama_layanan;
                $inputjenisLayanan = $kiloan->jenis;
                
                $inputHarga = $kiloan->harga;

            } catch (Throwable $th) {
                report($th);
                return $this->error('Failed!', [ 'message' => "data not found!, check again"], 400);       
            }
        }
        
        if($request->jenis_layanan == 'satuan'){
            try {
                $satuan = DB::table('satuans')
                    ->where('idwaktu', $request->idwaktu)
                    ->where('id', $request->idlayanan)
                    ->where('idoutlet', Auth::user()['outlet_id'])
                    ->first();

                $input = Arr::add($insert, 'kategori' ,$satuan->kategori);

                $inputLayanan = $satuan->nama_layanan;
                $inputjenisLayanan = $satuan->jenis;
                
                $inputHarga = $satuan->harga;
            } catch (Throwable $th) {
                report($th);

                return $this->error('Failed!', [ 'message' => "data not found!, check again"], 400);       
            }
        }

        // get data waktu
        $waktu = DB::table('waktus')
            ->where('id', $request->idwaktu)
            ->where('idoutlet', Auth::user()['outlet_id'])
            ->first();

        $deadline = Carbon::now()->addHours($waktu->waktu); 

        $nota = IdGenerator::generate(['table' => 'pesanans', 'length' => 20, 'prefix' => $waktu->paket . "" . date('Ymd')]);
        $uuid = Str::uuid();
        $insert = [
            'id' => $uuid,
            'idpelanggan' => $request->idpelanggan,
            'note' => $request->note,
            'deadline' => $deadline,
            'nota_transaksi' => $nota,
            'jumlah' => $request->jumlah,
            'paket' => $waktu->paket,
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

        $insert = Arr::add($insert, 'layanan' ,$inputLayanan);
        $insert = Arr::add($insert, 'jenis_layanan' ,$inputjenisLayanan);
        
        $insertPembayaran = Arr::add($insertPembayaran, 'harga' ,$inputHarga);
        
        try {
            Pesanan::create($insert);
            Pembayaran::create($insertPembayaran);
            return $this->success('Success!', [$nota, $insert, $insertPembayaran]);
        } catch (Throwable $th) {
            report($th);

            return $this->error('Failed!', [ 'message' => $th], 400);       
        }

    }
}