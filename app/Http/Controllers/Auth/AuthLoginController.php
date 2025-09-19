<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthLoginController extends Controller
{
    public function index(){
        return view('auth.login', [
            'title' => 'Login'
        ]);
    }

    public function store(Request $request) {

        $request->validate([
            'username' => 'required|string|min:3|max:50',
            'password' => 'required|String|min:3'
        ]);

        if (Auth::attempt($request->only('username', 'password'), $request->remember)) {
            if (Auth::user()->role == 'admin') return redirect()->route('dashboard');
            return redirect()->route('landing');
        }

        return back()->with('failed', 'Username atau password salah');
    }

    Public function destroy(Request $request) {

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerate();
        return redirect('/');
    }
}
