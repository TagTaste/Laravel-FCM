<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Api\Response;
use App\Http\Controllers\Controller;
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
        $tagboards = Ideabook::profile($profileId)->get();
        $response = new Response($tagboards);
        return $response->json();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tagboard = $request->user()->ideabooks->create($request->only(['name','description']));
        $response = new Response($tagboard);
        return $response->json();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($profileId,$id)
    {
        $tagboard = Ideabook::where('id',$id)->profile($profileId)->first();

        if(!$tagboard){
            throw new \Exception("Tag Board not found.");
        }

        $response = new Response($tagboard);
        return $response->json();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tagboard = $request->user()->ideabooks
                ->where('id',$id)->udpate($request->only(['name','description']));
        $response = new Request($tagboard);
        return $response->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $tagboard = $request->user()->ideabooks()
            ->where('id',$id)->delete();
        $response = new Request($tagboard);
        return $response->json();
    }
}
