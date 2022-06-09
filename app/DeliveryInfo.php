<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class DeliveryInfo extends Model
{
    use LogsActivity;
    protected $table = 'hmis_deliveryinfo';
    protected $guarded = ['id'];
    protected $primaryKey = 'id';
    protected static $logUnguarded = true;
}
