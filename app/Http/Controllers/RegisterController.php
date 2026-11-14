<?php

namespace App\Http\Controllers;

use App\Mail\VerifyTenantMail;
use App\Models\Tenants;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function registerBackend()
    {
        return view('backend.auth.register', [
            'judul' => 'Register'
        ]);
    }

    public function register(Request $request)
    {
        $expiredUsers = User::whereNull('email_verified_at')
            ->where('status', 0)
            ->where('created_at', '<', Carbon::now()->subMinutes(10))
            ->get();

        foreach ($expiredUsers as $expired) {
            if ($expired->tenant_id) {
                Tenants::where('id', $expired->tenant_id)->delete(); 
            } else {
                $expired->delete();
            }
        }

        $request->validate([
            'nama_tenant' => 'required|string|max:255',
            'alamat'      => 'required|string',
            'telepon'     => 'required|string|max:20',
            'nama'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ], [
            'email.unique'       => 'Email sudah terdaftar atau sedang menunggu verifikasi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $cleanSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->nama_tenant), '-'));
        $slug      = $cleanSlug . '-' . bin2hex(random_bytes(2));
        $token     = bin2hex(random_bytes(32));

        $user = DB::transaction(function () use ($request, $slug, $token) {
            $tenant = Tenants::create([
                'nama_tenant'  => $request->nama_tenant,
                'slug'      => $slug,
                'alamat'    => $request->alamat,
                'telepon'   => $request->telepon,
                'status' => false,
            ]);

            return User::create([
                'tenant_id'          => $tenant->id,
                'nama'               => $request->nama,
                'email'              => $request->email,
                'password'           => Hash::make($request->password),
                'role'               => 2, 
                'status'             => 0, 
                'verification_token' => $token,
                'email_verified_at'  => null,
            ]);

        });

        Mail::to($user->email)->send(new VerifyTenantMail($user, $token));

        return redirect()->route('backend.register.waiting')->with('email_verifikasi', $user->email);
    }

    public function waitingVerification(Request $request)
    {
        $email = session('email_verifikasi');
    
        if (!$email) {
            // Jika session kosong (langsung ketik URL / refresh setelah sesi hilang), lempar ke login
            return redirect()->route('backend.login');
        }

        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return redirect()->route('backend.login')
                ->with('error', 'Data pendaftaran tidak ditemukan atau sudah kedaluwarsa.');
        }

        if ($user->status == 1 && !is_null($user->email_verified_at)) {
            Auth::login($user);
            $request->session()->regenerate();
            session()->forget('email_verifikasi');
            return redirect()->route('backend.beranda')->with('success', 'Verifikasi berhasil!');
        }

        return view('backend.auth.waiting', [
            'judul' => 'Verification'
        ]);
    }

   
    public function verifyRegistration(Request $request, $token)
    {
        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            abort(404, 'Tautan verifikasi tidak valid atau sudah pernah digunakan.');
        }

        if (Carbon::parse($user->created_at)->addMinutes(10)->isPast()) {
            if ($user->tenant_id) {
                Tenants::where('id', $user->tenant_id)->delete();
            } else {
                $user->delete();
            }

            return redirect()->route('backend.register')
                ->with('error', 'Tautan verifikasi telah kedaluwarsa. Data pendaftaran Anda telah dihapus otomatis, silakan daftar ulang.');
        }

        DB::transaction(function () use ($user) {
            if ($user->tenant_id) {
                Tenants::where('id', $user->tenant_id)->update([
                    'status' => true,
                ]);
            }

            $user->update([
                'status'             => 1,
                'email_verified_at'  => now(),
                'verification_token' => null,
            ]);
        });

        // return redirect()->route('backend.login')
        //     ->with('success', 'Verifikasi email berhasil! Akun apotek Anda telah aktif, silakan login.');
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('backend.beranda')
            ->with('success', 'Verifikasi email berhasil! Selamat datang di Sobat Apotek.');
    }

    public function updateTenant(Request $request)
    {
        $user = Auth::user();

        if ($user->role != '2' || !$user->tenant_id) {
            return back()->with('error', 'Akses ditolak. Hanya pemilik apotek yang dapat mengubah profil apotek.');
        }

        $request->validate([
            'nama_tenant'      => 'required|string|max:255',
            'telepon'   => 'required|string|max:20',
            'alamat'    => 'required|string',
            'status' => 'required|in:0,1',
        ], [
            'nama_tenant.required'    => 'Nama apotek wajib diisi.',
            'telepon.required' => 'Nomor telepon wajib diisi.',
            'alamat.required'  => 'Alamat lengkap wajib diisi.',
        ]);

        $tenant = Tenants::find($user->tenant_id);

        if (!$tenant) {
            return back()->with('error', 'Data apotek tidak ditemukan.');
        }

        $cleanSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->nama_tenant), '-'));
        $slug      = $cleanSlug . '-' . bin2hex(random_bytes(2));

        DB::transaction(function () use ($request, $tenant, $slug) {
            $statusBaru = (int) $request->status;

            $tenant->update([
                'nama_tenant'      => $request->nama_tenant,
                'slug'      => $slug,
                'telepon'   => $request->telepon,
                'alamat'    => $request->alamat,
                'status' => $statusBaru,
            ]);

            if ($statusBaru === 0) {
                $stafIds = User::where('tenant_id', $tenant->id)
                    ->where('role', '!=', '2')
                    ->pluck('id');
    
                User::whereIn('id', $stafIds)->update(['status' => 0]);
    
                if (config('session.driver') === 'database') {
                    DB::table('sessions')->whereIn('user_id', $stafIds)->delete();
                }
            }
        });

        return back()->with('success', 'Profil apotek berhasil diperbarui.');
    }

    public function destroyTenant(Request $request)
    {
        $user = Auth::user();

        if ($user->role != '2' || !$user->tenant_id) {
            return back()->with('error', 'Akses ditolak. Hanya pemilik apotek yang dapat menghapus akun apotek.');
        }

        $request->validate([
            'password_konfirmasi' => 'required',
        ], [
            'password_konfirmasi.required' => 'Masukkan kata sandi Anda untuk konfirmasi penghapusan.',
        ]);

        if (!Hash::check($request->password_konfirmasi, $user->password)) {
            return back()->withErrors(['password_konfirmasi' => 'Kata sandi yang Anda masukkan salah.']);
        }

        $tenantId = $user->tenant_id;
        $tenant = Tenants::find($tenantId);

        if (!$tenant) {
            return back()->with('error', 'Data apotek tidak ditemukan.');
        }

        $allUsersInTenant = User::where('tenant_id', $tenantId)->get();
        foreach ($allUsersInTenant as $member) {
            if ($member->foto) {
                $oldImagePath = public_path('storage/img-user/') . $member->foto;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $tenant->delete();

        return redirect()->route('backend.login')
            ->with('success', 'Akun apotek dan seluruh data terkait berhasil dihapus secara permanen.');
    }
}
