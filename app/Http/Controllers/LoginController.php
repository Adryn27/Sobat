<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class LoginController extends Controller
{
    public function loginBackend(){
        return view('backend.auth.login', [
            'judul'=>'Login',
        ]);
    }

    public function authenticateBackend(Request $request){
        $credentials=$request->validate([
            'email'=> 'required|email',
            'password'=> 'required'
        ]);

        $user = User::where(
            'email',
            $request->email
        )->first();

        if(!$user){

            return back()->withErrors(['email' => 'Email tidak ditemukan'])->withInput();
        }

        if($user->email_verified_at == null){

            return back()->withErrors(['email' => 'Email belum terverifikasi'])->withInput();
        }

        if($user->status == 0){

            return back()->withErrors(['email' => 'Akun belum aktif'])->withInput();
        }

        if(Auth::attempt($credentials)){

            $request->session()->regenerate();

            return redirect()->intended(
                route('backend.beranda')
            );
        }
        return back()->withErrors(['password' => 'Kata sandi yang anda masukkan salah'])->withInput();
    }

    public function verifyEmail($token)
    {
        $user = User::where(
            'verification_token',
            $token
        )->first();

        if(!$user){

            abort(404);
        }
        if (Carbon::parse($user->created_at)->addMinutes(60)->isPast()) {
            abort(403, 'Tautan verifikasi telah kedaluwarsa (melebihi 60 menit).');
        }
        return view(
            'backend.auth.setpw',
            compact('user'), [
                'judul' => 'Verification & Set Password'
            ]
        );
    }

    public function setPassword(
        Request $request,
        $token
    ){

        $request->validate([

            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                ->letters()   
                ->mixedCase() 
                ->numbers()   
                ->symbols(),  
                ]
        ]);

        $user = User::where(
            'verification_token',
            $token
        )->first();

        if(!$user){

            abort(404);
        }
        if (Carbon::parse($user->created_at)->addMinutes(60)->isPast()) {
            return redirect()->route('backend.login')->with('error', 'Gagal menyimpan! Waktu verifikasi Anda telah habis (melebihi 60 menit).');
        }
        $user->update([

            'password' =>
                Hash::make(
                    $request->password
                ),

            'email_verified_at' => now(),

            'status' => 1,

            'verification_token' => null
        ]);

        return redirect(
            route('backend.login')
        );
    }

    public function logoutBackend(){
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect(route('backend.login'));
    }

    public function formReset(Request $request, $token)
    {
        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();
        if (!$record) {
            return redirect()->route('backend.login');
        }

        $masaBerlakuMenit = config('auth.passwords.users.expire', 60); 
        
        if (Carbon::parse($record->created_at)->addMinutes($masaBerlakuMenit)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            abort(403, 'Tautan reset kata sandi telah kedaluwarsa (melebihi 60 menit).');
        }
        
        return view('backend.auth.reset', [
            'token' => $token,
            'email' => $request->email,
            'judul' => 'Reset Password'
        ]);
    }

    public function prosesReset(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => [
                'required', 
                'confirmed', 
                Password::min(8)->letters()->mixedCase()->numbers()->symbols()
            ],
        ], [
            'password.confirmed' => 'Kata sandi tidak cocok',
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return redirect()->back()->withErrors(['email' => 'Tautan reset kata sandi tidak valid atau telah kedaluwarsa (60 menit).']);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('backend.login');
    }
}
