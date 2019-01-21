<?php

namespace App\Channel;

use App\Channel;
use App\PublicReviewProduct;
use App\Shareable\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payload extends Model
{
    use SoftDeletes;
    
    protected $table = 'channel_payloads';
    
    protected $fillable = ['channel_name', 'payload','model','model_id'];
    
    public static function boot()
    {
        parent::boot();
        
        self::created(function(Payload $payload){
            $payload->publish();
        });
        
    }
    
    private function getType()
    {
        $exploded = explode('\\',$this->model);
        return strtolower(end($exploded));
    }
    
    private function publish()
    {
        try {
            //payload has all the required keys, stored as json.
                $cached = json_decode($this->payload,true);
            
            //get all cached objects at once
                $objects = \Redis::mget(array_values($cached));
            
            //Making a string instead of using objects/arrays (not using json_encode/decode).
            //Why decode an object just to prefix a key to it, and then re-encode it?
    
            //build the json string.
                $index = 0;
                $numberOfCachedItems = count($cached);
                
                //meta that is not a part of Model's meta, but Yadav ji asks for.
                $additionalMeta = [];
                
                //start json
                $jsonPayload = "{";
                    foreach($cached as $name => $key){
                        //name : object
                        if(!$objects[$index]){
                            throw new \Exception($name . " not in cache (" . $key . ")");
                        }
                        $jsonPayload .= "\"{$name}\":"  . $objects[$index];
                        if($name === 'sharedBy'){
                            $additionalMeta['sharedAt'] = $this->created_at;
                            //bad me change krna h jald bazi me likha hua h
                            if($this->getType() == 'product')
                            {
                                $product = Product::where('id',$this->model_id)->first();
                                $meta = $product->getMetaFor();
                                $additionalMeta['overall_rating'] = $meta['overall_rating'];
                                $additionalMeta['current_status'] = $meta['current_status'];
                            }
                        }
                        //separate with comma
                        if($index<$numberOfCachedItems-1){
                            $jsonPayload .= ",";
                        }
                        
                        //next object please.
                        $index++;
                    }
                    
                //add to things to json
                if(!empty($additionalMeta)){
                    $jsonPayload .= ",\"meta\":{";
                        foreach($additionalMeta as $key => $value){
                            $jsonPayload .= "\"$key\":\"$value\"";
                        }
                    $jsonPayload .= "}";
                }
                
                $jsonPayload .= ",\"type\":\"" . $this->getType() ."\"";
                //end json
                $jsonPayload .= "}";
            //publish
            \Redis::publish($this->channel->name, $jsonPayload);
            
            //\Redis::sAdd($this->channel->name,json_encode($object));
    
        } catch (\Exception $e){
            \Log::warning("Could not publish.");
            \Log::info($e->getMessage());
            \Log::info($e->getFile() . " " . $e->getLine() . " " . $e->getCode());
        }
    }
    
    public function channel()
    {
        return $this->belongsTo(Channel::class,'channel_name','name');
    }
    
    public function setPayloadAttribute($data)
    {
        $this->attributes['payload'] = json_encode($data);
    }
}
