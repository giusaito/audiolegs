<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    public function user(){
    	return $this->hasMany('App\Laravue\Models\User');
    }
}
