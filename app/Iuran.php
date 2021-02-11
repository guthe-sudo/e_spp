<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Iuran extends Model
{
    protected $guarded = [];

    public function tahun_akademik()
    {
        return $this->belongsTo('App\Tahun_akademik', 'Iuran');
    }

    public function tingkat()
    {
        return $this->belongsTo('App\Tingkat', 'Iuran');
    }
}
