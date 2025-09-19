<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailController extends Controller
{
    public function create() {
        return view('emails.verify-email', [
            'title' => 'verify-email'
        ]);
    }

    public function update(Request $request) {
        
        $request->validate([
            'email' => 'required|email:rfc,dns|unique:users,email'
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->email = $request->email;
        $user->email_verified_at = null;
        $user->save();

        $user->sendEmailVerificationNotification();

        return back()->with('success', 'Email berhasil diubah! Silakan cek inbox untuk verifikasi.');
    }

    public function verify(EmailVerificationRequest $request){
        $request->fulfill();

        return redirect('/'); 
    }

    public function resend(Request $request){
        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
