<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Kalnoy\Nestedset\NodeTrait;

class Playlist extends Model
{
    use NodeTrait;
	protected $table = 'playlists';
	protected $guarded = [''];
}
