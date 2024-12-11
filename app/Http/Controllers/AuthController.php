<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function create()
    {
        return view('auth.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken('auth_token')->plainTextToken;

            return redirect()->intended('/')
                ->with('success', 'You are logged in!')
                ->cookie(
                    'auth_token',
                    $token,
                    60 * 24, // 1 day
                    '/',
                    '.vercel.app', // Ensure correct domain
                    true, // Secure
                    true, // HttpOnly
                    false, // SameSite 'lax'
                );
        }

        return redirect()->back()->with('error', 'Invalid credentials');
    }



    public function destroy()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    }
}
