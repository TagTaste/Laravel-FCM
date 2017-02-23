<?php

namespace App\Http\Controllers\Api;

use App\Http\Api\Response;
use App\Http\Controllers\Controller;
use App\Profile;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $requests = $request->user();
        return response()->json($requests);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $profile = \App\User::whereHas('profile',function($query) use ($id) {
            $query->where('id','=',$id);
        })->first();

        return $profile;
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
        $data = array_filter($request->input('profile'));
        //update user name
        if(!empty($data['name'])){
            $name = array_pull($data, 'name');
            $request->user()->update(['name'=>$name]);
        }
        if(!empty($data['image'])){
            $client = new Client();
            $imageName = str_random(32) . ".jpg";
            $response = $client->request("POST", 'http://website.app/api/ramukaka/filedena',[
                'form_params' => [
                    'token' => 'ZZ0vWANeIksiv07HJK5Dj74y%@VjwiXW',
                    'file' => $data['image']
                ],
                'sink'=> Profile::getImagePath($id, $imageName)
            ]);
            $data['image'] = $imageName;
        }

        if(!empty($data['hero_image'])){

            $client = new Client();
            $imageName = str_random(32) . ".jpg";
            $response = $client->request("POST", 'http://website.app/api/ramukaka/filedena',[
                'form_params' => [
                    'token' => 'ZZ0vWANeIksiv07HJK5Dj74y%@VjwiXW',
                    'file' => $data['hero_image']
                ],

                'sink'=> Profile::getHeroImagePath($id, $imageName)
            ]);
            $data['hero_image'] = $imageName;
        }
        $profile = $request->user()->profile()->update($data);
        $response = new Response($profile);

        return $response->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function image($id)
    {

        $profile = Profile::select('image')->findOrFail($id);

        return response()->file(Profile::getImagePath($id,$profile->image));
    }

    public function heroImage($id)
    {
        $profile = Profile::select('id','hero_image')->findOrFail($id);

        return response()->file(Profile::getHeroImagePath($id,$profile->hero_image));
    }

    public function dishImages($id)
    {
        $profile = Profile::select('hero_image')->findOrFail($id);
        return response()->file(storage_path("app/" . $profile->hero_image));
    }

    public function follow(Request $request)
    {
        $id = $request->input('id');
        $request->user()->profile->follow($id);
        //have a better response.
        return response()->json(['success'=>'done']);
    }

    public function userloggedIn()
    {
      $loggedinId = Auth::id();
      return $request->user()->profile->loggedinId;
    }
}
