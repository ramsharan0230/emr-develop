<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Componentseperation extends Model
{
    protected $table = 'tblcomponentmaster';
    protected $guarded=['id'];

    public function bloodbag()
    {
        return $this->hasOne(Bloodbag::class,'bag_id','bag_id');
    }
}
