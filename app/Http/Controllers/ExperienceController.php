<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Profile\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$experiences = Experience::orderBy('id', 'desc')->paginate(10);

		return view('experiences.index', compact('experiences'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('experiences.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$experience = new Experience();
		$experience->company = $request->input("company");
        $experience->designation = $request->input("designation");
        $experience->description = $request->input("description");
        $experience->location = $request->input("location");
        $experience->start_date = $request->input("start_date");
        $experience->end_date = $request->input("end_date");
        $experience->current_company = $request->input("current_company");
        $experience->profile_id = $request->user()->profile->id;

		$experience->save();

		return redirect()->route('experiences.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$experience = Experience::findOrFail($id);

		return view('experiences.show', compact('experience'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$experience = Experience::findOrFail($id);

		return view('experiences.edit', compact('experience'));
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
		$experience = Experience::findOrFail($id);

		$experience->company = $request->input("company");
        $experience->designation = $request->input("designation");
        $experience->description = $request->input("description");
        $experience->location = $request->input("location");
        $experience->start_date = $request->input("start_date");
        $experience->end_date = $request->input("end_date");
        $experience->current_company = $request->input("current_company");
        $experience->profile_id = $request->user()->profile->id;
		$experience->save();

		return redirect()->route('experiences.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$experience = Experience::findOrFail($id);
		$experience->delete();

		return redirect()->route('experiences.index')->with('message', 'Item deleted successfully.');
	}

}
