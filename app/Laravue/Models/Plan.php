<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class Plan extends Model
{
	use HasRoles, HasApiTokens, LogsActivity;

    protected $table = 'plans';
    protected $guarded = [''];

    protected static $logFillable = true;
    protected static $logName = 'Planos';

    public function getDescriptionForEvent(string $eventName): string
    {
        if($eventName == 'created'){
            $eventName = 'Plano adicionado';
        } elseif($eventName == 'updated') {
            $eventName = 'Plano atualizado';
        }elseif($eventName == 'deleted') {
            $eventName = 'Plano removido';
        }
        return $eventName;
    }

    public function vouchers()
    {
        return $this->belongsToMany('App\Laravue\Models\Voucher');
    }
}
