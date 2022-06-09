<?php

namespace App;

// use App\Utils\Helpers;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;

class TempPatbillDetail extends Model
{
    use LogsActivity;
    public $timestamps  = false;
    protected $table = 'tbltemppatbilldetail';
    protected $primaryKey = 'fldid';
    protected $guarded = [];
    protected static $logUnguarded = true;

   
}
