<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IdeabookArticle extends Model
{
    use SoftDeletes;

    public function ideabook()
    {
        return $this->belongsTo('\App\Ideabook');
    }

    public function article()
    {
        return $this->belongsTo('\App\Article');
    }
}
