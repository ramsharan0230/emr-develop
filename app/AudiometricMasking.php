<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class AudiometricMasking extends Model
{
    use LogsActivity;
    protected $table = 'audiometric_maskings';
    protected $guarded = ['id'];
    protected $primaryKey = 'id';

    protected static $logUnguarded = true;
}
