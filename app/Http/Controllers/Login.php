<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Login extends Controller {
    public function index() {
        return view('home');
    }

    public function authenticate(Request $request) {
        $credentials = $request->only('email', 'password', 'type');    
        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            $request->session()->put('user_id', $user->id);
            $request->session()->put('user_type', $user->type);
            if ($user->type == 'admin') {
                return redirect()->intended('admin/dashboard');
            } else if ($user->type == 'user') {
                return redirect()->intended('customer/dashboard');
            }
        }
        return back()->withErrors([
            'email' => 'Invalid credentials',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect('/'); 
    }
    
}