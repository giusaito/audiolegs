<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'states';
    protected $guarded = [''];

    public function cities(){
    	return $this->hasMany('App\Laravue\Models\City');
    }
    public function user(){
        return $this->hasOne('App\Laravue\Models\User');
    }
}
