<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subkategori extends Model
{
    protected $guarded = [];

    public function sub()
    {
        return $this->belongsTo('App\Kategori', 'Subkategori');
    }
}
