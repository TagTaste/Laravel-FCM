<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Follower;
use Illuminate\Http\Request;

class FollowerController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$followers = Follower::orderBy('id', 'desc')->paginate(10);

		return view('followers.index', compact('followers'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('followers.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request, $chefId)
	{
		$follower = new Follower();

		$follower->chef_id = $chefId;
        $follower->follower_id = $request->user()->id;

		$follower->save();

		return redirect()->route('followers.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$follower = Follower::findOrFail($id);

		return view('followers.show', compact('follower'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$follower = Follower::findOrFail($id);

		return view('followers.edit', compact('follower'));
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
		$follower = Follower::findOrFail($id);

		$follower->chef_id = $request->input("chef_id");
        $follower->follower_id = $request->input("follower_id");

		$follower->save();

		return redirect()->route('followers.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($chefId, $userId)
	{
		$follower = Follower::where('chef_id','=',$chefId)->where('follower_id','=',$userId)->first();
		if($follower){
			$follower->delete();
		}

		
	}

	public function follow(Request $request, $chefId){
		$follower = new Follower();

		$follower->chef_id = $chefId;
        $follower->follower_id = $request->user()->id;

		$follower->save();

		return redirect()->back()->with('message', 'Item created successfully.');

	}

	public function unfollow(Request $request, $chefId){
		
		$this->destroy($chefId, $request->user()->id);

		return redirect()->back()->with('message', 'Item deleted successfully.');
	}

}
