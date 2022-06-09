<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class BillRemark extends Model
{


    use LogsActivity;
    public $timestamps = false;
    protected $table = 'tblbillremark';

    protected $guarded = [];

    protected static $logUnguarded = true;

    protected $primaryKey = 'id';

    
}
