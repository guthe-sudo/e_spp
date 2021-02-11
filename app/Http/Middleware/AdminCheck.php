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
        if($request->path() == 'app/admin_login') {
            return $next($request);    
        }
        if(!Auth::check()){
            return response()->json([
                'msg' => 'kamu tidak berhak mengakses halaman ini',
                'url' => $request->path()
            ],402);
        }
        $user = Auth::user();
            if($user->role->isAdmin == "Tidak"){
                return response()->json([
                    'msg' => 'kamu tidak berhak mengakses halaman ini'
                ],402);
            };

        return $next($request);
    }
}   
