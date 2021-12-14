<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Traits\ApiResponser;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Models\User;
use App\Models\verif;
use App\Models\Invite;
use App\Mail\Verif as MailVerif;
use Validator;

class AuthController extends Controller
{
    use ApiResponser;
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'whatsapp' => 'required|string|unique:users',
            'alamat' => 'required|string',
        ]);
        if($validator->fails()){
            return $this->error('Register Failed!', [ 'message' => $validator->errors()], 400);       
        }

        $uuid = Str::uuid();
        $input = [
            'uid' => $uuid,
            'username' => $request->username,
            'email' => $request->email,
            'whatsapp' => $request->whatsapp,
            'alamat' => $request->alamat,
            'status' => 'INACTIVE',
            'password' => Hash::make($request->password),
            'role' => "owner",
        ];
        
        DB::beginTransaction();
        try {
            $user = new User();
            $user->uid = $input['uid'];
            $user->username = $input['username'];
            $user->email = $input['email'];
            $user->whatsapp = $input['whatsapp'];
            $user->alamat = $input['alamat'];
            $user->status = $input['status'];
            $user->password = $input['password'];
            $user->role = $input['role'];
            $user->save();
            
            $randomToken = $this->randomToken();
            $details = [
                'title' => 'Verify your email address',
                'subject' => 'Verification Email',
                'deskripsi' => 'Please confirm that you want to use this as your sellfy account email address. Once it\'s done you will be able to start selling!',
                'url' => URL::signedRoute('verif', ['token' => $randomToken])
            ];
            
            $token = new verif();
            $token->userid = $input['uid'];
            $token->token = $randomToken;
            $token->save();
            
            Mail::to($input['email'])->send(new MailVerif($details));
            
            DB::commit();
            return $this->success('Register Success and Check Email to Verification!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->error('Register Failed!', [ 'message' => 'Send Email Verification Failed!'], 400);       
        }
        // dd("Email is Sent.");
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

        if (User::where('email', $request->email)->doesntExist()) {
            return $this->error('Failed!', [ 'message' => 'User don\'t exist!'], 404);       
        }

        $verifiy = DB::table('users')->where('email', $request->email)->whereNull('email_verified_at')->count();
        // dd($verifiy);
        if($verifiy){
            $exist_token = DB::table('users')
            ->rightJoin('verifs', 'verifs.userid', '=', 'users.uid')
            ->where('users.email', $request->email)
            ->whereNull('users.email_verified_at')
            ->count('verifs.userid');
            if ($exist_token) {
                return $this->error('Failed!', [ 'message' => 'Please Verify Your Email Address'], 401);       
            }else{
                $data = DB::table('users')->where('email', $request->email)->select('uid', 'email')->get();
                DB::beginTransaction();
                try {
                    $randomToken = $this->randomToken();
                    $details = [
                        'title' => 'Verify your email address',
                        'subject' => 'Verification Email',
                        'deskripsi' => 'Please confirm that you want to use this as your sellfy account email address. Once it\'s done you will be able to start selling!',
                        'url' => URL::signedRoute('verif', ['token' => $randomToken])
                    ];
                    
                    $token = new verif();
                    $token->userid = $data[0]->uid;
                    $token->token = $randomToken;
                    $token->save();
                    
                    Mail::to($data[0]->email)->send(new MailVerif($details));
                    
                    DB::commit();
                    return $this->error('Login Failed!', [ 'message' => 'Check Email to Verification!'], 401);       
                } catch (\Throwable $th) {
                    DB::rollBack();
                    return $this->error('Login Failed!', [ 'message' => 'Login and Send Email Verification Failed!'], 401);       
                }
            }
        }

        if (!Auth::attempt($request->only('email', 'password')))
        {
            return $this->error('Failed!', [ 'message' => 'Email or Password is Incorrect'], 401);       
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken($user->uid)->plainTextToken;

        User::where('email', $request['email'])->update(['status' => 'ACTIVE']);

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
            User::where('uid', Auth::user()['uid'])->update(['status' => 'INACTIVE']);
            return $this->success('Logout Success!');
        } catch (Throwable $e) {
            return $this->error(report($e));
        }
    }

    public function registerKaryawan(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'token' => 'required|string',
            'whatsapp' => 'required|string|unique:users',
            'alamat' => 'required|string',
        ]);

        if($validator->fails()){
            return $this->error('Register Failed!', [ 'message' => $validator->errors()], 400);       
        }

        if (Invite::where('token', $request->token)->doesntExist()) {
            return $this->error('Failed!', [ 'message' => 'Token Expired'], 404);       
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
                'status' => 'INACTIVE',
                'outlet_id' => $token->idoutlet,
            ];
        }

        DB::beginTransaction();
        try {
            // dd($input);
            $user = new User();
            $user->uid = $input['uid'];
            $user->username = $input['username'];
            $user->email = $input['email'];
            $user->whatsapp = $input['whatsapp'];
            $user->alamat = $input['alamat'];
            $user->status = $input['status'];
            $user->password = $input['password'];
            $user->role = $input['role'];
            $user->outlet_id = $input['outlet_id'];
            $user->save();
            
            $randomToken = $this->randomToken();
            $details = [
                'title' => 'Verify your email address',
                'subject' => 'Verification Email',
                'deskripsi' => 'Please confirm that you want to use this as your sellfy account email address. Once it\'s done you will be able to start selling!',
                'url' => URL::signedRoute('verif', ['token' => $randomToken])
            ];
            
            $tokenVerif = new verif();
            $tokenVerif->userid = $input['uid'];
            $tokenVerif->token = $randomToken;
            $tokenVerif->save();
            
            Mail::to($input['email'])->send(new MailVerif($details));
            
            DB::commit();
            $token->delete();
            return $this->success('Register Success and Check Email to Verification!');
        } catch (\Throwable $th) {
            DB::rollBack();
            $token->delete();
            return $this->error('Register Failed!', [ 'message' => 'Send Email Verification Failed!'], 400);       
        }


    }

    public function verif(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'token' => 'required|string'
        ]);

        if($validator->fails()){
            return $this->error('Register Failed!', [ 'message' => $validator->errors()], 400);       
        }

        if (verif::where('token', $request->token)->doesntExist()) {
            return ("<script LANGUAGE='JavaScript'>
            window.alert('token expired!');
            </script>");
        }
        try {
            $userid = verif::where('token', $request->token)->select('userid')->get();
    
            User::where('uid', $userid[0]['userid'])->update(['email_verified_at' => now()]);
            
            verif::where('token', $request->token)->select('userid')->delete();

            $hostname = env("FRONTEND_URL");

            return ("<script LANGUAGE='JavaScript'>
            window.alert('Succesfully Verify');
            window.location.href='".$hostname."';
            </script>");

        } catch (\Throwable $th) {
            return ("<script LANGUAGE='JavaScript'>
            window.alert('Failed Verify');
            </script>");
        }
    }

    public function randomToken()
    {
        $strength = 60;
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $input_length = strlen($permitted_chars);
        $random_string = '';
        for($i = 0; $i < $strength; $i++) {
            $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
    
        return $random_string;
    }
}
