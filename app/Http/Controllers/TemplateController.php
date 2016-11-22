<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Template;
use App\TemplateType;

use Illuminate\Http\Request;

class TemplateController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$templates = Template::orderBy('id', 'desc')->paginate(10);

		return view('templates.index', compact('templates'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$templateTypes = TemplateType::getAll();
		return view('templates.create', compact('templateTypes'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$template = new Template();

		$template->name = $request->input("name");
        $template->view = $request->input("view");
        $template->enabled = $request->input("enabled");
        $template->template_type_id = $request->input("template_type_id");

		$template->save();

		return redirect()->route('templates.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$template = Template::findOrFail($id);

		return view('templates.show', compact('template'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$template = Template::findOrFail($id);

		return view('templates.edit', compact('template'));
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
		$template = Template::findOrFail($id);

		$template->name = $request->input("name");
        $template->view = $request->input("view");
        $template->enabled = $request->input("enabled");
        $template->template_type_id = $request->input("template_type_id");

		$template->save();

		return redirect()->route('templates.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$template = Template::findOrFail($id);
		$template->delete();

		return redirect()->route('templates.index')->with('message', 'Item deleted successfully.');
	}

}
