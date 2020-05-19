<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Notifications\Notifiable;
use Notification;
class FieldLogin extends Model
{
    use LogsActivity, Notifiable;
    protected $table = 'field_login';
    protected $fillable = [  	
                        'user_id',
                        'logindate',
                        'logintime',
                        'latitude',
                        'longitude',
                        'address',
                        'remark',
                        'tlapproval',
                        'adminapproval',
                        'logout',
                        'logout_lat',
                        'logout_long',
                        'logout_address',
                        'status',
                        'hrapproval'
                    ];
    protected static $causerId = 3;
    protected static $logName = "";
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function user()
    {
        return $this->hasOne("App\User",'id','user_id');
    }
    

}
