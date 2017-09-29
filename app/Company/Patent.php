<?php

namespace App\Company;

use App\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Patent extends Model
{
    protected $table = 'company_patents';

    protected $fillable = ['title','description','awarded_on','issued_by','number','company_id','url'];
    protected $visible = ['id','title','description','awarded_on','issued_by','number','url','company_id'];

    protected static function boot()
    {
        parent::boot();
        // Order by name ASC
        static::addGlobalScope('company_patents', function (Builder $builder) {
            $builder->orderBy('awarded_on', 'desc');
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
