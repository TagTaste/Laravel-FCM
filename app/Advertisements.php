<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\IdentifiesOwner;

class Advertisements extends Model
{
	use IdentifiesOwner, SoftDeletes;

    protected $fillable = ['title','description','video','youtube_url','link','image','cities','payload','model','model_id','profile_id','company_id','is_active','created_at','updated_at','deleted_at','expired_at'
    ];

    protected $visible = ['id','title','description','link','image','payload','model','model_id','profile','company','actual_model', 'type'
    ];

    protected $with = ['profile', 'company'];

    protected $appends = ['actual_model'];

    /**
     * Which profile created the collaboration project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo(\App\V2\Profile::class,'profile_id');
    }

    /**
     * Which company created the collaboration project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(\App\Recipe\Company::class);
    }

    public function getActualModelAttribute()
    {
        switch (strtolower($this->model)) {
            case 'shoutout':
                return "App\Shoutout";
                break;
            case 'polling':
                return "App\Polling";
                break;
            case 'photo':
                return "App\V2\Photo";
                break;
            case 'collaborate':
                return "App\Collaborate";
                break;
            case 'product':
                return "App\PublicReviewProduct";
                break;
            default:
                return null;
                break;
        };
    }
}
