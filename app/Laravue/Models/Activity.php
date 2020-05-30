<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
	protected $table = 'bw_activity_log';
   	protected $fillable = ['*'];

   	 public function getUser(){
       return $this->hasOne('App\User','id','causer_id');
   }

   public function getSubject(){
       return $this->hasOne('App\User','id','causer_id');
   }

}
