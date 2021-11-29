<?php

namespace App\Http\Controllers\API\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            'paket' => 'required|string',
            'idoutlet' => 'required|uuid'
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
            'idoutlet' => $request->idoutlet
        ]);

        return $this->success('Success!',"successfully created data!");
    }

    public function show()
    {
        $waktu = Waktu::where('status', 1)->where('idoutlet', Auth::user()['outlet_id'])->get();
        return $this->success('Success!', $waktu);
    }

    public function showadmin()
    {
        $outletid = Auth::user()['outlet_id'];
        // $waktu = Waktu::all();
        $waktu = DB::select('select w.id, w.nama, w.waktu, w.jenis, w.status, w.paket, w.idoutlet, w.created_at, w.updated_at from waktus w left join outlets o on w.idoutlet = o.id where o.id = ? or parent = ?', [$outletid, $outletid]);

        return $this->success('Success!', $waktu);
    }

    public function showById($id)
    {
        $waktu = Waktu::where('id', $id)->where('status', 1)->where('idoutlet', Auth::user()['outlet_id'])->first();
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
            'nama' => 'string|max:255',
            'waktu' => 'integer',
            'jenis' => 'string',
            'status' => 'boolean',
            'paket' => 'string'
        ]);

        if($validator->fails()){
            return $this->error('Failed!', [ 'message' => $validator->errors()], 400);       
        }
        
        if($request->all()){
            $waktu = Waktu::find($id)->update($request->all());
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
        $delete = Waktu::find($id)->delete();
        
        if($delete){
            return $this->success('Success!', "successfully deleted data!");
        }else{
            return $this->error('Failed!', [ 'message' => $delete->errors()], 400);       
        }
    }
}
