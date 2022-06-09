<?php

namespace App;

// use App\Utils\Helpers;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;

class PatientInfo extends Model
{
    use LogsActivity;
    protected $table = 'tblpatientinfo';
    public $timestamps = false;
    protected $primaryKey = 'fldpatientval';
    protected $keyType = 'string';
    protected $appends = ['fldage', 'fldfullname', 'fldrankfullname', 'fldagestyle', 'fulladdress'];
    protected $guarded = [];
    protected static $logUnguarded = true;

    // public function getFldemailAttribute($value)
    // {
    //     return ($value) ? decrypt($value) : null;
    // }

    // public function getFldptbirdayAttribute($value)
    // {
    //     return ($value) ? decrypt($value) : null;
    // }

    // public function getFldcitizenshipnoAttribute($value)
    // {
    //     return ($value) ? decrypt($value) : null;
    // }

    // public function getFldpannumberAttribute($value)
    // {
    //     return ($value) ? decrypt($value) : null;
    // }


    // public function getFldclaimcodeAttribute($value)
    // {
    //     return ($value) ? decrypt($value) : null;
    // }


    // public function getFldnationalidAttribute($value)
    // {
    //     return ($value) ? decrypt($value) : null;
    // }

    // public function getFldrelationAttribute($value)
    // {
    //     return ($value) ? decrypt($value) : null;
    // }

    // public function getFldbirdayAttribute($value)
    // {
    //     return ($value) ? decrypt($value) : null;
    // }
    // public function getFldptnamefirAttribute($value)
    // {
    //     return ($value) ? decrypt($value) : null;
    //     // return $value;
    // }

    // public function getFldptnamelastAttribute($value)
    // {
    //     return ($value) ? decrypt($value) : null;
    //     // return $value;
    // }

    // public function getFldmidnameAttribute($value)
    // {
    //     return ($value) ? strtoupper(decrypt($value)) : null;
    //     // return $value;
    // }

    public function getFldptnamefirAttribute($value)
    {
        return ($value) ? strtoupper($value) : null;
    }

    public function getFldptnamelastAttribute($value)
    {
        return ($value) ? strtoupper($value) : null;
    }

    public function getFldmidnameAttribute($value)
    {
        return ($value) ? strtoupper($value) : null;
    }

    // public function getFldptcontactAttribute($value)
    // {
    //     return ($value) ? decrypt($value) : null;
    // }

    // public function getFldptguardianAttribute($value)
    // {
    //     return ($value) ? decrypt($value) : null;
    // }

    public function getFldageAttribute()
    {
        if (!empty($this->fldptbirday)){
            $date = $this->fldptbirday;
            return \Carbon\Carbon::parse($date)->diffInYears(\Carbon\Carbon::now());
        } else
            return "0 D";
    }

    public function getFldagestyleAttribute()
    {
        if (!empty($this->fldptbirday)){
            $date = $this->fldptbirday;
            $date = \Carbon\Carbon::parse($date)->diff(\Carbon\Carbon::now())->format('%y, %m, %d , %h');
            $date = explode(', ', $date);

            if ($date[0] > 0)
                $date = "{$date[0]} Y";
            elseif ($date[1] > 0)
                $date = "{$date[1]} M";
            elseif ($date[2] > 0)
                $date = "{$date[2]} D";
                elseif ($date[3] > 0)
                $date = "0 D";
            return $date;
        } else{
            return "0 D";
    }
}

    public function getFldfullnameAttribute()
    {
        return ucwords($this->fldptnamefir) . ' ' . ucwords($this->fldmidname) . ' ' . ucwords($this->fldptnamelast);
    }

    public function getFldrankfullnameAttribute()
    {
        $fldrank = '';
        if (\App\Utils\Options::get('system_patient_rank') == 1)
            $fldrank = $this->fldrank;

        return $fldrank . ' ' . ucwords($this->fldptnamefir) . ' ' . ucwords($this->fldmidname) . ' ' . ucwords($this->fldptnamelast);
    }

    public static function getPatientInfo($encounterId)
    {
        return PatientInfo::where('fldpatientval', $encounterId)->first();
    }

    public function encounter()
    {
        return $this->hasMany('App\Encounter', 'fldpatientval', 'fldpatientval');
    }

    public function latestEncounter()
    {
        return $this->hasOne('App\Encounter', 'fldpatientval', 'fldpatientval')->orderBy('fldregdate', 'DESC');
    }

    public function district()
    {
        return $this->belongsTo(Municipal::class, 'fldptadddist', 'flddistrict');
    }

    public function municipality()
    {
        return $this->belongsTo(Municipal::class, 'fldmunicipality', 'fldpality');
    }

    public function age()
    {
        $date = $this->fldptbirday;
        return \Carbon\Carbon::parse($date)->diffInYears(\Carbon\Carbon::now());
    }

    public function fullName()
    {
        return ucwords($this->fldptnamefir) . ' ' . ucwords($this->fldmidname) . ' ' . ucwords($this->fldptnamelast);
    }

    public function getFullNameAttribute()
    {
        $fullname = ucwords($this->fldptnamefir) . ' ';
        if ($this->fldmidname != null) {
            $fullname .= ucwords($this->fldmidname) . ' ';
        }
        if ($this->fldptnamelast != null) {
            $fullname .= ucwords($this->fldptnamelast) . ' ';
        }
        return $fullname;
    }

    public function image()
    {
        return $this->hasMany('App\PersonImage', 'fldname', 'fldpatientval');
    }

    public function latestImage()
    {
        return $this->hasOne('App\PersonImage', 'fldname', 'fldpatientval')->orderBy('fldtime', 'DESC');
    }

    public function credential()
    {
        return $this->hasOne(PatientCredential::class, 'fldpatientval', 'fldpatientval');
    }

    public function getFullAddress()
    {
        $address = '';
        if ($this->fldptaddvill || $this->fldmunicipality) {
            $address .= ($this->fldptaddvill) ? $this->fldptaddvill . ', ' : '';
            $address .= $this->fldmunicipality;
            if ($this->fldwardno)
                $address .= '-' . $this->fldwardno;
            $address .= ($address) ? "{$address}, " : '';
        }
        $address .= $this->fldptadddist;
        return $address;
    }

    public function getFullAddressAttribute()
    {
        $address = '';
        if ($this->fldptaddvill || $this->fldmunicipality) {
            $address .= ($this->fldptaddvill) ? $this->fldptaddvill . ', ' : '';
            $address .= $this->fldmunicipality;
            if ($this->fldwardno)
                $address .= '-' . $this->fldwardno;
            $address = ($address) ? "{$address}, " : '';
        }
        $address .= $this->fldptadddist;
        return ucfirst($address);
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
