<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UserCheck
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
            $member = Auth::user();
            if($member->userType == 'User') {
                Auth::check();
                return response()->json([
                    'msg' => 'Kamu berhasil Login',
                    'user' => $member
                ]);
            }
            else if($member->userType == 'Admin') {
                Auth::logout();
                return response()->json([
                    'msg' => 'Login Gagal Kadul',
                ], 401);
            }   
        }
        if(!Auth::check()){
            return response()->json([
                'msg' => 'kamu tidak berhak mengakses halaman ini'
            ],402);
        }
        $member = Auth::user();
            if($member->userType=='Admin'){
                return response()->json([
                    'msg' => 'kamu tidak berhak mengakses halaman ini'
                ],402);
            };

        return $next($request);
    }
}
