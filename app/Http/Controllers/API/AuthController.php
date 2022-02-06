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
use App\Models\PasswordReset;
use App\Models\verif;
use App\Models\Invite;
use App\Mail\Verif as MailVerif;
use App\Mail\ResetPassword as MailPassword;
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
            
            // $randomToken = $this->randomToken();
            // $details = [
            //     'title' => 'Verifikasi Email',
            //     'subject' => 'Verifikasi Email',
            //     'deskripsi' => 'Silahkan Klik Link dibawah ini untuk proses verifikasi, (link hanya bisa diakses selama 10 menit) Once it\'s done you will be able to start selling!',
            //     'url' => URL::signedRoute('verif', ['token' => $randomToken])
            // ];
            
            // $token = new verif();
            // $token->userid = $input['uid'];
            // $token->token = $randomToken;
            // $token->expired = now()->addMinutes(10);
            // $token->save();
            
            // Mail::to($input['email'])->send(new MailVerif($details));
            
            DB::commit();
            return $this->success('Register Success and Check Email to Verification!');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Register Failed!', [ 'message' => $e->getMessage()], 400); 
            // return $this->error('Register Failed!', [ 'message' => 'Send Email Verification Failed!'], 400);       
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

        // $verifiy = DB::table('users')->where('email', $request->email)->whereNull('email_verified_at')->count();
        // // dd($verifiy);
        // if($verifiy){
        //     $exist_token = DB::table('users')
        //     ->rightJoin('verifs', 'verifs.userid', '=', 'users.uid')
        //     ->where('users.email', $request->email)
        //     ->whereNull('users.email_verified_at')
        //     ->count('verifs.userid');
        //     if ($exist_token) {
        //         return $this->error('Failed!', [ 'message' => 'Please Verifikasi Email'], 401);       
        //     }else{
        //         $data = DB::table('users')->where('email', $request->email)->select('uid', 'email')->get();
        //         DB::beginTransaction();
        //         try {
        //             $randomToken = $this->randomToken();
        //             $details = [
        //                 'title' => 'Verifikasi Email',
        //                 'subject' => 'Verifikasi Email',
        //                 'deskripsi' => 'Silahkan Klik Link dibawah ini untuk proses verifikasi, (link hanya bisa diakses selama 10 menit) Once it\'s done you will be able to start selling!',
        //                 'url' => URL::signedRoute('verif', ['token' => $randomToken])
        //             ];
                    
        //             $token = new verif();
        //             $token->userid = $data[0]->uid;
        //             $token->token = $randomToken;
        //             $token->expired = now()->addMinutes(10);
        //             $token->save();
                    
        //             Mail::to($data[0]->email)->send(new MailVerif($details));
                    
        //             DB::commit();
        //             return $this->error('Login Failed!', [ 'message' => 'Check Email to Verification!'], 401);       
        //         } catch (\Throwable $th) {
        //             DB::rollBack();
        //             return $this->error('Login Failed!', [ 'message' => 'Login and Send Email Verification Failed!'], 401);       
        //         }
        //     }
        // }

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
            
            // $randomToken = $this->randomToken();
            // $details = [
            //     'title' => 'Verifikasi Email',
            //     'subject' => 'Verifikasi Email',
            //     'deskripsi' => 'Silahkan Klik Link dibawah ini untuk proses verifikasi, (link hanya bisa diakses selama 10 menit)',
            //     'url' => URL::signedRoute('verif', ['token' => $randomToken])
            // ];
            
            // $tokenVerif = new verif();
            // $tokenVerif->userid = $input['uid'];
            // $tokenVerif->token = $randomToken;
            // $tokenVerif->expired = now()->addMinutes(10);
            // $tokenVerif->save();
            
            // Mail::to($input['email'])->send(new MailVerif($details));
            
            DB::commit();
            // $token->delete();
            return $this->success('Register Success and Check Email to Verification!');
        } catch (\Exception $e) {
            DB::rollBack();
            // $token->delete();
            return $this->error('Register Failed!', [ 'message' => $e->getMessage()], 400);       
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

    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|string|email',
        ]);

        if($validator->fails()){
            return $this->error('Change Password Failed!', [ 'message' => $validator->errors()], 400);       
        }

        if (User::where('email', $request->email)->doesntExist()) {
            return $this->error('Failed!', [ 'message' => 'User Not Exist'], 404);       
        }
        
        // $notvalidate = User::where('email', $request->email)->where('email_verified_at', null)->count();
        // // dd($notvalidate);
        // if ($notvalidate > 0) {
        //     return $this->error('Failed!', [ 'message' => 'You must Verifikasi Email'], 404);       
        // }

        DB::beginTransaction();
        try {
            $randomToken = $this->randomToken();
            $resetpassword = new PasswordReset();
            $resetpassword->email = $request->email;
            $resetpassword->token = $randomToken;
            $resetpassword->created_at = now();
            $resetpassword->expired = now()->addMinutes(10);
            $resetpassword->save();
            
            $hostname = env("FRONTEND_URL");
            $redirect = URL::signedRoute('forgetpass',['token' => $randomToken]);

            $details = [
                'title' => 'Ubah Password',
                'subject' => 'Ubah Password',
                'deskripsi' => 'Silahkan klik link dibawah ini untuk mengubah password (link hanya bisa diakses selama 10 menit)',
                'footer' => 'jika kamu merasa tidak mengubah password, mohon abaikan pesan ini',
                'url' => $hostname.'/forget?token='. $randomToken . '&redirect=' . $redirect
            ];
            
            Mail::to($request->email)->send(new MailPassword($details));
            
            DB::commit();
            return $this->success('Check Email to Change Password!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->error('Change Password Failed!', [ 'message' => 'Send Email Change Password Failed!'], 400);       
        }
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'newPassword' => 'required|string|min:8',
            'token' => 'required',
        ]);

        if($validator->fails()){
            return $this->error('Change Password Failed!', [ 'message' => $validator->errors()], 400);       
        }
        
        
        if (PasswordReset::where('token', $request->token)->doesntExist()) {
            return $this->error('Change Password Failed!', [ 'message' => 'token expired!'], 400);       
        }
        try {
            $emailUpdate = PasswordReset::where('token', $request->token)->select('email')->first();
            
            User::where('email', $emailUpdate->email)->update(['password' =>  Hash::make($request->newPassword)]);
            
            return $this->success('Change Password Success!');
        } catch (\Throwable $th) {
            return $this->error('Change Password Failed!', 400);       
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
