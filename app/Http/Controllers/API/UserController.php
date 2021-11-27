<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Models\User;
use Validator;

class UserController extends Controller
{
    use ApiResponser;
    public function showall()
    {
        $user = User::all();
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
}
