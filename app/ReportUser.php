<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportUser extends Model
{
	use SoftDeletes;

    protected $table = 'report_user';

    protected $dates = ['deleted_at'];

    protected $fillable = ['report_type_id','report_type_name','report_comment','user_type','user_id','profile_id','is_active'];

    protected $visible = ['id','report_type_id','report_type_name','report_comment','user_type','user_id','profile_id','is_active'];

}
