<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class UserShare extends Model
{
    use LogsActivity;
    protected $table = 'tbluserpay';

    protected $guarded = ['fldid'];
    protected $primaryKey = 'fldid';
    protected static $logUnguarded = true;
	public $timestamps = false;


	public function user()
    {
        return $this->belongsTo(CogentUsers::class, 'flduserid', 'id');
    }

    public function sub_category()
    {
        return $this->belongsTo(OtGroupSubCategory::class, 'ot_group_sub_category_id', 'id');
    }
}
