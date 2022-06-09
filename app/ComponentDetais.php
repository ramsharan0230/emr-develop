<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComponentDetais extends Model
{
    protected $table = 'tblcomponentdetail';
    protected $guarded =['id'];

    public  function component(){
        return $this->hasOne(Componentseperation::class,'id','component_id');

    }
}
