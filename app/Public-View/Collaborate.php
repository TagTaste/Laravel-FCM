<?php

namespace App;

use App\Channel\Payload;
use App\Collaboration\Collaborator;
use App\Interfaces\Feedable;
use App\Traits\CachedPayload;
use App\Traits\IdentifiesOwner;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collaborate extends Model
{
    use IdentifiesOwner, SoftDeletes;

    protected $visible = ['id','title', 'i_am', 'looking_for',
        'expires_on','video','location','categories',
        'description','project_commences','images',
        'duration','financials','eligibility_criteria','occassion',
        'profile_id', 'company_id','template_fields','template_id','notify','privacy_id',
        'profile','company','created_at','deleted_at',
        'file1','deliverables','start_in','state','updated_at'];

    /**
     * Which profile created the collaboration project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class);
    }

    /**
     * Which company created the collaboration project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    /**
     * @param int $profileId
     * @return array
     */
    public function getMetaFor(int $profileId) : array
    {
        $meta = [];

        $meta['isShortlisted'] = \DB::table('collaborate_shortlist')->where('collaborate_id',$this->id)->where('profile_id',$profileId)->exists();

        $key = "meta:collaborate:likes:" . $this->id;
        $meta['hasLiked'] = \Redis::sIsMember($key,$profileId) === 1;
        $meta['likeCount'] = \Redis::sCard($key);

        $meta['commentCount'] = $this->comments()->count();
        $peopleLike = new PeopleLike();
        $meta['peopleLiked'] = $peopleLike->peopleLike($this->id, 'collaborate' ,request()->user()->profile->id);
        $meta['shareCount']=\DB::table('collaborate_shares')->where('collaborate_id',$this->id)->whereNull('deleted_at')->count();
        $meta['sharedAt']= \App\Shareable\Share::getSharedAt($this);

        $meta['interestedCount'] = (int) \Redis::hGet("meta:collaborate:" . $this->id,"applicationCount") ?: 0;
        $meta['isAdmin'] = $this->company_id ? \DB::table('company_users')
            ->where('company_id',$this->company_id)->where('user_id',request()->user()->id)->exists() : false ;

        return $meta;
    }

    /**
     * @param int $companyId
     * @return array
     */
    public function getMetaForCompany(int $companyId) : array
    {
        $meta = [];
        $meta['interested'] = \DB::table('collaborators')->where('collaborate_id',$this->id)->where('company_id',$companyId)->exists();
        return $meta;
    }

    public function privacy()
    {
        return $this->belongsTo(Privacy::class);
    }

    public function getRelatedKey() : array
    {
        if(empty($this->relatedKey) && $this->company_id === null){
            return ['profile'=>'profile:small:' . $this->profile_id];
        }
        return ['company'=>'company:small:' . $this->company_id];
    }

    public function getImagesAttribute ()
    {

        $images=[];
        if($this->company_id){
            for($i=1;$i<=5;$i++)
            {
                if($this->{"image".$i}!==null)
                {
                    $images[] = !is_null($this->{"image".$i}) ? \Storage::url($this->{"image".$i}) : null;

                }
            }
        }
        else
        {
            for($i=1;$i<=5;$i++)
            {
                if($this->{"image".$i}!==null)
                {
                    $images[] = !is_null($this->{"image".$i}) ? \Storage::url($this->{"image".$i}) : null;
                }
            }
        }

        return $images;
    }

    public function getPreviewContent()
    {
        $profile = isset($this->company_id) ? Company::getFromCache($this->company_id) : Profile::getFromCache($this->profile_id);
        $profile = json_decode($profile);
        $data = [];
        $data['modelId'] = $this->id;
        $data['deeplinkCanonicalId'] = 'share_feed/'.$this->id;
        $data['owner'] = $profile->id;
        $data['title'] = $profile->name. ' is looking for '.substr($this->title,0,65);
        $data['description'] = substr($this->description,0,155);
        $data['ogTitle'] = $profile->name. ' is looking for '.substr($this->title,0,65);
        $data['ogDescription'] = substr($this->description,0,155);
        $images = $this->getImagesAttribute();
        $data['cardType'] = isset($images[0]) ? 'summary_large_image':'summary';
        $data['ogImage'] = isset($images[0]) ? $images[0]:'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/share/share-collaboration-big.png';
        $data['ogUrl'] = env('APP_URL').'/preview/collaborate/'.$this->id;
        $data['redirectUrl'] = env('APP_URL').'/collaborate/'.$this->id;

        return $data;

    }

    public function getStateAttribute($value)
    {
        if($value == 1)
            return 'Active';
        else if($value == 3)
            return 'Expired';
        else
            return 'Delete';
    }

}
