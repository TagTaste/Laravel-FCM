<?php

namespace App\Http\Controllers\Api\Profile;

use App\Events\Actions\Like;
use App\Events\DeleteFeedable;
use App\Http\Controllers\Api\Controller;
use App\Recipe;
use App\RecipeLike;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    protected $model = [];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $profileId)
    {
        $loggedInProfileId = $request->user()->profile->id;
        
        $recipes = Recipe::where('profile_id',$profileId)->orderBy('created_at','desc')->get();
        $this->model = [];
        foreach($recipes as $recipe){
            $this->model[] = ['recipe'=>$recipe,'meta'=>$recipe->getMetaFor($loggedInProfileId)];
        }
        return $this->sendResponse();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $profileId = $request->user()->profile->id;
        $inputs = $request->all();
        $inputs['profile_id'] = $profileId;
        
        //move this to validator.
        if(!$request->hasFile('image')){
            throw new \Exception("Image is required");
        }
    
        $imageName = str_random("32") . ".jpg";
        $path = Recipe::$fileInputs['image'];
        $response = $request->file('image')->storeAs($path,$imageName);
        if(!$response){
            throw new \Exception("Could not save image " . $imageName . " at " . $path);
        }
        $inputs['image'] = $imageName;
        $this->model = Recipe::create($inputs);
        return $this->sendResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $profileId,$id)
    {
        $recipe = Recipe::where('profile_id',$profileId)->where('id',$id)->first();
        $loggedInProfileId = $request->user()->profile->id;
        $this->model = [];
        $this->model['recipe'] = $recipe;
        $this->model['meta'] = $recipe->getMetaFor($loggedInProfileId);
        return $this->sendResponse();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $profileId, $id)
    {
        $profileId = $request->user()->profile->id;
        
        $recipe = Recipe::where('profile_id',$profileId)->where('id',$id)->first();
        
        if($recipe === null){
            throw new \Exception("Recipe doesn't belong to the user.");
        }
        
        if($request->hasFile('image')){
            $imageName = str_random("32") . ".jpg";
            $path = Recipe::$fileInputs['image'];
            $response = $request->file('image')->storeAs($path,$imageName);
            if(!$response){
                throw new \Exception("Could not save image " . $imageName . " at " . $path);
            }
            $inputs['image'] = $imageName;
        }
        
        $this->model = $recipe->where('id',$id)->where('profile_id',$profileId)->update($request->except(['profile_id']));
        
        return $this->sendResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $profileId, $id)
    {
        $profileId = $request->user()->profile->id;
        $recipe = Recipe::where('profile_id',$profileId)->where('id',$id)->first();
    
        if($recipe === null){
            throw new \Exception("Recipe doesn't belong to the user.");
        }
        event(new DeleteFeedable($recipe));
        $this->model = $recipe->where('id',$id)->where('profile_id',$profileId)->delete();
    
        return $this->sendResponse();
    }

    public function recipeImages($profileId, $id)
    {
        $recipe = Recipe::select('image')->find($id);
        
        if($recipe === null){
            throw new ModelNotFoundException("Could not find recipe with id " . $id);
        }
        $path = storage_path("app/" . Recipe::$fileInputs['image'] . "/" . $recipe->image);
        return response()->file($path);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function like(Request $request, $profileId, $id)
    {
        $profileId = $request->user()->profile->id;
        $photoLike = RecipeLike::where('profile_id', $profileId)->where('recipe_id', $id)->first();
        $this->model = [];
        if($photoLike != null) {
            RecipeLike::where('profile_id', $profileId)->where('recipe_id', $id)->delete();
            $this->model['likeCount'] = \Redis::hIncrBy("photo:" . $id . ":meta","like",-1);
    
        } else {
            RecipeLike::insert(['profile_id' => $profileId, 'recipe_id' => $id]);
            $this->model['likeCount'] = \Redis::hIncrBy("photo:" . $id . ":meta","like",1);
            $recipe = Recipe::find($id);
            event(new Like($recipe,$request->user()->profile));
        }
        return $this->sendResponse();
    }
}
