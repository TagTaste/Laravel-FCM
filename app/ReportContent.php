<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportContent extends Model
{
	use SoftDeletes;

    protected $table = 'report_content';

    protected $dates = ['deleted_at'];

    protected $fillable = ['report_type_id','report_type_name','report_comment','payload_id','data_type','data_id','is_active','is_shared','shared_id','reported_profile_id','reported_company_id','profile_id','profile_id','is_active'];

    protected $visible = ['id','report_type_id','report_type_name','report_comment','payload_id','data_type','data_id','is_active','is_shared','shared_id','reported_profile_id','reported_company_id','profile_id','profile_id','is_active'];

}
