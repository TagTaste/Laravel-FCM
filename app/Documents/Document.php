<?php


namespace App\Documents;

use App\Events\Searchable;
use App\Interfaces\Document as SearchDocument;
use App\Interfaces\CreatesDocument;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;

class Document implements Arrayable, CreatesDocument, SearchDocument
{
    public $index = 'users';
    public $type;
    public $id;
    public $body = [];
    public $bodyProperties = [];
    
    public static function create(Model $model)
    {
        //get the class.
        $className = "\\App\\Documents\\" . class_basename($model);
        $document = new $className;
        
        //set id
        $document->id = $model->id;
        
        //set other attributes
        foreach($document->bodyProperties as $attribute){
            $document->body[$attribute] = $model->{$attribute};
        }
        
        //fire the event.
        try {
            $document->fire();
        }
        catch (\Exception $e){
            //Possibly Search Engine is down.
            \Log::warning($e->getMessage());
        }
    }
    
    public function toArray()
    {
        if($this->type === null){
            throw new \Exception("Type not defined on " . get_class($this));
        }
    
        if($this->id === null){
            throw new \Exception("Id not defined on " . get_class($this));
        }
        
        if(empty($this->body)){
            throw new \Exception("Body is empty for " . get_class($this));
        }
        
        $body = get_object_vars($this);
        //making this private fails somehow,
        //unsetting it like this for now.
        unset($body['bodyProperties']);
        return $body;
    }
    
    public function fire()
    {
        event(new Searchable($this));
    }
    
    /**
     * @return string
     */
    public function getIndex(): string
    {
        if($this->index === 'users'){
            \Log::info("Using default index users");
        }
        return $this->index;
    }
    
    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    
    
}
