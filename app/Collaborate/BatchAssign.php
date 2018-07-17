<?php

namespace App\Collaborate;

use App\Recipe\Collaborate;
use Illuminate\Database\Eloquent\Model;


class BatchAssign extends Model {

    protected $table = 'collaborate_batches_assign';

    protected $fillable = ['batch_id','profile_id'];

    protected $visible = ['batch_id','profile_id','current_status','batches'];

    protected $with = ['batches'];

    public function batches()
    {
        return $this->belongsTo("App\Collaborate\Batches",'batch_id');
    }

}
