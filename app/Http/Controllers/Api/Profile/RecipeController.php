<?php

namespace App\Http\Controllers\Api\Profile;

use App\Cuisine;
use App\Events\Actions\Like;
use App\Events\DeleteFeedable;
use App\Http\Controllers\Api\Controller;
use App\Recipe;
use App\RecipeRating;
use App\RecipeLike;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    protected $model = [];

    public function __construct(Recipe $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $profileId)
    {
        $loggedInProfileId = $request->user()->profile->id;

        $recipes = Recipe::where('profile_id', $profileId)->orderBy('created_at', 'desc')->get();
        $this->model = [];
        foreach ($recipes as $recipe) {
            $this->model[] = ['recipe' => $recipe, 'meta' => $recipe->getMetaFor($loggedInProfileId)];
        }
        return $this->sendResponse();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $profileId = $request->user()->profile->id;
        $inputs = $request->except(['ingredients', 'equipments', 'images', '_token']);
        $inputs['profile_id'] = $profileId;
        $cuisine = Cuisine::where('name', $inputs['cuisine']['name']);
        if(isset($inputs['cuisine']['id'])){
            $cuisine = $cuisine->where('id',$inputs['cuisine']['id']);
        }
        $cuisine = $cuisine->first();
        if (!$cuisine) {
            $cuisine = Cuisine::create($request->input("cuisine"));
        }
        unset($inputs['cuisine']);
        $inputs['cuisine_id'] = $cuisine->id;
        $inputs['directions'] = json_encode($request->input("directions"));
        $this->model = $this->model->create($inputs);

        //save images
        if ($request->has("images")) {
            $images = [];
            $count = count($request->input("images")) - 1;
            
            while ($count >= 0) {
                if (!$request->hasFile("images.$count.file")) {
                    \Log::info("No file for images.$count.file");
                    $count--;
                    continue;
                }
                $imageName = str_random("32") . ".jpg";
                $path = Recipe\Image::getImagePath($this->model->id);
                $response = $request->file("images.$count.file")->storeAs($path, $imageName);
                if (!$response) {
                    \Log::warning("Could not save image " . $imageName . " at " . $path);
                    $count--;
                    continue;
                }
                $images[] = ['recipe_id' => $this->model->id, 'image' => $imageName, 'show_case' => $request->input("images.$count.showCase")];
                $count--;
            }

            $this->model->images()->insert($images);
        }

        //save ingredients
        $ingredients = $request->input("ingredients");
        if (count($ingredients) > 0) {
            foreach ($ingredients as &$ingredient) {
                $ingredient = ['recipe_id' => $this->model->id, "name" => $ingredient];
            }

            $this->model->ingredients()->insert($ingredients);
        }

        //save equipments
        $equipments = $request->input("equipments");
        if (count($equipments) > 0) {
            foreach ($equipments as &$equipment) {
                $equipment = ['recipe_id' => $this->model->id, "name" => $equipment];
            }
            $this->model->equipments()->insert($equipments);
        }

        //refetch model with relationships.
        $this->model->refresh();
        return $this->sendResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $profileId, $id)
    {

        $recipe = Recipe::where('profile_id', $profileId)->where('id', $id)->first();
        if (!$recipe) {
            return $this->sendError("Could not find recipe.");
        }

        $loggedInProfileId = $request->user()->profile->id;

        $meta = $recipe->getMetaFor($loggedInProfileId);
        $recipe = $recipe->toArray();
        $recipe['userRating'] = RecipeRating::where('recipe_id', $id)->where('profile_id', $loggedInProfileId)->first();
        $this->model = ['recipe' => $recipe, 'meta' => $meta];

        return $this->sendResponse();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $profileId, $id)
    {
        $profileId = $request->user()->profile->id;

        $this->model = Recipe::where('profile_id', $profileId)->where('id', $id)->first();

        if ($this->model === null) {
            return $this->sendError("Recipe doesn't belong to the user.");
        }

        $inputs = $request->except(['ingredients', 'equipments', 'images', '_method', '_token']);

        if ($inputs['cuisine']['id'] == null) {
            $cuisineExist = Cuisine::where('name', $inputs['cuisine']['name']);
            if (!$cuisineExist->exists()) {
                $cuisineExist = Cuisine::create($request->input("cuisine"));
            }
            $cuisine = $cuisineExist->first();
            $inputs['cuisine']['id'] = $cuisine->id;
        }
        $cuisineExist = Cuisine::where('id', $inputs['cuisine']['id'])->Where('name', $inputs['cuisine']['name'])->exists();
        if (!$cuisineExist) {
            $cuisine = Cuisine::where('name', $inputs['cuisine']['name'])->first();
            if($cuisine==null){
                $cuisine = Cuisine::create($request->input("cuisine"));
            }
            $inputs['cuisine']['id'] = $cuisine->id;
        }
        $inputs['cuisine_id'] = $inputs['cuisine']['id'];

        $this->model->update($inputs);

        //save images
        if ($request->has("images")) {
            $newImages = [];
            $count = count($request->input("images")) - 1;
            while ($count >= 0) {
                if (!$request->hasFile("images.$count.file")) {
                    \Log::info("No file for images.$count.file");
                    $count--;
                    continue;
                }

                $imageName = str_random("32") . ".jpg";
                $path = Recipe\Image::getImagePath($this->model->id);
                $response = $request->file("images.$count.file")->storeAs($path, $imageName);
                if (!$response) {
                    \Log::warning("Could not save image " . $imageName . " at " . $path);
                    $count--;
                    continue;
                }

                if ($request->input("images.$count.id") != null) {
                    $this->model = $this->model->images()->where('recipe_id', $id)
                        ->where('id', $request->input("images.$count.id"))
                        ->update(['image' => $imageName, 'show_case' => $request->input("images.$count.showCase")]);
                } else {
                    $newImages[] = ['recipe_id' => $id, 'image' => $imageName,
                        'show_case' => $request->input("images.$count.showCase")];
                }
                $count--;
            }
            if (!empty($newImages)) {
                $this->model->images()->insert($newImages);
            }
        }

        //save ingredients
        $ingredients = $request->input("ingredients");
        if (count($ingredients) > 0) {
            $newIngredients = [];
            foreach ($ingredients as $ingredient) {
                if (isset($ingredient['id'])) {
                    $this->model->ingredients()->where('recipe_id', $id)->where('id', $ingredient['id'])
                        ->update(["name" => $ingredient['name']]);
                } else {
                    $newIngredients[] = ['recipe_id' => $id, "name" => $ingredient];
                }
            }
            if (count($newIngredients) > 0) {
                $this->model->ingredients()->insert($newIngredients);
            }
        }

        //save equipments
        $equipments = $request->input("equipments");
        if (count($equipments) > 0) {
            $newEquipments = [];
            foreach ($equipments as $equipment) {
                if ($equipment['id']) {
                    $this->model->equipments()->where('recipe_id', $id)->where('id', $equipment['id'])->update(["name" => $equipment['name']]);
                } else {
                    $newEquipments[] = ['recipe_id' => $id, "name" => $equipment];
                }
            }
            if (count($newEquipments) > 0) {
                $this->model->equipments()->insert($newEquipments);
            }
        }
        return $this->sendResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $profileId, $id)
    {
        $profileId = $request->user()->profile->id;
        $recipe = Recipe::where('profile_id', $profileId)->where('id', $id)->first();

        if ($recipe === null) {
            return $this->sendError("Recipe not found.");
        }
        event(new DeleteFeedable($recipe));
        $this->model = $recipe->delete();

        return $this->sendResponse();
    }

    public function recipeImages($profileId, $id)
    {
        $recipe = Recipe::select('image')->find($id);

        if ($recipe === null) {
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
        if ($photoLike != null) {
            RecipeLike::where('profile_id', $profileId)->where('recipe_id', $id)->delete();
            $this->model['likeCount'] = \Redis::hIncrBy("photo:" . $id . ":meta", "like", -1);

        } else {
            RecipeLike::insert(['profile_id' => $profileId, 'recipe_id' => $id]);

            $this->model['likeCount'] = \Redis::hIncrBy("photo:" . $id . ":meta", "like", 1);
            $recipe = Recipe::find($id);
            event(new Like($recipe, $request->user()->profile));
        }
        return $this->sendResponse();
    }
}
