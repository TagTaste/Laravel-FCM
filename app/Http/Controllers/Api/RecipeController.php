<?php

namespace App\Http\Controllers\Api;

use App\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->model = Recipe::orderBy('created_at')->get();
        return $this->sendResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->model = Recipe::where('id',$id)->first();
        return $this->sendResponse();
    }
    
    /**
     * Return recipe image.
     * @param $id
     * @return mixed
     */
    public function dishImages($id)
    {
        $recipe = Recipe::select('image')->findOrFail($id);
        $path = storage_path("app/" . Recipe::$fileInputs['image'] . "/" . $recipe->image);
        return response()->file($path);
    }
}
