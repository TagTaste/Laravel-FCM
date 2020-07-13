<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\IdentifiesOwner;

class PalateOptions extends Model
{
	use IdentifiesOwner, SoftDeletes;

    protected $table = 'palate_options';

    protected $fillable = ['type','has_concentration','concentration','concentration_level','has_point_scale','lower_point_scale','upper_point_scale','created_at','updated_at','deleted_at'];

    protected $visible = ['id','type','has_concentration','concentration','concentration_level','has_point_scale','lower_point_scale','upper_point_scale','created_at','updated_at','deleted_at'];

    protected $append = ['profile'];
}
