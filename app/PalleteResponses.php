<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\IdentifiesOwner;

class PalleteResponses extends Model
{
	use IdentifiesOwner, SoftDeletes;

    protected $table = 'pallete_responses';

    protected $fillable = ['iteration_id','profile_id','pallete_option_id','result','point_scale_result','created_at','updated_at','deleted_at'];

    protected $visible = ['id','iteration_id','result','pallete_type','has_concentration','concentration_type','concentration_level','status', 'color_code'];

    protected $appends = ['pallete_type','has_concentration','concentration_type','concentration_level','status','color_code'];

    protected $with = ['pallete'];

    /**
     * Which pallete options.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pallete()
    {
        return $this->belongsTo(\App\PalleteOptions::class, 'pallete_option_id', 'id');
    }

    public function getPalleteTypeAttribute()
    {
        return $this->pallete->type;
    }

    public function getHasConcentrationAttribute()
    {
        return $this->pallete->has_concentration;
    }

    public function getConcentrationTypeAttribute()
    {
        return $this->pallete->concentration;
    }

    public function getConcentrationLevelAttribute()
    {
        return $this->pallete->concentration_level;
    }

    public function getStatusAttribute()
    {
        if (!is_null($this->pallete->has_point_scale) && $this->pallete->has_point_scale && !is_null($this->point_scale_result)) {
            switch ($this->point_scale_result) {
                case 1:
                    return 'Extremely low'; //'Barely Detectable'
                    break;
                case 2:
                    return 'Very low'; //'Weak
                    break;
                case 3:
                    return 'Low'; //'Mild'
                    break;
                case 4:
                    return 'Medium'; //'Moderate'
                    break;
                case 5:
                    return 'High'; //'Intense'
                    break;
                case 6:
                    return 'Very high'; //'Very Intense'
                    break;
                case 7:
                    return 'Extremely high'; //'Extremely Intense'
                    break;
                default:
                    return null;
                    break;
            }
        } else if (!is_null($this->pallete->has_concentration) && $this->pallete->has_concentration) {
            switch ($this->pallete->concentration) {
                case "0.01%":
                    return 'Very high'; 
                    break;
                case "0.1%":
                    return 'High'; 
                    break;
                case "1%":
                    return 'Medium';
                    break;
                case "10%":
                    return 'Low';
                    break;
                default:
                    return null;
                    break;
            }
        }
        return null;
    }

    public function getColorCodeAttribute()
    {
        if (!is_null($this->pallete->has_point_scale) && $this->pallete->has_point_scale && !is_null($this->point_scale_result)) {
            switch ($this->point_scale_result) {
                case 1:
                    return '#fffeee'; //'Barely Detectable'
                    break;
                case 2:
                    return '#fffeee'; //'Weak
                    break;
                case 3:
                    return '#fffeee'; //'Mild'
                    break;
                case 4:
                    return '#fffeee'; //'Moderate'
                    break;
                case 5:
                    return '#fffeee'; //'Intense'
                    break;
                case 6:
                    return '#fffeee'; //'Very Intense'
                    break;
                case 7:
                    return '#fffeee'; //'Extremely Intense'
                    break;
                default:
                    return null;
                    break;
            }
        } else if (!is_null($this->pallete->has_concentration) && $this->pallete->has_concentration) {
            switch ($this->pallete->concentration) {
                case "0.01%":
                    return '#fffeee'; 
                    break;
                case "0.1%":
                    return '#fffeee'; 
                    break;
                case "1%":
                    return '#fffeee';
                    break;
                case "10%":
                    return '#fffeee';
                    break;
                default:
                    return null;
                    break;
            }
        }
        return null;
    }

}
