<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class Activity extends Model
{
    use Notifiable, HasRoles, HasApiTokens;

	protected $table = 'activity_log';
   	protected $fillable = ['*'];

   	 public function getUser(){
       return $this->hasOne('App\User','id','causer_id');
   }

   public function getSubject(){
       return $this->hasOne('App\User','id','causer_id');
   }

}
