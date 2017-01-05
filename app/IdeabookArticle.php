<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IdeabookArticle extends Model
{
    public function ideabook()
    {
        return $this->belongsTo('\App\Ideabook');
    }

    public function article()
    {
        return $this->belongsTo('\App\Article');
    }
}
