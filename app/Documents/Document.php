<?php


namespace App\Documents;

use App\Events\Searchable;
use App\Interfaces\CreatesDocument;
use App\Interfaces\Document as SearchDocument;
use App\SearchClient;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;

class Document implements Arrayable, CreatesDocument, SearchDocument
{
    public $index = 'api';
    public $type;
    public $id;
    public $body = [];
    public $bodyProperties = [];
    public $model = null;
    
    public static function create(Model $model)
    {
        //get the class.
        $className = "\\App\\Documents\\" . class_basename($model);
        $document = new $className;
        $document->model = $model;
        //set id
        $document->id = $model->id;
        
        //set other attributes
        foreach($document->bodyProperties as $attribute){
            $method = 'getValueOf' . $attribute;
            if(method_exists($document,$method)){
                echo "method name is ".$method."\n";
                $document->body[$attribute] = $document->$method();
                continue;
            }
            echo "1 method name is ".$method."\n";
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
        unset($body['model']);
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
        echo "id is here ".$this->id;
        return $this->id;
    }
    
    //delete document
    public static function delete(Model $model)
    {
        $self = new static();
        $params = [
            'index' => $self->index,
            'type' => $self->type,
            'id' => $model->id
        ];
    
        $client =  SearchClient::get();
        try {
            $response = $client->delete($params);
            \Log::warning("Deleted Document " . $self->type . " (" . $model->id . ")");
            \Log::info($response);
        } catch (\Exception $e){
            \Log::warning("Could not delete document {$self->type} $model->id. " . $e->getMessage());
        }
       
    }
    
    
}
