<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OtGroupSubCategory extends Model
{
    protected $guarded = ['id'];

    public function user_shares()
    {
        return $this->hasMany(UserShare::class, 'ot_group_sub_category_id', 'id');
    }
}
