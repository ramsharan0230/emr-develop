<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class UnsampledTest extends Model
{
    use LogsActivity;
    protected $table = 'tbl_unsampled_test';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected static $logUnguarded = true;
    protected $guarded = [];
}
