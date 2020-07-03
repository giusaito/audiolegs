<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';
    protected $guarded = [''];

    public function state(){
    	return $this->belongsTo('App\Laravue\Models\State');
    }
    public function user(){
        return $this->hasOne('App\Laravue\Models\User');
    }
}
