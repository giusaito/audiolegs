<?php
namespace App\Laravue\Models\Laws;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class File extends Model
{
	use NodeTrait;
	protected $table = 'laws_files';
	protected $fillable = ['*'];
	   
	public function folder(){
    	return $this->belongsTo('App\Laravue\Models\Laws\Folder');
    }
}
