<?php

namespace App\Http\Controllers\Api;

use App\Dish;
use Illuminate\Http\Request;

class DishController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->model = Dish::orderBy('created_at')->get();
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
        $this->model = Dish::where('id',$id)->first();
        return $this->sendResponse();
    }

    public function dishImages($id)
    {
        $dish = Dish::select('image')->findOrFail($id);
        $path = storage_path("app/" . Dish::$fileInputs['image'] . "/" . $dish->image);
        return response()->file($path);
    }
}
