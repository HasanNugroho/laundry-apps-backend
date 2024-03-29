<?php

namespace App\Http\Controllers\API\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponser;
use App\Models\outletKode;
use App\Models\Invite;
use App\Models\User;
use App\Models\Outlet;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class OutletController extends Controller
{
    use ApiResponser;
    public function show()//menampilkan data outlet
    {
        $user_outlet = Auth::user()->outlet_id;
        if($user_outlet){
            $outlet = Outlet::where('id', $user_outlet)->orWhere('parent', $user_outlet)->get();
        }else{
            $outlet = [];
        }
        return $this->success(' Success!', $outlet);
    }
    
    public function showbyid($id)//menampilkan data outlet berdasarkan id
    {
        $user_outlet = Auth::user()->outlet_id;
        $outlet = Outlet::where('id', $id)->first();
        return $this->success(' Success!', $outlet);
    }
    
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama_outlet' => 'required|string|max:255',
            'alamat' => 'string|max:255',
            'sosial_media' => 'string|max:255',
        ]);

        if($validator->fails()){
            return $this->error('Create Outlet Failed!', [ 'message' => $validator->errors()], 400);       
        }
        
        if (Outlet::where('nama_outlet', '=', $request->nama_outlet)->exists()) {
            return $this->error('Create Outlet Failed!', [ 'message' => 'Outlet exists'], 400);       
        }
        try {
            $lenData = DB::table('outlets')->where('status_outlet', 'pusat')->count();
            if($lenData < 5){
                $userExist = User::where('uid', Auth::user()->uid)->select('outlet_id')->first();
                if($userExist->outlet_id == null || Auth::user()->email_verified_at != null){
                    $uuid = Str::uuid();
                    $outlet = Outlet::create([
                        'id' => $uuid,
                        'nama_outlet' => $request->nama_outlet,
                        'status_outlet' => 'pusat',
                        'alamat' => $request->alamat ? $request->alamat : null,
                        'sosial_media' => $request->sosial_media ? $request->sosial_media :null,
                    ]);
                    $user = User::where('uid', Auth::user()->uid)->update(['outlet_id' => $uuid]);
                    
                    $y = ($lenData / 26);
                    if ($y >= 1) {
                        $y = intval($y);
                        $kode = chr($y+64) . chr($lenData-$y*26 + 65);
                    } else {
                        $kode = chr($lenData+65);
                    }
    
                    outletKode::create([
                        'outletid' => $uuid,
                        'kode' => $kode
                    ]);
                }else{ 
                    return $this->error('Create Outlet Failed!', [ 'message' => 'user can\'t create outlets!'], 400);       
                }
            }else{
                return $this->error('Create Outlet Failed!', [ 'message' => 'Outlet limited!'], 400);       
            }
            return $this->success('Create Outlet Success!', $outlet);
        } catch (\Exception $e) {
            return $this->error('Create Outlet Failed!', [ 'message' => $e->getMessage()], 400);       
        }
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama_outlet' => 'string|max:255',
            'alamat' => 'string|max:255',
            'sosial_media' => 'string|max:255',
        ]);

        if($validator->fails()){
            return $this->error('Failed!', [ 'message' => $validator->errors()], 400);       
        }

        if($request->all()){
            $outlet = Outlet::find($id)->update($request->all());
        }else{
            return $this->error('Failed!', [ 'message' => 'no data to update!'], 404);       
        }

        if($outlet){
            return $this->success('Success!', "successfully updated data!");
        }else{
            return $this->error('Failed!', [ 'message' => $outlet->errors()], 400);       
        }
    }

    public function delete($id)
    {
        $delete = Outlet::find($id)->delete();
        
        if($delete){
            return $this->success('Success!', "successfully deleted data!");
        }else{
            return $this->error('Failed!', [ 'message' => $delete->errors()], 400);       
        }
    }
    
    public function tambahCabang(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama_outlet' => 'required|string|max:255',
            'parent' => 'required|string',
            'alamat' => 'string|max:255',
            'sosial_media' => 'string|max:255',
        ]);

        if($validator->fails()){
            return $this->error('Create Outlet Failed!', [ 'message' => $validator->errors()], 400);       
        }

        if (Outlet::where('nama_outlet', '=', $request->nama_outlet)->exists()) {
            return $this->error('Create Outlet Failed!', [ 'message' => 'Outlet exists'], 400);       
        }
        try {
            $uuid = Str::uuid();
            $outlet = Outlet::create([
                'id' => $uuid,
                'nama_outlet' => $request->nama_outlet,
                'parent' => $request->parent,
                'status_outlet' => 'cabang',
                'alamat' => $request->alamat ? $request->alamat : null,
                'sosial_media' => $request->sosial_media ? $request->sosial_media :null,
            ]);
            
            $lenData = DB::table('outlet_kodes')
            ->count();

            $y = ($lenData / 26);
            if ($y >= 1) {
                $y = intval($y);
                $kode = chr($y+64) . chr($lenData-$y*26 + 65);
            } else {
                $kode = chr($lenData+65);
            }

            outletKode::create([
                'outletid' => $uuid,
                'kode' => $kode
            ]);
            return $this->success('Create Outlet Success!', $outlet);
        } catch (\Exception $e) {
            return $this->error('Create Outlet Failed!', [ 'message' => $e->getMessage()], 400);       
        }
        

    }

    public function invite(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'idoutlet' => 'required|string|max:255',
        ]);

        if($validator->fails()){
            return $this->error('Create Invite Failed!', [ 'message' => $validator->errors()], 400);       
        }
        $token = Str::random(6);
        $invite = Invite::create([
        'token' => $token,
        'idoutlet' => $request->idoutlet,
        'status' => 1,
        ]);

        return $this->success('Create Invite Success!',[
            'token' => $token
        ]);
    }
}
