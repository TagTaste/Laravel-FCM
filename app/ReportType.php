<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportType extends Model
{
	use SoftDeletes;

    protected $table = 'report_type';

    protected $fillable = ['name','is_active','created_at','updated_at','deleted_at'];

    protected $visible = ['id','name'];

}
