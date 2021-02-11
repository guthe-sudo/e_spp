<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $guarded = [];

    public function jurusan()
    {
        return $this->belongsTo('App\Jurusan', 'Major');
    }

    public function tingkat()
    {
        return $this->belongsTo('App\Tingkat', 'Major');
    }
}
