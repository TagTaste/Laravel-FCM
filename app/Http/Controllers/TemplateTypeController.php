<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\TemplateType;
use Illuminate\Http\Request;

class TemplateTypeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$template_types = TemplateType::orderBy('id', 'desc')->paginate(10);

		return view('template_types.index', compact('template_types'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('template_types.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$template_type = new TemplateType();

		$template_type->name = $request->input("name");

		$template_type->save();

		return redirect()->route('template_types.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$template_type = TemplateType::findOrFail($id);

		return view('template_types.show', compact('template_type'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$template_type = TemplateType::findOrFail($id);

		return view('template_types.edit', compact('template_type'));
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
		$template_type = TemplateType::findOrFail($id);

		$template_type->name = $request->input("name");

		$template_type->save();

		return redirect()->route('template_types.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$template_type = TemplateType::findOrFail($id);
		$template_type->delete();

		return redirect()->route('template_types.index')->with('message', 'Item deleted successfully.');
	}

}
