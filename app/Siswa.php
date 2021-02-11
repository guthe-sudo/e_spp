<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $guarded = [];

    
    public function tahun_akademik()
    {
        return $this->belongsTo('App\Tahun_akademik', 'Siswa');
    }

    public function tingkat()
    {
        return $this->belongsTo('App\Tingkat', 'Siswa');
    }

    public function jurusan()
    {
        return $this->belongsTo('App\Jurusan', 'Siswa');
    }
}
