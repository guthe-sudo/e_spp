<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function controllerMethod() {
        return response()->json([
            'msg' => 'ini adalah welcome'
        ]);
    }

    public function test(){
        return response()->json([
            'msg' => 'some error'
        ], 422);
    }
}
