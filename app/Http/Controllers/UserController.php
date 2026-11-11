<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Mail\ResetPassword;
use App\Mail\VerifyEmailMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user=User::orderby('updated_at','desc')->get();
        return view('backend.layout.v_user.index', [
            'judul'=>'User',
            'subjudul'=>'Daftar Pengguna',
            'index'=>$user,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users',
            'role' => 'required',
            'foto'=>'image|mimes:jpeg,jpg,png,gif|file|max:1024'
        ],
        $message=[
            'foto.image'=>'Format gambar gunakan file dengan ekstensi jpeg, jpg, png, atau gif',
            'foto.max'=>'Ukuran file gambar maksimal adalah 1024 KB',
        ]
        );
        
        // default foto
        $originalFileName = null;

        //menggunakan imageHelper
        if($request->file('foto')){
            $file = $request->file('foto');
            $extension=$file->getClientOriginalExtension();
            $originalFileName=date('YmdHis') . '_' . uniqid() . '.' . $extension;
            $directory='storage/img-user/';

            ImageHelper::uploadAndResize($file, $directory, $originalFileName, 400, 500);

            $validatedData['foto']=$originalFileName;
        }

        // generate token
        $token = Str::random(64);
    
        // simpan user
        $user = User::create([
            'nama' => $request->nama,    
            'email' => $request->email,   
            'role' => $request->role,    
            'status' => 0,    
            'foto' => $originalFileName,
            'password' => null,    
            'verification_token' => $token
        ]);
    
        // kirim email
        Mail::to($user->email)
            ->send(
                new VerifyEmailMail($user)
            );
    
        return back()->with(
            'success',
            'User berhasil dibuat'
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user=User::findOrFail($id);
        if(Auth::id() == $id){

            return back()->with(
                'error',
                'Akun yang sedang digunakan tidak dapat diedit'
            );
        }
        $rules=[
            'nama'=>'required|max:255',
            'role'=>'required',
            'status'=>'required',
            'foto'=>'image|mimes:jpeg,jpg,png,gif|file|max:1024'
        ];
        $message=[
            'foto.image'=>'Format gambar gunakan file dengan ekstensi jpeg, jpg, png, atau gif',
            'foto.max'=>'Ukuran file gambar maksimal adalah 1024 KB',
        ];

        $validatedData=$request->validate($rules, $message);
        //menggunakan imageHelper
        if($request->file('foto')){
            // Hapus gambar lama
            if($user->foto){
                $oldImagePath=public_path('storage/img-user/') . $user->foto;
                if(file_exists($oldImagePath)){
                    unlink($oldImagePath);
                }
            }
            $file = $request->file('foto');
            $extension=$file->getClientOriginalExtension();
            $originalFileName=date('YmdHis') . '_' . uniqid() . '.' . $extension;
            $directory='storage/img-user/';

            ImageHelper::uploadAndResize($file, $directory, $originalFileName, 400, 400);

            $validatedData['foto']=$originalFileName;
        }
        $user->update($validatedData);
        return redirect()->route('backend.user.index')->with('success', 'Data berhasil diperbaharui');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user= User::findOrFail($id);
        if(Auth::id() == $id){

            return back()->with(
                'error',
                'Akun yang sedang digunakan tidak dapat dihapus'
            );
        }
        if($user->foto){
            $oldImagePath=public_path('storage/img-user/') . $user->foto;
            if(file_exists($oldImagePath)){
                unlink($oldImagePath);
            }
        }
        $user->delete();
        return redirect()->route('backend.user.index')->with('success','Data Berhasil Dihapus');
    }

    public function verifikasiUlang($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'verification_token' => Str::random(60),
            'created_at' => now() 
        ]);

        Mail::to($user->email)->send(new VerifyEmailMail($user));

        return redirect()->back()->with('success', 'Tautan verifikasi baru berhasil dikirim ke ' . $user->email);
    }

    public function profile()
    {
        $user=Auth::user();
        return view('backend.layout.v_user.profile', [
            'judul'=>'Profile',
            'index'=>$user,
        ]);
    }

    public function linkReset($id)
    {
        $user = User::findOrFail($id);
        if ($user->status == 0 || is_null($user->email_verified_at)) {
            return redirect()->back()->with('error', 'Gagal! Akun ' . $user->nama . ' belum melakukan verifikasi awal dan pembuatan kata sandi pertama kali.');
        }

        $token = Str::random(60);
        
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => Hash::make($token), 
                'created_at' => now()
            ]
        );

        $urlReset = route('password.reset', ['token' => $token, 'email' => $user->email]);

        try {
            Mail::to($user->email)->send(new ResetPassword($user, $urlReset));
            
            return redirect()->back()->with('success', 'Tautan reset password berhasil dikirim ke Gmail: ' . $user->email);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim email. Periksa setelan SMTP sistem Anda. Rincian: ' . $e->getMessage());
        }
    }
}
