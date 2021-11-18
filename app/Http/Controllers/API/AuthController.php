<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponser;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Models\User;
use App\Models\Invite;
use Validator;

class AuthController extends Controller
{
    use ApiResponser;
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'whatsapp' => 'required|string',
            'alamat' => 'required|string',
            // 'role' => 'required|string'
        ]);

        if($validator->fails()){
            return $this->error('Register Failed!', [ 'message' => $validator->errors()], 400);       
        }

        // if($request->role == 'owner'){
        //     $inputRole = 'owner';
        // }elseif($request->role == 'admin'){
        //     $inputRole = 'admin';
        // }else{
        //     $inputRole = 'karyawan';
        // }
        $uuid = Str::uuid();
        $input = [
            'uid' => $uuid,
            'username' => $request->username,
            'email' => $request->email,
            'whatsapp' => $request->whatsapp,
            'alamat' => $request->alamat,
            'password' => Hash::make($request->password),
            'role' => "owner",
        ];
        // $input = Arr::add($input, 'role' ,$inputRole);

        $user = User::create($input);

        return $this->success('Register Success!');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return $this->error('Login Failed!', [ 'message' => $validator->errors()], 400);       
        }

        if (!Auth::attempt($request->only('email', 'password')))
        {
            return $this->error('Unauthorized',null , 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken($user->uid)->plainTextToken;

        return $this->success('Authorized', [
            'token' => $token,
            'data' => $user
        ]);
    }

    // method for user logout and delete token
    public function logout()
    {
        try {
            auth()->user()->tokens()->delete();
            return $this->success('Logout Success!');
        } catch (Throwable $e) {
            return $this->error(report($e));
        }
    }

    public function registerKaryawan(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'token' => 'required|string',
            'whatsapp' => 'required|string',
            'alamat' => 'required|string',
        ]);

        if($validator->fails()){
            return $this->error('Register Failed!', [ 'message' => $validator->errors()], 400);       
        }

        $token = Invite::where('token', $request->token)->first();
        if($token){
            $uuid = Str::uuid();
            $input = [
                'uid' => $uuid,
                'username' => $request->username,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'whatsapp' => $request->whatsapp,
                'password' => Hash::make($request->password),
                'role' => "karyawan",
                'outlet_id' => $token->idoutlet,
            ];
        }

        $user = User::create($input);

        $token->delete();

        return $this->success('Register Success!');
    }
    
}
