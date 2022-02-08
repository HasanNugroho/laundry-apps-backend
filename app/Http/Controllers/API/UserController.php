<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Models\User;
use Validator;

class UserController extends Controller
{
    use ApiResponser;
    public function show()
    {
        $user_outlet = Auth::user()->outlet_id;
        $user = DB::table('users')
                ->leftJoin('outlets', 'users.outlet_id', '=', 'outlets.id')
                ->where('outlets.id', $user_outlet)
                ->orWhere('outlets.parent', $user_outlet)
                ->select('users.uid', 'users.username', 'users.email', 'users.role', 'users.alamat','users.whatsapp', 'outlets.nama_outlet')
                ->get();
        if ($user) {
            return $this->success('Success!', $user);
        }else{
            return $this->error('Get Data Failed!', 400);       
        }
    }
    
    public function showdetil($id)
    {
        $user = User::where('uid', $id)->firstOrFail();
        if ($user) {
            return $this->success('Success!', $user);
        }else{
            return $this->error('Get Data Failed!', 400);       
        }
    }

    public function updaterole($id, Request $request)
    {
        $user = User::where('uid', $id)->firstOrFail();
        if ($user) {
            $validator = Validator::make($request->all(),[
                'role' => 'required|string'
            ]);

            if($validator->fails()){
                return $this->error('Register Failed!', [ 'message' => $validator->errors()], 400);       
            }

            $user->update($request->all());
            $user->save();

            return $this->success('Update Role Success!');
        }else{
            return $this->error('Get Data Failed!', 400);       
        }
    }
    public function update($id, Request $request)
    {
        if(Auth::user()->role != 'owner'){
            return $this->error('Forbidden to access', 403);
        }
        $user = User::where('uid', $id)->firstOrFail();
        if ($user) {
            $validator = Validator::make($request->all(),[
                'username' => 'string|unique:users',
                'whatsapp' => 'string|unique:users',
                'alamat' => 'string',
                'email' => 'string|email|unique:users',
                'outlet_id' => 'string',
            ]);
            if($validator->fails()){
                return $this->error('Register Failed!', [ 'message' => $validator->errors()], 400);       
            }

            $user->update($request->all());
            $user->save();

            return $this->success('Update User Success!');
        }else{
            return $this->error('Update Data Failed!', 400);
        }
    }

    public function delete($id)
    {
        $user_outlet = Auth::user()->outlet_id;

        if(Auth::user()->uid == $id){
            return $this->error('can\'t delete owner' ,null ,403);
        }
        if(DB::table('users')->where('uid', $id)->doesntExist()){
            return $this->error('user not found' ,null ,404);
        }
        $userThisOutlet = DB::table('users')
        ->leftJoin('outlets', 'users.outlet_id', '=', 'outlets.id')
        ->where('users.uid', $id)
        ->where('outlets.id', $user_outlet)
        ->orWhere('outlets.parent', $user_outlet)
        ->select('users.uid')
        ->get();
        if($userThisOutlet){
            DB::table('users')->where('uid', $id)->delete();
        }else{
            return $this->error('user not found' ,null ,404);
        }
        return $this->success('Delete User Success!');
    }
}
