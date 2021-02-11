<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    //Controller untuk Index (Validasi Login)
    public function index(Request $request) {
        // pengecekan pertama jika login sebagai admin
        //return Auth::check();
        if(!Auth::check() && $request->path() != 'login') {
            return redirect('/login');
        }
        if(!Auth::check() && $request->path() == 'login') {
            return view('welcome');
        } 
        
        // Jika sudah login.. akan dilakukan pengecekan jika login sebagai admin
        $user = Auth::user();
        if($user->userType == 'User') {
            return redirect('/login');
        }
        if($request->path() == 'login') {
            return redirect('/coba');
        } 
        return view('welcome');

        // Jika sudah login.. akan dilakukan pengecekan jika login sebagai admin
        $member = Auth::user();
        if($member->userType == 'Admin') {
            return redirect('/login');
        }
        if($request->path() == 'login') {
            return redirect('/coba');
        } 
        return view('welcome');
    }
    //Controller untuk Logout
    public function logout() {
        Auth::logout();
        return redirect('/login');
    }
    //Controller untuk Login User
    public function userLogin (Request $request) {
        //validate admin
       $this->validate($request, [
           'email' => 'bail|required|email',            
           'password' => 'bail|required|min:8',
       ]);
       if(Auth::attempt(['email'=> $request->email, 'password' => $request->password ])) {
           $member = Auth::user();
           if($member->userType == 'User') {
               Auth::logout();
               return response()->json([
                   'msg' => 'Login tidak berhasil'
               ], 401);
           }
           return response()->json([
               'msg' => 'Kamu berhasil Login',
               'user' => $member
           ]);
       }else{
           return response()->json([
               'msg' => 'Login Gagal'
           ], 401);
       }
       }
}
