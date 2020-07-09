<?php

namespace App\Laravue\Models\Laws;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class Law extends Model
{
	protected $table = 'laws';
   	protected $fillable = ['*'];

    public function files(){
    	return $this->hasMany('App\Laravue\Models\Laws\File');
    }
}
