<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class Playlist extends Model
{
	protected $table = 'playlists';
	protected $guarded = [''];

	public function user(){
    	return $this->belongsTo('App\Laravue\Models\User','author_id','id');
	}
	public function audios(){
		return $this->belongsToMany('App\Laravue\Models\Law')->where('type', 'file'); 
	}
}
