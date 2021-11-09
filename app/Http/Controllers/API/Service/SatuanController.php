<?php

namespace App\Http\Controllers\API\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponser;
use Illuminate\Support\Str;
use App\Models\Satuan;
use Validator;

class SatuanController extends Controller
{
    use ApiResponser;
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama_layanan' => 'required|string|max:255',
            'harga' => 'required|integer',
            'status' => 'required|boolean',
            'kategori' => 'required|string',
            'item' => 'required|string',
            'idwaktu' => 'string'
        ]);

        if($validator->fails()){
            return $this->error('Failed!', [ 'message' => $validator->errors()], 400);       
        }

        if (Satuan::where('nama_layanan', '=', $request->nama_layanan)->exists()) {
            return $this->error('Failed!', [ 'message' => 'Data exists'], 400);       
        }
        
        $uuid = Str::uuid();
        $waktu = Satuan::create([
            'id' => $uuid,
            'nama_layanan' => $request->nama_layanan,
            'harga' => $request->harga,
            'kategori' => $request->kategori,
            'status' => $request->status,
            'item' => $request->item,
            'idwaktu' => $request->idwaktu,
            'jenis' => 'satuan',
            'idoutlet' => Auth::user()['outlet_id']
        ]);

        return $this->success('Success!',"successfully created data!");
    }

    public function show()
    {
        $waktu = Satuan::where('status', 1)->get();
        return $this->success('Success!', $waktu);
    }

    public function showById($id)
    {
        $waktu = Satuan::where('id', $id)->where('status', 1)->first();
        // dd($waktu);
        if($waktu != null){
            return $this->success('Success!', $waktu);
        }else{
            return $this->error('Failed', null, 404);
        }
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama_layanan' => 'required|string|max:255',
            'harga' => 'required|integer',
            'status' => 'required|boolean',
            'kategori' => 'required|string',
            'item' => 'required|string',
            'idwaktu' => 'string'
        ]);

        if($validator->fails()){
            return $this->error('Failed!', [ 'message' => $validator->errors()], 400);       
        }

        $waktu = Satuan::find($id)->update([
            'nama_layanan' => $request->nama_layanan,
            'harga' => $request->harga,
            'kategori' => $request->kategori,
            'status' => $request->status,
            'item' => $request->item,
            'idwaktu' => $request->idwaktu,
            'idoutlet' => Auth::user()['outlet_id']
        ]);

        if($waktu){
            return $this->success('Success!', "successfully updated data!");
        }else{
            return $this->error('Failed!', [ 'message' => $waktu->errors()], 400);       
        }
    }
    public function delete($id)
    {
        $delete = Satuan::find($id)->delete();
        
        if($delete){
            return $this->success('Success!', "successfully deleted data!");
        }else{
            return $this->error('Failed!', [ 'message' => $waktu->errors()], 400);       
        }
    }
}
