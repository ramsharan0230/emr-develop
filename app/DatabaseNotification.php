<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatabaseNotification extends Model
{
   protected $table='notifications';
   protected $guarded =['id'];
}
