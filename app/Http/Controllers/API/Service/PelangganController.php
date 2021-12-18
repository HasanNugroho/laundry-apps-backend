<?php

namespace App\Http\Controllers\API\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Pelanggan;
use Validator;

class PelangganController extends Controller
{
    use ApiResponser;
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'whatsapp' => 'required'
        ]);

        if($validator->fails()){
            return $this->error('Failed!', [ 'message' => $validator->errors()], 400);       
        }

        if (Pelanggan::where('outletid', Auth::user()['outlet_id'])->where('nama', '=', $request->nama)->exists()) {
            return $this->error('Failed!', [ 'message' => 'Data exists'], 400);       
        }
        
        $uuid = Str::uuid();
        $pelanggan = Pelanggan::create([
            'id' => $uuid,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'whatsapp' => $request->whatsapp,
            'outletid' => Auth::user()['outlet_id'],
        ]);

        if($pelanggan){
            return $this->success('Success!', "successfully added data!");
        }else{
            return $this->error('Failed!', [ 'message' => $pelanggan->errors()], 400);       
        }
    }

    public function show()
    {
        $data_pelanggan = Pelanggan::select('id', 'nama', 'whatsapp', 'alamat')->where('outletid', Auth::user()['outlet_id'])->get();
        
        if($data_pelanggan){
            return $this->success('Success!',$data_pelanggan);
        }else{
            return $this->error('Failed!', [ 'message' => $data_pelanggan->errors()], 400);       
        }

    }

    public function showadmin()
    {
        $outletid = Auth::user()['outlet_id'];
        // $waktu = Waktu::all();
        $data_pelanggan = DB::select('select pe.id, pe.nama, pe.whatsapp, pe.alamat, pe.created_at as date_join from pelanggans pe left join outlets o on pe.outletid = o.id where o.id = ? or parent = ?', [$outletid, $outletid]);

        return $this->success('Success!', $data_pelanggan);
    }
    
    public function showbyid($id)
    {
        $data_pelanggan = Pelanggan::where('id', $id)->first();
        
        if($data_pelanggan){
            return $this->success('Success!',$data_pelanggan);
        }else{
            return $this->error('Failed!', [ 'message' => $data_pelanggan->errors()], 400);       
        }
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama' => 'string|max:255',
            'alamat' => 'string',
            'whatsapp' => 'string'
        ]);

        if($validator->fails()){
            return $this->error('Failed!', [ 'message' => $validator->errors()], 400);       
        }

        if($request->all()){
            $pelanggan = Pelanggan::find($id)->update($request->all());
        }else{
            return $this->error('Failed!', [ 'message' => 'no data to update!'], 404);       
        }

        if($pelanggan){
            return $this->success('Success!', "successfully updated data!");
        }else{
            return $this->error('Failed!', [ 'message' => $pelanggan->errors()], 400);       
        }
    }

    public function delete($id)
    {
        $delete = Pelanggan::find($id)->delete();
        
        if($delete){
            return $this->success('Success!', "successfully deleted data!");
        }else{
            return $this->error('Failed!', [ 'message' => $delete->errors()], 400);       
        }
    }
}
