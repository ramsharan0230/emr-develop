<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class EntImage extends Model
{
    use LogsActivity;
    protected $table = 'ent_images';
    protected $guarded = ['id'];
    protected static $logUnguarded = true;
    protected $primaryKey = 'id';
}
