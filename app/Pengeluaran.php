<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    protected $guarded = [];

    public function kategori()
    {
        return $this->belongsTo('App\Kategori', 'Pengeluaran');
    }

    public function sub()
    {
        return $this->belongsTo('App\Subkategori', 'Pengeluaran');
    }
}
