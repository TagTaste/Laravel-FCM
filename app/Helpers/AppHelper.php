<?php
namespace app\Helpers;

use Illuminate\Database\Eloquent\Model;

class AppHelper extends Model
{

    public static function instance()
    {
        return new AppHelper();
    }

    Public function saveFileToData($key,$path,&$request,&$data,$extraKey = null)
    {
        if($request->hasFile($key) && !is_null($extraKey)){

            $response = $this->saveFile($path,$request,$key);
            $data[$key] = json_encode($response,true);
            $data[$extraKey] = $response['original_photo'];
        }
    }

    Public function saveFile($path,&$request,$key)
    {
        $imageName = str_random("32") . ".jpg";
        $response['original_photo'] = \Storage::url($request->file($key)->storeAs($path."/original",$imageName,['visibility'=>'public']));
        //create a tiny image
        $path = $path."/tiny/" . str_random(20) . ".jpg";
        $thumbnail = \Image::make($request->file($key))->resize(50, null,function ($constraint) {
            $constraint->aspectRatio();
        })->blur(1)->stream('jpg',70);
        \Storage::disk('s3')->put($path, (string) $thumbnail,['visibility'=>'public']);
        $response['tiny_photo'] = \Storage::url($path);
        $response['meta'] = getimagesize($request->input($key));
        if(!$response){
            throw new \Exception("Could not save image " . $imageName . " at " . $path);
        }
        return $response;
    }
}