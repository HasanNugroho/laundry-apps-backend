<?php

namespace App\Http\Controllers\API\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponser;
use Illuminate\Support\Str;
use App\Models\Kiloan;
use Validator;

class KiloanController extends Controller
{
    use ApiResponser;
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama_layanan' => 'required|string|max:255',
            'status' => 'required|integer',
            'item' => 'string',
            'idwaktu' => 'required|string',
            'harga' => 'required|integer',
        ]);

        if($validator->fails()){
            return $this->error('Failed!', [ 'message' => $validator->errors()], 400);       
        }

        if (Kiloan::where('nama_layanan', '=', $request->nama_layanan)->exists()) {
            return $this->error('Failed!', [ 'message' => 'Data exists'], 400);       
        }

        $uuid = Str::uuid();
        $waktu = Kiloan::create([
            'id' => $uuid,
            'nama_layanan' => $request->nama_layanan,
            // 'waktu' => $request->waktu,
            'jenis' => 'kiloan',
            'status' => $request->status,
            'item' => $request->item,
            'idwaktu' => $request->idwaktu,
            'harga' => $request->harga,
            'idoutlet' => Auth::user()['outlet_id']
        ]);

        return $this->success('Success!',"successfully created data!");
    }

    public function show()
    {
        $waktu = Kiloan::where('status', 1)->get();
        return $this->success('Success!', $waktu);
    }

    public function showById($id)
    {
        $waktu = Kiloan::where('id', $id)->where('status', 1)->first();
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
            'nama_layanan' => 'string|max:255|unique:kiloans',
            'jenis' => 'string',
            'status' => 'boolean',
            'item' => 'string',
            'idwaktu' => 'string',
            'harga' => 'integer'
        ]);

        if($validator->fails()){
            return $this->error('Failed!', [ 'message' => $validator->errors()], 400);       
        }
        
        if($request->all()){
            $waktu = Kiloan::find($id)->update($request->all());
        }else{
            return $this->error('Failed!', [ 'message' => 'no data to update!'], 404);       
        }

        if($waktu){
            return $this->success('Success!', "successfully updated data!");
        }else{
            return $this->error('Failed!', [ 'message' => $waktu->errors()], 400);       
        }
    }
    public function delete($id)
    {
        $delete = Kiloan::find($id)->delete();
        
        if($delete){
            return $this->success('Success!', "successfully deleted data!");
        }else{
            return $this->error('Failed!', [ 'message' => $waktu->errors()], 400);       
        }
    }
}
