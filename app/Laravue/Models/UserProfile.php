<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
	// protected $table = ['user_profiles'];

	protected $guarded = [''];


    public function user(){
    	return $this->hasMany('App\Laravue\Models\User');
    }
}
