<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\IdentifiesOwner;

class PalateResponses extends Model
{
	use IdentifiesOwner, SoftDeletes;

    protected $table = 'palate_responses';

    protected $fillable = ['iteration_id','profile_id','palate_option_id','result','point_scale_result','created_at','updated_at','deleted_at'];

    protected $visible = ['id','iteration_id','result','palate_type','has_concentration','concentration_type','concentration_level','status','color_code','ui_style_meta'];

    protected $appends = ['palate_type','has_concentration','concentration_type','concentration_level','status','color_code','ui_style_meta'];

    protected $with = ['palate'];

    /**
     * Which pallete options.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function palate()
    {
        return $this->belongsTo(\App\PalateOptions::class, 'palate_option_id', 'id');
    }

    public function getPalateTypeAttribute()
    {
        return $this->palate->type;
    }

    public function getHasConcentrationAttribute()
    {
        return $this->palate->has_concentration;
    }

    public function getConcentrationTypeAttribute()
    {
        return $this->palate->concentration;
    }

    public function getConcentrationLevelAttribute()
    {
        return $this->palate->concentration_level;
    }

    public function getStatusAttribute()
    {
        if (!is_null($this->palate->has_point_scale) && $this->palate->has_point_scale && !is_null($this->point_scale_result)) {
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
        } else if (!is_null($this->palate->has_concentration) && $this->palate->has_concentration) {
            switch ($this->palate->concentration) {
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
        if (!is_null($this->palate->has_point_scale) && $this->palate->has_point_scale && !is_null($this->point_scale_result)) {
            switch ($this->point_scale_result) {
                case 1:
                    return '#F6F6F6'; //'Barely Detectable'
                    break;
                case 2:
                    return '#F6F6F6'; //'Weak
                    break;
                case 3:
                    return '#F6F6F6'; //'Mild'
                    break;
                case 4:
                    return '#F6F6F6'; //'Moderate'
                    break;
                case 5:
                    return '#F6F6F6'; //'Intense'
                    break;
                case 6:
                    return '#F6F6F6'; //'Very Intense'
                    break;
                case 7:
                    return '#F6F6F6'; //'Extremely Intense'
                    break;
                default:
                    return null;
                    break;
            }
        } else if (!is_null($this->palate->has_concentration) && $this->palate->has_concentration) {
            switch ($this->palate->concentration) {
                case "0.01%":
                    return '#F6F6F6'; 
                    break;
                case "0.1%":
                    return '#F6F6F6'; 
                    break;
                case "1%":
                    return '#F6F6F6';
                    break;
                case "10%":
                    return '#F6F6F6';
                    break;
                default:
                    return null;
                    break;
            }
        }
        return null;
    }

    public function getUiStyleMetaAttribute()
    {
        $response = array(
            "border_color" => null,
            "background_color" => null
        );
        if (!is_null($this->palate->has_point_scale) && $this->palate->has_point_scale && !is_null($this->point_scale_result)) {
            switch ($this->point_scale_result) {
                case 1:
                    //'Barely Detectable'
                    $response['border_color'] = "#E5E5E5";
                    $response['background_color'] = "#F5F5F5";
                    return $response;
                    break;
                case 2:
                    //'Weak
                    $response['border_color'] = "#E5E5E5";
                    $response['background_color'] = "#F5F5F5";
                    return $response;
                    break;
                case 3:
                    //'Mild'
                    $response['border_color'] = "#E5E5E5";
                    $response['background_color'] = "#F5F5F5";
                    return $response;
                    break;
                case 4:
                    //'Moderate'
                    $response['border_color'] = "#E5E5E5";
                    $response['background_color'] = "#F5F5F5";
                    return $response;
                    break;
                case 5:
                    //'Intense'
                    $response['border_color'] = "#E5E5E5";
                    $response['background_color'] = "#F5F5F5";
                    return $response;
                    break;
                case 6:
                    //'Very Intense'
                    $response['border_color'] = "#E5E5E5";
                    $response['background_color'] = "#F5F5F5";
                    return $response;
                    break;
                case 7:
                    //'Extremely Intense'
                    $response['border_color'] = "#E5E5E5";
                    $response['background_color'] = "#F5F5F5";
                    return $response;
                    break;
                default:
                    return $response;
                    break;
            }
        } else if (!is_null($this->palate->has_concentration) && $this->palate->has_concentration) {
            switch ($this->palate->concentration) {
                case "0.01%":
                    $response['border_color'] = "#E5E5E5";
                    $response['background_color'] = "#F5F5F5";
                    return $response;
                    break;
                case "0.1%":
                    $response['border_color'] = "#E5E5E5";
                    $response['background_color'] = "#F5F5F5";
                    return $response;
                    break;
                case "1%":
                    $response['border_color'] = "#E5E5E5";
                    $response['background_color'] = "#F5F5F5";
                    return $response;
                    break;
                case "10%":
                    $response['border_color'] = "#E5E5E5";
                    $response['background_color'] = "#F5F5F5";
                    return $response;
                    break;
                default:
                    return $response;
                    break;
            }
        }
        return $response;
    }

}
