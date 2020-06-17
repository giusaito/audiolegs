<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class Voucher extends Model
{
	use HasRoles, HasApiTokens, LogsActivity;

    protected $table = 'vouchers';
    protected $guarded = [''];

    protected static $logFillable = true;
    protected static $logName = 'Cupons';

    public function getDescriptionForEvent(string $eventName): string
    {
        if($eventName == 'created'){
            $eventName = 'Cupom adicionado';
        } elseif($eventName == 'updated') {
            $eventName = 'Cupom atualizado';
        }elseif($eventName == 'deleted') {
            $eventName = 'Cupom removido';
        }
        return $eventName;
    }
}
