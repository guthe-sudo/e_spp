<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Spp extends Model
{
    protected $guarded = [];

    
    public function tahun_akademik()
    {
        return $this->belongsTo('App\Tahun_akademik', 'Spp');
    }

    public function tingkat()
    {
        return $this->belongsTo('App\Tingkat', 'Spp');
    }
}
