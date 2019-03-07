<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faqs extends Model
{
    protected $fillable = ['question','answer','faq_category_id','description'];

    protected $visible = ['id','question','answer','faq_category','faq_category_id','description'];

    protected $with = ['faq_category'];

    protected $table = 'faqs_question_answer';

    public function faq_category()
    {
        return \DB::table('faq_categories')->where('id','faq_category_id')->first();
    }

}
