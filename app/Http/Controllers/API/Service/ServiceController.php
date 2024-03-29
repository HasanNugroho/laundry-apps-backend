<?php

namespace App\Http\Controllers\API\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponser;
use Illuminate\Support\Str;
use App\Models\Service;
use App\Models\Waktu;
use Validator;

class ServiceController extends Controller
{
    use ApiResponser;
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama_layanan' => 'required|string|max:255',
            'harga' => 'required|integer',
            'status' => 'required|boolean',
            'kategori' => 'string',
            'item' => 'required|string',
            'idwaktu' => 'string|uuid',
            'idoutlet' => 'required|uuid'
        ]);

        if($validator->fails()){
            return $this->error('Failed!', [ 'message' => $validator->errors()], 400);       
        }

        // if (Service::where('nama_layanan', '=', $request->nama_layanan)->exists()) {
        //     return $this->error('Failed!', [ 'message' => 'Data exists'], 400);       
        // }
        $jenis = Waktu::where('id', $request->idwaktu)->select('jenis')->first();
        $uuid = Str::uuid();
        $waktu = Service::create([
            'id' => $uuid,
            'nama_layanan' => $request->nama_layanan,
            'harga' => $request->harga,
            'kategori' => $request->kategori,
            'status' => $request->status,
            'item' => $request->item,
            'idwaktu' => $request->idwaktu,
            'jenis' => $jenis->jenis,
            'idoutlet' => $request->idoutlet
        ]);

        return $this->success('Success!',"successfully created data!");
    }

    public function show()
    {
        $service = Service::where('status', 1)->where('idoutlet', Auth::user()['outlet_id'])->get();
        return $this->success('Success!', $service);
    }

    public function showadmin(Request $request)
    {
        $outletid = Auth::user()['outlet_id'];
        // $service = Service::all();
        if($request->outlet){
            $service = DB::select('select s.id, s.nama_layanan, s.harga, s.jenis, s.status, s.idwaktu, s.idoutlet, s.created_at, s.updated_at, w.nama as nama_waktu, w.waktu as estimasi from services s left join outlets o on s.idoutlet = o.id left join waktus w on s.idwaktu = w.id where o.id = ? and o.parent = ?', [$request->outlet, $outletid]);
        }else{
            $service = DB::select('select s.id, s.nama_layanan, s.harga, s.jenis, s.status, s.idwaktu, s.idoutlet, s.created_at, s.updated_at, w.nama as nama_waktu, w.waktu as estimasi from services s left join outlets o on s.idoutlet = o.id left join waktus w on s.idwaktu = w.id where o.id = ? or o.parent = ?', [$outletid, $outletid]);
        }
        return $this->success('Success!', $service);
    }

    public function showById($id)
    {
        $waktu = Service::where('id', $id)->where('status', 1)->where('idoutlet', Auth::user()['outlet_id'])->first();
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
            'nama_layanan' => 'string|max:255',
            'harga' => 'integer',
            'status' => 'boolean',
            'kategori' => 'string',
            'item' => 'string',
            'idwaktu' => 'string|uuid'
        ]);

        if($validator->fails()){
            return $this->error('Failed!', [ 'message' => $validator->errors()], 400);       
        }
        

        if($request->all()){
            $waktu = Service::find($id)->update($request->all());
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
        $delete = Service::find($id)->delete();
        
        if($delete){
            return $this->success('Success!', "successfully deleted data!");
        }else{
            return $this->error('Failed!', [ 'message' => $waktu->errors()], 400);       
        }
    }
}
