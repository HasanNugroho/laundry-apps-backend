<?php

namespace App\Http\Controllers\API\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;
use App\Traits\ApiResponser;
use App\Models\User;
use App\Models\Outlet;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class OutletController extends Controller
{
    use ApiResponser;
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama_outlet' => 'required|string|max:255',
            'status_outlet' => 'required|string|max:255',
            'alamat' => 'string|max:255',
            'sosial_media' => 'string|max:255',
        ]);

        if($validator->fails()){
            return $this->error('Create Outlet Failed!', [ 'message' => $validator->errors()], 400);       
        }

        if (Outlet::where('nama_outlet', '=', $request->nama_outlet)->exists()) {
            return $this->error('Create Outlet Failed!', [ 'message' => 'Outlet exists'], 400);       
        }
        $uuid = Str::uuid();
        $outlet = Outlet::create([
        'id' => $uuid,
        'nama_outlet' => $request->nama_outlet,
        'status_outlet' => $request->status_outlet,
        'alamat' => $request->alamat ? $request->alamat : null,
        'sosial_media' => $request->sosial_media ? $request->sosial_media :null,
        ]);
        $user = User::where('uid', Auth::user()->uid)->update(['outlet_id' => $uuid]);

        return $this->success('Create Outlet Success!',[
            'data' => $outlet
        ]);
    }
}
