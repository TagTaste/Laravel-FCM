<?php

namespace App\Http\Controllers\Api\Profile;

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
    public function index(Request $request,$profileId)
    {
        $ideabooks = Ideabook::profile($profileId)->get();
        $this->model = [];
        if($ideabooks->count() ){
            foreach($ideabooks as $ideabook){
                $temp = $ideabook->toArray();
                $temp['meta'] =  $ideabook->getMetaFor($profileId);
                $this->model['tagboards'][] = $temp;
            }
        }
        
        $this->model['similar'] = Ideabook::similar($profileId,$request->user()->profile->id);
    
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
        $ideabook = Ideabook::where('id',$id)->profile($profileId)->first();
        $this->model = $ideabook->toArray();
        $this->model['meta']=$ideabook->getMetaFor($profileId);

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
    {
        $this->model = $request->user()->ideabooks()
                ->where('id',$id)
                ->update($request->except(['_method','_token']));
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

    public function like(Request $request, $profileId, $id)
    {
        $profileId = $request->user()->profile->id;

        $ideabookLike = IdeabookLike::where('profile_id', $profileId)->where('ideabook_id', $id)->first();
        if($ideabookLike != null) {
            $this->model = IdeabookLike::where('profile_id', $profileId)->where('ideabook_id', $id)->delete();
            \Redis::hIncrBy("ideabook:" . $id . ":meta","like",-1);

        } else {
            $this->model = IdeabookLike::insert(['profile_id' => $profileId, 'ideabook_id' => $id]);
            \Redis::hIncrBy("ideabook:" . $id . ":meta","like",1);

        }
        return $this->sendResponse();
    }
}
