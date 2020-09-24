<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::attempt(['email'=> $request->email, 'password' => $request->password ])) {
            $user = Auth::user();
            if($user->userType == 'Admin') {
                Auth::check();
                return response()->json([
                    'msg' => 'Kamu berhasil Login',
                    'user' => $user
                ]);
            }
            else if($user->userType == 'User') {
                Auth::logout();
                return response()->json([
                    'msg' => 'Login Gagal'
                ], 401);
            }   
        }
        if(!Auth::check()){
            return response()->json([
                'msg' => 'kamu tidak berhak mengakses halaman ini'
            ],402);
        }
        $user = Auth::user();
            if($user->userType=='User'){
                return response()->json([
                    'msg' => 'kamu tidak berhak mengakses halaman ini'
                ],402);
            };

        return $next($request);
    }
}
