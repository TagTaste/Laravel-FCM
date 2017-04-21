<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ideabook extends Model
{
    use SoftDeletes;

    protected $fillable = ['name','description','privacy_id','user_id','keywords','privacy_id'];

    protected $dates = ['deleted_at'];

    protected $visible = ['id','name','description','profiles','keywords','privacy','photos','similar','recipes','products','profileId'];
    
    protected $with = ['privacy','profiles','photos', 'products','recipes'];
    
    //protected $appends = ['similar'];
    
    public static function boot()
    {
        parent::boot();
    }

    public function user()
    {
        return $this->belongsTo('\App\User');
    }

    public function privacy()
    {
        return $this->belongsTo('\App\Privacy');
    }
    
    public function profiles()
    {
        return $this->belongsToMany(\App\Ideabook\Profile::class,'ideabook_profiles','ideabook_id','profile_id')
            ->withPivot('note');
    }
    
    public function products()
    {
        return $this->belongsToMany(\App\Ideabook\Product::class,'ideabook_products','ideabook_id','product_id')
            ->withPivot('note');
    }
    
    public function recipes()
    {
        return $this->belongsToMany(\App\Ideabook\Recipe::class,'ideabook_recipes','ideabook_id','recipe_id')
            ->withPivot('note');
    }

    public function photos()
    {
        return $this->belongsToMany(\App\Ideabook\Photo::class,'ideabook_photos','ideabook_id','photo_id')
            ->withPivot('note');
    }
    
    /**
     * Checks whether the current tagboard belongs to $profileId
     *
     * @param $query
     * @param $profileId
     * @return mixed
     */
    public function scopeProfile($query, $profileId)
    {
       return $query->whereHas('user.profile',function($query) use ($profileId){
           return $query->where('profiles.id',$profileId);
       });
    }
    
    /**
     * Checks whether the current tag has already been attached
     *
     * @param $query
     * @param $profileId
     * @return mixed
     */
    
    public function scopeAlreadyTagged($query, $relationship, $modelId, $columnName = null)
    {
        $columnName = $columnName !== null ?: str_singular($relationship) . "_id";
        return $query->wherehas($relationship,function($query) use ($columnName, $modelId){
            $query->where($columnName,$modelId);
        });
    }
    
    public function tag($relationship,$modelId, $note = null)
    {
        $model = $this->{$relationship}()->attach($modelId);
        $this->updateNote($relationship,$modelId,$note);
        return $model;
    }
    
    public function updateNote(&$relationship, &$modelId, &$note = null)
    {
        return $this->{$relationship}()->updateExistingPivot($modelId,['note'=>$note]);
    }
    
    public function untag($relationship,$modelId)
    {
        return $this->{$relationship}()->detach($modelId);
    }

    public static function similar($profileId, $loggedInUser)
    {
        $similar = self::select('ideabooks.id','name','profiles.id as profileId')
            ->join('profiles','profiles.user_id','=','ideabooks.user_id')
            ->where('profiles.id','!=',$profileId)->where('profiles.id','!=',$loggedInUser)
            ->get();
        return $similar;
    }
}
