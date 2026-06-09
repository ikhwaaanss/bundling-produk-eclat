<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Kemana pengguna akan dialihkan setelah login
     */
    protected $redirectTo = '/dashboard';

    /**
     * Buat instance LoginController baru
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Tampilkan formulir login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Validasi kredensial login
     * Bisa login dengan email atau username
     */
    protected function credentials(Request $request)
    {
        $loginType = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        return [
            $loginType => $request->input('login'),
            'password' => $request->input('password'),
            'is_active' => true
        ];
    }

    /**
     * Dapatkan nama field untuk login
     */
    public function username()
    {
        return 'login';
    }

    /**
     * Validasi form login
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
            'remember' => 'boolean'
        ], [
            'login.required' => 'Email atau username harus diisi',
            'password.required' => 'Password harus diisi'
        ]);
    }

    /**
     * Kirim respons login yang gagal
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect()->back()
            ->withInput($request->only('login', 'remember'))
            ->with('error', 'Email/Username atau password salah, atau akun tidak aktif.');
    }

    /**
     * Logout pengguna
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('sukses', 'Anda telah berhasil logout');
    }
}
