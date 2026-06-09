<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Kemana pengguna akan dialihkan setelah registrasi
     */
    protected $redirectTo = '/dashboard';

    /**
     * Buat instance RegisterController baru
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Tampilkan formulir registrasi
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Validasi data pendaftaran
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nama_lengkap' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'username' => 'required|string|max:50|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'nama_lengkap.required' => 'Nama lengkap harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email harus valid',
            'email.unique' => 'Email sudah terdaftar',
            'username.required' => 'Username harus diisi',
            'username.unique' => 'Username sudah digunakan',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Password tidak sesuai'
        ]);
    }

    /**
     * Buat instance user baru setelah validasi lulus
     */
    protected function create(array $data)
    {
        return User::create([
            'nama_lengkap' => $data['nama_lengkap'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'role' => 'operator',
            'is_active' => true
        ]);
    }
}
