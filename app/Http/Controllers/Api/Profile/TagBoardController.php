<?php

namespace App\Http\Controllers\Api\Profile;

use Tagtaste\Api\Response;
use App\Http\Controllers\Api\Controller;
use App\Ideabook;
use Illuminate\Http\Request;

class TagBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$profileId)
    {
        $this->model['tagboards'] = Ideabook::profile($profileId)->get();
        $this->model['similar'] = Ideabook::similar();
    
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
        $params = ['privacy_id'=>1];
        $params = array_merge($params,$request->only(['name','description','keywords','privacy_id']));
        $this->model = $request->user()->ideabooks()->create($params);
        return $this->sendResponse();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($profileId,$id)
    {
        $this->model = Ideabook::where('id',$id)->profile($profileId)->first();

        if(!$this->model){
            throw new \Exception("Tag Board not found.");
        }
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
    {\Log::info($request->all());
        $this->model = $request->user()->ideabooks()
                ->where('id',$id)
                ->update($request->intersect(['name','description','keywords','privacy_id']));
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
        $this->model = $request->user()->ideabooks()
            ->where('id',$id)->delete();
        return $this->sendResponse();
    }
}
