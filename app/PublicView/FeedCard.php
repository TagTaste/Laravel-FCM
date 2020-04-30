<?php

namespace App\PublicView;

use App\Traits\IdentifiesOwner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\FeedCard as BaseFeedCard;
use Illuminate\Support\Facades\Redis;

class FeedCard extends BaseFeedCard
{
    use IdentifiesOwner, SoftDeletes;

    protected $table = 'feed_card';

    protected $fillable = ['data_type','data_id','title','subtitle','name','image','description','icon','is_active','profile', 'company','created_at','updated_at','deleted_at'];

    protected $visible = ['id','title','subtitle','name','image_meta','description','icon','is_active','profile', 'company'];

    protected $appends = ['profile','company','image_meta'];

    /**
     * @param int $profileId
     * @return array
     */
    public function getMetaForPublic()
    {
        $meta = [];
        $meta['type'] = $this->data_type;
        return $meta;
    }
}
