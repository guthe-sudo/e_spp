<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tingkat extends Model
{
    protected $guarded = [];

    public function jurusan(){
        return $this->hasOne('App\Jurusan');
    }
}
