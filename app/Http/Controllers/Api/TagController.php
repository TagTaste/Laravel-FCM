<?php

namespace App\Http\Controllers\Api;

use App\Ideabook;
use App\Photo;
use App\Profile;
use Illuminate\Http\Request;

class TagController extends Controller
{
    private function getTagboard(&$request,$tagboardId){
        \Log::info($tagboardId);
        return $request->user()->ideabooks()->find($tagboardId);
    }
    
    public function profile(Request $request, $profileId, $tagboardId)
    {
        $tagboard = $this->getTagboard($request,$tagboardId);

        if(!$tagboard){
            $this->errors[] = ["Tagboard doesn't exist or the user doesn't belong to the tagboard."];
            return $this->sendResponse();
        }
        
        if(Profile::find($profileId) === null){
            $this->errors[] = ["Profile doesn't exist."];
            return $this->sendResponse();
        }
        
        $alreadyTagged = $tagboard->where('id',$tagboardId)->alreadyTagged('profiles',$profileId)->first();

        $response = $alreadyTagged === null ? $tagboard->tag('profiles',$profileId) : $tagboard->untag('profiles',$profileId);
        $this->model['tagged'] = $response === 1 ? false : true;
        return $this->sendResponse();
    }
    
    public function photo(Request $request, $profileId, $albumId, $photoId, $tagboardId)
    {
        $tagboard = $this->getTagboard($request,$tagboardId);
    
        if(!$tagboard){
            $this->errors[] = ["Tagboard doesn't exist or the user doesn't belong to the tagboard."];
            return $this->sendResponse();
        }
        
        if(Photo::find($photoId) === null){
            $this->errors[] = ["Photo doesn't exist."];
            return $this->sendResponse();
        }
    
        $alreadyTagged = $tagboard->where('id',$tagboardId)->alreadyTagged('photos',$photoId)->first();
    
        $response = $alreadyTagged === null ? $tagboard->tag('photos',$photoId) : $tagboard->untag('photos',$photoId);
        $this->model['tagged'] = $response === 1 ? false : true;
        return $this->sendResponse();
    }
}
