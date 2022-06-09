<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionMaster extends Model
{
    protected $guarded = ['id'];

    public function childs()
    {
        return $this->hasMany(QuestionMaster::class, 'parent_id')->orderBy('order');
    }
}
