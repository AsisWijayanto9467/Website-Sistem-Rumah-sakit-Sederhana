<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if(Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->role == 'admin') {
                return redirect()->route('admin.beranda')->with([
                    'login_success' => 'Selamat Datang ' . Auth::user()->nama
                ]);
            }
            if (Auth::user()->role == 'doctor') {
                return redirect()->route('doctor.dashboard');
            }
            if (Auth::user()->role == 'patient') {
                return redirect()->route('patient.dashboard');
            }
        }

        return back()->with([
            'login_failed' => 'Email atau password salah!'
        ])->withInput();
    }

    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with([
            'logout_success' => 'Anda berhasil melakukan logout'
        ]);
    }
}
