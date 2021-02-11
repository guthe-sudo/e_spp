<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['roleName', 'permission', 'isAdmin'];

    // public function user()
    // {
    //     return $this->belongsTo('App\User' ,'id_role');
    // }
}

