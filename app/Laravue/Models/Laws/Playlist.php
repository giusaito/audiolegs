<?php

namespace App\Laravue\Models\Laws;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class Playlist extends Model
{
	protected $table = 'laws_playlists';
   	protected $fillable = ['*'];

}
