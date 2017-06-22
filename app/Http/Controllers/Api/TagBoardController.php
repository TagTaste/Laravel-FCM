<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Ideabook;
use App\IdeabookLike;
use Illuminate\Http\Request;

class TagBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        \Log::info('here');
        $this->model = Ideabook::orderBy('created_at','desc')->paginate(10);
        return $this->sendResponse();
    }

    public function show($id)
    {
        $this->model = Ideabook::where('id',$id)->first();
        return $this->sendResponse();
    }
}
