<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Profile\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$projects = Project::orderBy('id', 'desc')->paginate(10);

		return view('projects.index', compact('projects'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('projects.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$project = new Project();

		$project->title = $request->input("title");
        $project->description = $request->input("description");
        $project->ongoing = $request->input("ongoing");
        $project->start_date = $request->input("start_date");
        $project->end_date = $request->input("end_date");
        $project->url = $request->input("url");
        $project->profile_id = $request->input("profile_id");
        $project->profile_id = $request->input("profile_id");

		$project->save();

		return redirect()->route('projects.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$project = Project::findOrFail($id);

		return view('projects.show', compact('project'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$project = Project::findOrFail($id);

		return view('projects.edit', compact('project'));
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
		$project = Project::findOrFail($id);

		$project->title = $request->input("title");
        $project->description = $request->input("description");
        $project->ongoing = $request->input("ongoing");
        $project->start_date = $request->input("start_date");
        $project->end_date = $request->input("end_date");
        $project->url = $request->input("url");
        $project->profile_id = $request->input("profile_id");
        $project->profile_id = $request->input("profile_id");

		$project->save();

		return redirect()->route('projects.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$project = Project::findOrFail($id);
		$project->delete();

		return redirect()->route('projects.index')->with('message', 'Item deleted successfully.');
	}

}
