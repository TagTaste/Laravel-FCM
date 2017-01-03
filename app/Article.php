<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title','user_id','profile_type_id','privacy_id','comments_enabled','status','template_id'];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

   	public function author() {
   		return $this->belongsTo('\App\Profile','author_id');
   	}

   	public function privacy(){
   		return $this->belongsTo('\App\Privacy','privacy_id');
   	}

   	public function template() {
   		return $this->belongsTo('\App\Template','template_id');
   	}

      public function dish() {
         return $this->hasOne('\App\DishArticle','article_id');
      }

    public function blog()
    {
        return $this->hasOne('\App\BlogArticle','article_id');
      }

      public function getContent(){
         if($this->dish){
            return $this->dish->content;
         }
      }

      public function hasRecipe() {
         if($this->dish){
            return ($this->dish->recipe);
         }
         return false;
      }

      public function getAuthor() {
         return $this->author->user->name;
      }

}
