<?php

namespace App\Http\Controllers\Api;

use App\Http\Api\Response;
use App\Http\Controllers\Controller;
use App\Profile;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->json($request->user());
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

        return response()->json($profile);
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
        $data = array_filter($request->all());
        if(!empty($data['name'])){
            $name = $data['name'];
            unset($data['name']);
            $request->user()->update(['name'=>$name]);
        }
        if(!empty($data['image'])){

            $directory = storage_path("app/profile/{$id}/images/");
            if(!file_exists($directory)){
                mkdir($directory,0664,true);
            }

            $client = new Client();
            $imageName = str_random(32) . ".jpg";
            $response = $client->request("POST", 'http://website.app/api/ramukaka/filedena',[
                'form_params' => [
                    'token' => 'ZZ0vWANeIksiv07HJK5Dj74y%@VjwiXW',
                    'file' => $data['image']
                ],
                'sink'=>storage_path("app/profile/{$id}/images/" . $imageName)
            ]);
            $data['image'] = $imageName;
        }

        if(!empty($data['hero_image'])){
            $directory = storage_path("app/profile/{$id}/hero_images/");
            if(!file_exists($directory)){
                mkdir($directory,0664,true);
            }
            $client = new Client();
            $imageName = str_random(32) . ".jpg";
            $response = $client->request("POST", 'http://website.app/api/ramukaka/filedena',[
                'form_params' => [
                    'token' => 'ZZ0vWANeIksiv07HJK5Dj74y%@VjwiXW',
                    'file' => $data['hero_image']
                ],
                'sink'=>storage_path($directory . $imageName)
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
        if(file_exists(storage_path("app/" . $profile->image))){
            return response()->file(storage_path("app/" . $profile->image));
        }
        return;
    }

    public function heroImage($id)
    {
        $profile = Profile::select('hero_image')->findOrFail($id);

        return response()->file(storage_path("app/" . $profile->hero_image));
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
}
