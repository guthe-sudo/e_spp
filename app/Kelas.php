<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $guarded = [];

    public function tingkat()
    {
        return $this->belongsTo('App\Tingkat', 'Jurusan');
    }
}
