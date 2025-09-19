<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthRegisterController extends Controller
{
    public function index(){
        return view('auth.register');
    }

    public function store(Request $request) {

        $validated = $request->validate([
            'email'     => 'required|email:rfc,dns|unique:users,email',
            'username'  => 'required|string|min:3|max:50|unique:users,username',
            'phone'     => 'required|string|max:20',
            'address'   => 'required|string|max:255',
            'latitude'  => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'password'  => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'email'                     => $validated['email'],
            'username'                  => $validated['username'],
            'phone'                     => $validated['phone'],
            'address'                   => $validated['address'],
            'latitude'                  => $validated['latitude'],
            'longitude'                 => $validated['longitude'],
            'password'                  => $validated['password'],
        ]);

        Auth::login($user);

        $user->sendEmailVerificationNotification();

        return redirect()->route('verification.notice')->with('success', 'Registrasi berhasil! Silakan cek email untuk verifikasi akun.');
    }

}
