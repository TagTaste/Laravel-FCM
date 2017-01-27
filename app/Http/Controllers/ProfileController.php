<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$profiles = Profile::orderBy('id', 'desc')->paginate(10);

		return view('profiles.index', compact('profiles'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('profiles.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$profile = new Profile();
        $profile->tagline = $request->input("tagline");
        $profile->about = $request->input("about");
        if($request->hasFile('image')){
           $path = $request->image->store('profile.images');
           $profile->image = $path;
        }
        if($request->hasFile("hero_image")){
            $path = $request->hero_image->store('profile.hero_images');
            $profile->hero_image = $path;
        }

        $profile->phone = $request->input("phone");
        $profile->address = $request->input("address");
        $profile->dob = $request->input("dob");
        $profile->interests = $request->input("interests");
        $profile->website_url = $request->input("website_url");
        $profile->blog_url = $request->input("blog_url");
        $profile->facebook_url = $request->input("facebook_url");
        $profile->linkedin_url = $request->input("linkedin_url");
        $profile->instagram_link = $request->input("instagram_link");
        $profile->youtube_channel = $request->input("youtube_channel");
        $profile->user_id = $request->user()->id;
		$profile->save();

		return redirect()->route('profiles.index')->with('message', 'Profile created.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$profile = Profile::findOrFail($id);

		return view('profiles.show', compact('profile'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$profile = Profile::findOrFail($id);

		return view('profiles.edit', compact('profile'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @param Request $request
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
	    $userId = $request->user()->id;
		$profile = Profile::findOrFail($id);

        $profile->tagline = $request->input("tagline");
        $profile->about = $request->input("about");

        if($request->hasFile('image')){
            $path = $request->image->store("profile/{$userId}/images");
            $profile->image = $path;
        }

        if($request->hasFile("hero_image")){
            $path = $request->hero_image->store("profile/{$userId}/hero_images");
            $profile->hero_image = $path;
        }

        $profile->phone = $request->input("phone");
        $profile->address = $request->input("address");
        $profile->dob = $request->input("dob");
        $profile->interests = $request->input("interests");
        $profile->website_url = $request->input("website_url");
        $profile->blog_url = $request->input("blog_url");
        $profile->facebook_url = $request->input("facebook_url");
        $profile->linkedin_url = $request->input("linkedin_url");
        $profile->instagram_link = $request->input("instagram_link");
        $profile->youtube_channel = $request->input("youtube_channel");
        $profile->user_id = $userId;

		$profile->save();

		return redirect()->route('profiles.index')->with('message', 'Profile updated.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$profile = Profile::findOrFail($id);
		$profile->delete();

		return redirect()->route('profiles.index')->with('message', 'Profile deleted.');
	}

    public function image($id)
    {
        $profile = Profile::select('image')->findOrFail($id);
        return response()->file(storage_path("app/" . $profile->image));
	}

    public function heroImage($id)
    {
        $profile = Profile::select('hero_image')->findOrFail($id);

        return response()->file(storage_path("app/" . $profile->hero_image));
    }

}
