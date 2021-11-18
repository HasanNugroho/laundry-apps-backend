<?php

namespace App\Http\Controllers\API\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponser;
use Illuminate\Support\Str;
use App\Models\Waktu;
use Validator;

class WaktuController extends Controller
{
    use ApiResponser;
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama' => 'required|string|max:255',
            'waktu' => 'required|integer',
            'jenis' => 'required|string',
            'paket' => 'required|string'
        ]);

        if($validator->fails()){
            return $this->error('Failed!', [ 'message' => $validator->errors()], 400);       
        }

        if (Waktu::where('nama', '=', $request->nama)->exists()) {
            return $this->error('Failed!', [ 'message' => 'Data exists'], 400);       
        }
        
        $uuid = Str::uuid();
        $waktu = Waktu::create([
            'id' => $uuid,
            'nama' => $request->nama,
            'waktu' => $request->waktu,
            'jenis' => $request->jenis,
            'status' => 1,
            'paket' => $request->paket,
            'idoutlet' => Auth::user()['outlet_id']
        ]);

        return $this->success('Success!',"successfully created data!");
    }

    public function show()
    {
        $waktu = Waktu::where('status', 1)->get();
        return $this->success('Success!', $waktu);
    }

    public function showById($id)
    {
        $waktu = Waktu::where('id', $id)->where('status', 1)->first();
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
            'nama' => 'required|string|max:255',
            'waktu' => 'required|integer',
            'jenis' => 'required|string',
            'status' => 'required|boolean',
            'paket' => 'required|string'
        ]);

        if($validator->fails()){
            return $this->error('Failed!', [ 'message' => $validator->errors()], 400);       
        }

        $waktu = Waktu::find($id)->update([
            'nama' => $request->nama,
            'waktu' => $request->waktu,
            'jenis' => $request->jenis,
            'status' => $request->status,
            'paket' => $request->paket,
        ]);

        if($waktu){
            return $this->success('Success!', "successfully updated data!");
        }else{
            return $this->error('Failed!', [ 'message' => $waktu->errors()], 400);       
        }
    }
    public function delete($id)
    {
        $delete = Waktu::find($id)->delete();
        
        if($delete){
            return $this->success('Success!', "successfully deleted data!");
        }else{
            return $this->error('Failed!', [ 'message' => $waktu->errors()], 400);       
        }
    }
}
