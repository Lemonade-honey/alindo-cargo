<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function login(){
        return view("login");
    }

    public function loginPost(Request $request){
        $request->validate([
            "email" => ["required", "email"],
            "password" => "required"
        ]);

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            if(Auth::user()->block === 1 && Auth::user()->id !== 1){

                Auth::logout();

                return back()->with("error", "akun ini telah di blokir");
            }

            return redirect()->route("dashboard");
        }
        return back()->with("error", "email atau password salah");
    }

    public function logout(){
        Auth::logout();

        return redirect("/");
    }
}
