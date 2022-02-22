<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\AssetProvinsi;
use App\Models\AssetKecamatan;
use App\Models\AssetKabupaten_kota;
use App\Models\AssetKelurahan;
use App\Models\AssetStatus;
use App\Traits\ApiResponser;
use App\Imports\ImportPelanggan;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class AssetController extends Controller
{
    
    use ApiResponser;
    public function provinsi(Request $request)
    {
        $response = Http::get($request->url);
        // dd($response);
        $provinsi = json_decode($response->body(), true);
        $provinsi = $provinsi['provinsi'];
        foreach ($provinsi as $data) {
            $data_provinsi = AssetProvinsi::create([
                'id' => $data['id'],
                'nama' => $data['nama'],
            ]);
        }
        return $this->success('Success!');
    }

    public function kabupaten(Request $request)
    {
        $data_provinsi = AssetProvinsi::select('id')->get();
        foreach ($data_provinsi as $id) {
            # code...
            $response = Http::get($request->url.$id->id);
            $kabupaten = json_decode($response->body(), true);
            // dd($request->url.$id->id);
            $kabupaten = $kabupaten['kota_kabupaten'];
            foreach ($kabupaten as $data) {
                $data_kabupaten = AssetKabupaten_kota::create([
                    'id' => $data['id'],
                    'nama' => $data['nama'],
                    'id_provinsi' => $data['id_provinsi'],
                ]);
            }
        }
        return $this->success('Success!');
    }
    
    public function kecamatan(Request $request)
    {
        ini_set('max_execution_time', 4000);
        $data_provinsi = AssetKabupaten_kota::select('id')->get();
        foreach ($data_provinsi as $id) {
            # code...
            $response = Http::get($request->url.$id->id);
            $kecamatan = json_decode($response->body(), true);
            // dd($request->url.$id->id);
            $kecamatan = $kecamatan['kecamatan'];
            foreach ($kecamatan as $data) {
                $kecamatan = AssetKecamatan::create([
                    'id' => $data['id'],
                    'nama' => $data['nama'],
                    'id_kota' => $data['id_kota'],
                ]);
            }
        }
        return $this->success('Success!');
    }
    
    public function kelurahan(Request $request)
    {
        ini_set('max_execution_time', 4000);
        $data_kecamatan = AssetKecamatan::select('id')->get();
        foreach ($data_kecamatan as $id) {
            # code...
            $response = Http::get($request->url.$id->id);
            $kelurahan = json_decode($response->body(), true);
            // dd($request->url.$id->id);
            $kelurahan = $kelurahan['kelurahan'];
            foreach ($kelurahan as $data) {
                $kelurahan = AssetKelurahan::create([
                    'id' => $data['id'],
                    'nama' => $data['nama'],
                    'id_kecamatan' => $data['id_kecamatan'],
                ]);
            }
        }
        return $this->success('Success!');
    }

    public function get_provinsi()
    {
        $data_provinsi = AssetProvinsi::all();

        if($data_provinsi){
            return $this->success('Success!', $data_provinsi);
        }else{
            return $this->error('Failed!', [ 'message' => $data_provinsi->errors()], 400);
        }
    }
    
    public function get_kabupaten($id)
    {
        $data_kabupaten = AssetKabupaten_kota::where('id_provinsi', $id)->get();

        if($data_kabupaten){
            return $this->success('Success!', $data_kabupaten);
        }else{
            return $this->error('Failed!', [ 'message' => $data_kabupaten->errors()], 400);
        }
    }
    
    public function get_kecamatan($id)
    {
        $data_kecamatan = AssetKecamatan::where('id_kota', $id)->get();

        if($data_kecamatan){
            return $this->success('Success!', $data_kecamatan);
        }else{
            return $this->error('Failed!', [ 'message' => $data_kecamatan->errors()], 400);
        }
    }
    
    public function get_kelurahan($id)
    {
        $data_kelurahan = AssetKelurahan::where('id_kecamatan', $id)->get();

        if($data_kelurahan){
            return $this->success('Success!', $data_kelurahan);
        }else{
            return $this->error('Failed!', [ 'message' => $data_kelurahan->errors()], 400);
        }
    }

    public function status_pesanan()
    {
        $status = AssetStatus::where('type', 'pesanan')->get();
        if($status){
            return $this->success('Success!', $status);
        }else{
            return $this->error('Failed!', [ 'message' => $status->errors()], 400);
        }
    }
  
    public function status_pembayaran()
    {
        $status = AssetStatus::where('type', 'pembayaran')->get();
        if($status){
            return $this->success('Success!', $status);
        }else{
            return $this->error('Failed!', [ 'message' => $status->errors()], 400);
        }
    }

    public function importPelanggan(Request $request)
    {
        $validate = [
            'file' => 'required|mimes:xls,xlsx',
        ];
        $validator = Validator::make($request->all(),$validate);
        if($validator->fails()){
            return $this->error('Failed!', [ 'message' => $validator->errors()], 400);       
        }
        Excel::import(new ImportPelanggan,request()->file('file'));
        return $this->success('Success!');
    }
}
