<?php namespace App\Http\Controllers;

use App\Education;
use App\Http\Requests;
use Illuminate\Http\Request;

class EducationController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$education = Education::orderBy('id', 'desc')->paginate(10);

		return view('education.index', compact('education'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('education.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$education = new Education();

		$education->degree = $request->input("degree");
        $education->college = $request->input("college");
        $education->field = $request->input("field");
        $education->grade = $request->input("grade");
        $education->percentage = $request->input("percentage");
        $education->description = $request->input("description");
        $education->start_date = $request->input("start_date");
        $education->end_date = $request->input("end_date");
        $education->ongoing = $request->input("ongoing");
        $education->profile_id = $request->input("profile_id");
        $education->profile_id = $request->input("profile_id");

		$education->save();

		return redirect()->route('education.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$education = Education::findOrFail($id);

		return view('education.show', compact('education'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$education = Education::findOrFail($id);

		return view('education.edit', compact('education'));
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
		$education = Education::findOrFail($id);

		$education->degree = $request->input("degree");
        $education->college = $request->input("college");
        $education->field = $request->input("field");
        $education->grade = $request->input("grade");
        $education->percentage = $request->input("percentage");
        $education->description = $request->input("description");
        $education->start_date = $request->input("start_date");
        $education->end_date = $request->input("end_date");
        $education->ongoing = $request->input("ongoing");
        $education->profile_id = $request->input("profile_id");
        $education->profile_id = $request->input("profile_id");

		$education->save();

		return redirect()->route('education.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$education = Education::findOrFail($id);
		$education->delete();

		return redirect()->route('education.index')->with('message', 'Item deleted successfully.');
	}

}
