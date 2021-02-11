<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];

    public function siswa()
    {
        return $this->belongsTo('App\Siswa', 'Transaction');
    }

    public function spp()
    {
        return $this->belongsTo('App\Spp', 'Transaction');
    }

    public function tahun_akademik()
    {
        return $this->belongsTo('App\Tahun_akademik', 'Transaction');
    }

    public function tingkat()
    {
        return $this->belongsTo('App\Tingkat', 'Transaction');
    }

    public function jurusan()
    {
        return $this->belongsTo('App\Jurusan', 'Transaction');
    }
}
