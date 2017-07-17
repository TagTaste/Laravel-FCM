<?php

namespace App\Http\Controllers\Api\Profile;

use App\Events\DeleteFeedable;
use App\Http\Controllers\Api\Controller;
use App\Recipe;
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
        foreach ($recipes as $recipe) {
            $r = $recipe->toArray();
            $r['meta'] = $recipe->getMetaFor($loggedInProfileId);
            $this->model[] = $r;
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

        $this->model = $this->model->create($inputs);

        $images = [];
        if ($request->has("images")) {
            $count = count($request->input("images"));
            while ($count >= 0) {
                $imageName = str_random("32") . ".jpg";
                $path = "profile/recipes/{$this->model->id}/images/{$count}";
                \Storage::makeDirectory($path);
                if (!$request->hasFile("images.[$count][file]")) {
                    \Log::info("No file for images .[$count][file]");
                    $count--;
                    continue;
                }
                $response = $request->file("images.[$count][file]")->storeAs($path, $imageName);
                if (!$response) {
                    throw new \Exception("Could not save image " . $imageName . " at " . $path);
                }
                $images = ['recipe_id' => $this->model->id, 'image' => $imageName, 'showCase' => "images.[$count][showCase]"];
                $count--;
            }
        }

        $this->model->images()->insert($images);

        $ingredients = $request->input("ingredients");
        if (count($ingredients) > 0) {
            $toatalIngredient = [];
            foreach ($ingredients as $ingredient) {
                $toatalIngredient[] = ['recipe_id' => $this->model->id, "description" => $ingredient];
            }
        }
        $this->model->ingredients()->insert($toatalIngredient);

        $equipments = $request->input("equipments");
        if (count($equipments) > 0) {
            $totalEquipment = [];
            foreach ($equipments as $equipment) {
                $totalEquipment[] = ['recipe_id' => $this->model->id, "name" => $equipment];
            }
        }
        $this->model->equipments()->insert($totalEquipment);

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
        $loggedInProfileId = $request->user()->profile->id;
        $r = $recipe->toArray();
        $r['meta'] = $recipe->getMetaFor($loggedInProfileId);
        $this->model = $r;
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

        $recipe = Recipe::where('profile_id', $profileId)->where('id', $id)->first();

        if ($recipe === null) {
            throw new \Exception("Recipe doesn't belong to the user.");
        }

        $inputs = $request->except(['ingredients', 'equipments', 'images', '_method', '_token']);
        $inputs['profile_id'] = $profileId;
        $this->model = Recipe::where('id', $id)->where('profile_id', $profileId)->update($inputs);
        $this->model = new Recipe ();
        if ($request->has("images")) {
            $count = count($request->input("images"));
            while ($count >= 0) {
                $imageName = str_random("32") . ".jpg";
                $path = "profile/recipes/{$id}/images/{$count}";
                \Storage::makeDirectory($path);
                if (!$request->hasFile("images.[$count][file]")) {
                    \Log::info("No file for images.[$count][file]");
                    $count--;
                    continue;
                }
                $response = $request->file("images.[$count].[file]")->storeAs($path, $imageName);
                if (!$response) {
                    throw new \Exception("Could not save image " . $imageName . " at " . $path);
                }
                if ("images" . $count['showCase'] != null) {
                    $this->model->images()->where('recipe_id', $id)
                        ->where('id', "images" . [$count]['id'])
                        ->update(['image' => $imageName, 'showCase' => "images" . [$count]['showCase']]);
                } else {
                    $images = ['recipe_id' => $id, 'image' => $imageName, 'showCase' => "images.[$count][showCase]"];
                    $this->model->images()->insert($images);
                }
                $count--;
            }
        }

        $ingredients = $request->input("ingredients");

        if (count($ingredients) > 0) {
            $toatalIngredient = [];
            foreach ($ingredients as $ingredient) {
                if ($ingredient['id']) {
                    $this->model->ingredients()->where('recipe_id', $id)->where('id', $ingredient['id'])
                        ->update(["description" => $ingredient['description']]);
                } else {
                    $toatalIngredient[] = ['recipe_id' => $id, "description" => $ingredient];
                }
            }
            if (count($toatalIngredient) > 0) {
                $this->model->ingredients()->insert($toatalIngredient);
            }
        }

        $equipments = $request->input("equipments");
        if (count($equipments) > 0) {
            $totalEquipment = [];
            foreach ($equipments as $equipment) {
                if ($equipment['id']) {
                    $this->model->ingredients()->where('recipe_id', $id)->where('id', $equipment['id'])->update(["name" => $equipment['name']]);
                } else {
                    $totalEquipment[] = ['recipe_id' => $id, "name" => $equipment];
                }
            }
            if (count($totalEquipment) > 0) {
                $this->model->equipments()->insert($totalEquipment);
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
            throw new \Exception("Recipe doesn't belong to the user.");
        }
        event(new DeleteFeedable($recipe));
        $this->model = $recipe->where('id', $id)->where('profile_id', $profileId)->delete();

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

        }
        return $this->sendResponse();
    }
}
