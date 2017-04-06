<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Api\Controller;

use App\Patent;
use Illuminate\Http\Request;

class PatentController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$patents = Patent::orderBy('id', 'desc')->paginate(10);

		return view('patents.index', compact('patents'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('patents.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$patent = new Patent();

		$patent->title = $request->input("title");
        $patent->description = $request->input("description");
        $patent->number = $request->input("number");
        $patent->issued_by = $request->input("issued_by");
        $patent->awarded_on = $request->input("awarded_on");
        $patent->company_id = $request->input("company_id");
        $patent->company_id = $request->input("company_id");

		$patent->save();

		return redirect()->route('patents.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$patent = Patent::findOrFail($id);

		return view('patents.show', compact('patent'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$patent = Patent::findOrFail($id);

		return view('patents.edit', compact('patent'));
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
		$patent = Patent::findOrFail($id);

		$patent->title = $request->input("title");
        $patent->description = $request->input("description");
        $patent->number = $request->input("number");
        $patent->issued_by = $request->input("issued_by");
        $patent->awarded_on = $request->input("awarded_on");
        $patent->company_id = $request->input("company_id");
        $patent->company_id = $request->input("company_id");

		$patent->save();

		return redirect()->route('patents.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$patent = Patent::findOrFail($id);
		$patent->delete();

		return redirect()->route('patents.index')->with('message', 'Item deleted successfully.');
	}

}
