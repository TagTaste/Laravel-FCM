<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Api\Controller;

use App\Profile\Certification;
use Illuminate\Http\Request;

class CertificationController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$certifications = Certification::orderBy('id', 'desc')->paginate(10);

		return view('certifications.index', compact('certifications'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('certifications.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$certification = new Certification();

		$certification->name = $request->input("name");
        $certification->description = $request->input("description");
        $certification->date = $request->input("date");
        $certification->profile_id = $request->user()->profile->id;

		$certification->save();

		return redirect()->route('certifications.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$certification = Certification::findOrFail($id);

		return view('certifications.show', compact('certification'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$certification = Certification::findOrFail($id);

		return view('certifications.edit', compact('certification'));
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
		$certification = Certification::findOrFail($id);

		$certification->name = $request->input("name");
        $certification->description = $request->input("description");
        $certification->date = $request->input("date");
        $certification->profile_id = $request->user()->profile->id;

		$certification->save();

		return redirect()->route('certifications.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$certification = Certification::findOrFail($id);
		$certification->delete();

		return redirect()->route('certifications.index')->with('message', 'Item deleted successfully.');
	}

}
