<?php

namespace App;

// use App\Utils\Helpers;
use Illuminate\Notifications\Notifiable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    use Notifiable;
    use LogsActivity;
    protected $table = 'tbluser';
    protected $guarded = ['flduserid'];
    protected $primaryKey = 'flduserid';
    protected $keyType = 'string';
    protected static $logUnguarded = true;

    /**
     * The attributes that are mass assignable.   
     *
     * @var array
     */
    protected $fillable = [
        'fldusername', 'flduserid', 'fldpass',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'fldpass', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @return mixed
     * @var array
     */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];

    public function getAuthPassword()
    {
        return $this->fldpass;
    }

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::addGlobalScope('hospital_department_id', function (Builder $builder) {
    //        if(count(\Auth::guard('admin_frontend')->user()->user_is_superadmin) > 0){
    //           //do nothing
    //        }else{
    //         $builder->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession());
    //        }
    //     });
    // }


}
