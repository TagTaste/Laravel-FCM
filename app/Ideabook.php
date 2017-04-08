<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ideabook extends Model
{
    use SoftDeletes;

    protected $fillable = ['name','description','privacy_id','user_id','keywords','privacy_id'];

    protected $dates = ['deleted_at'];

    //protected $visible = ['id','name','description','profiles','keywords','privacy','profiles.pivot'];
    
    protected $with = ['privacy','profiles'];
    
    public static function boot()
    {
        parent::boot();

        self::deleting(function($ideabook){
            if($ideabook->articles->count()){
                $ideabook->articles->delete();
            }
        });
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

    public function articles()
    {
        return $this->belongsToMany('\App\Article','ideabook_articles','ideabook_id','article_id')
            ->withPivot('note');
    }

    public function albums()
    {
        return $this->belongsToMany('\App\Album','ideabook_albums','ideabook_id','album_id')
            ->withPivot('note');
    }

    public function photos()
    {
        return $this->belongsToMany('\App\Photo','ideabook_photos','ideabook_id','photo_id')
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
         $this->{$relationship}()->updateExistingPivot($modelId,['note'=>$note]);
        return $model;
    }
    
    public function untag($relationship,$modelId)
    {
        return $this->{$relationship}()->detach($modelId);
    }
}
