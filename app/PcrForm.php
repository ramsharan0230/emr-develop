<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class PcrForm extends Model
{
	use LogsActivity;
	protected $table = 'tblpcrform';
	public $timestamps = false;
}
