<?php

namespace App\Collaborate;

use Illuminate\Database\Eloquent\Model;


class Batches extends Model {

        protected $table = 'product_review_batches';

    protected $fillable = ['name','notes','allergens','instruction','color_id','collaborate_id'];

    protected $visible = ['id','name','notes','allergens','instruction','color_id','collaborate_id'];

}
