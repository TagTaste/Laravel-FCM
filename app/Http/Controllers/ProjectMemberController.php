<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\ProjectMember;
use Illuminate\Http\Request;

class ProjectMemberController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$project_members = ProjectMember::orderBy('id', 'desc')->paginate(10);

		return view('project_members.index', compact('project_members'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('project_members.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$project_member = new ProjectMember();

		$project_member->project_id = $request->input("project_id");
        $project_member->project_id = $request->input("project_id");
        $project_member->profile_id = $request->input("profile_id");
        $project_member->profile_id = $request->input("profile_id");
        $project_member->name = $request->input("name");
        $project_member->designation = $request->input("designation");
        $project_member->description = $request->input("description");

		$project_member->save();

		return redirect()->route('project_members.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$project_member = ProjectMember::findOrFail($id);

		return view('project_members.show', compact('project_member'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$project_member = ProjectMember::findOrFail($id);

		return view('project_members.edit', compact('project_member'));
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
		$project_member = ProjectMember::findOrFail($id);

		$project_member->project_id = $request->input("project_id");
        $project_member->project_id = $request->input("project_id");
        $project_member->profile_id = $request->input("profile_id");
        $project_member->profile_id = $request->input("profile_id");
        $project_member->name = $request->input("name");
        $project_member->designation = $request->input("designation");
        $project_member->description = $request->input("description");

		$project_member->save();

		return redirect()->route('project_members.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$project_member = ProjectMember::findOrFail($id);
		$project_member->delete();

		return redirect()->route('project_members.index')->with('message', 'Item deleted successfully.');
	}

}
