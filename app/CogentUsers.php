<?php

namespace App;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;

class CogentUsers extends Authenticatable
{
    use Notifiable, CanResetPassword, LogsActivity;

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    protected $primaryKey = 'id';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['fldfullname', 'fldtitlefullname'];
    protected static $logUnguarded = true;

    public function getFldfullnameAttribute()
    {
        return $this->firstname . ' ' . $this->middlename . ' ' . $this->lastname;
    }

    public function getFldtitlefullnameAttribute()
    {
        return $this->fldcategory . ' ' . $this->firstname . ' ' . $this->middlename . ' ' . $this->lastname;
    }

    public function user_details()
    {
        return $this->hasOne('App\UserDetail', 'user_id');
    }

    public function eapp()
    {
        return $this->hasOne('App\EappUser', 'user_id');
    }

    public function user_group()
    {
        return $this->hasMany('App\UserGroup', 'user_id');
    }

    public function groups()
    {
        return $this->belongsToMany('App\Group', 'user_group', 'user_id', 'group_id');
    }

    public function user_is_superadmin()
    {
        return $this->hasMany('App\UserGroup', 'user_id')->where('group_id', config('constants.role_super_admin'));
    }

    public function getFullNameAttribute()
    {
        return "{$this->signature_name} {$this->signature_title} {$this->firstname} {$this->middlename} {$this->lastname}";
    }

    public function department()
    {
        return $this->belongsToMany('App\Department', 'department_users', 'user_id', 'department_id', 'id', 'fldid');
    }

    public function hospitalDepartment()
    {
        return $this->belongsToMany('App\HospitalDepartment', 'hospital_department_users', 'user_id', 'hospital_department_id', 'id', 'id');
    }

    public function getNmcAttribute($value)
    {
        return $value ?: '';
    }

    public function user_shares()
    {
        return $this->hasMany(UserShare::class, 'flduserid');
    }

    public function user_ledger()
    {
        return $this->hasOne(LedgerUserMap::class, 'user_id', 'id');
    }
}
