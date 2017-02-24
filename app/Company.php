<?php

namespace App;

use App\Album;
use App\Company\Address;
use App\Company\Advertisement;
use App\Company\Book;
use App\Company\Patent;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ['name','about','logo','hero_image','phone',
        'email','registered_address','established_on', 'status_id',
        'type','employee_count','client_count','annual_revenue_start',
        'annual_revenue_end',
        'facebook_url','twitter_url','linkedin_url','instagram_url','youtube_url','pinterest_url','google_plus_url'
    ];

    protected $with = ['advertisements','addresses','websites','type','status','awards','albums','patents','books'];


    public static function boot()
    {
        parent::boot();

        static::created(function(Company $company){
            $album = Album::createDefault();

            $company->albums()->attach($album->id);
        });

    }

    public function setEstablishedOnAttribute($value)
    {
        $this->attributes['established_on'] = date("Y-m-d",strtotime($value));
    }

    public function albums()
    {
        return $this->belongsToMany('App\Album','company_albums','company_id','album_id');
    }

    public function awards()
    {
        return $this->belongsToMany('App\Company\Award','company_awards','company_id','award_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function status()
    {
        return $this->belongsTo('App\Company\Status','status_id');
    }

    public function type()
    {
        return $this->belongsTo('App\Company\Type','type');
    }

    public function websites()
    {
        return $this->hasMany('App\Company\Website','company_id','id');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function advertisements()
    {
        return $this->hasMany(Advertisement::class);
    }

    public function patents()
    {
        return $this->hasMany(Patent::class);
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
