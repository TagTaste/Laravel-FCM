<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\ProfileAttribute;
use Illuminate\Http\Request;

class ProfileAttributeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$profile_attributes = ProfileAttribute::orderBy('id', 'desc')->paginate(10);

		return view('profile_attributes.index', compact('profile_attributes'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('profile_attributes.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$profile_attribute = new ProfileAttribute();

		$profile_attribute->name = $request->input("name");
        $profile_attribute->label = $request->input("label");
        $profile_attribute->description = $request->input("description");
        $profile_attribute->user_id = $request->input("user_id");
        $profile_attribute->multiline = $request->input("multiline");
        $profile_attribute->requires_upload = $request->input("requires_upload");
        $profile_attribute->allowed_mime_types = $request->input("allowed_mime_types");
        $profile_attribute->enabled = $request->input("enabled");
        $profile_attribute->required = $request->input("required");
        $profile_attribute->parent_id = $request->input("parent_id");
        $profile_attribute->template_id = $request->input("template_id");

		$profile_attribute->save();

		return redirect()->route('profile_attributes.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$profile_attribute = ProfileAttribute::findOrFail($id);

		return view('profile_attributes.show', compact('profile_attribute'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$profile_attribute = ProfileAttribute::findOrFail($id);

		return view('profile_attributes.edit', compact('profile_attribute'));
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
		$profile_attribute = ProfileAttribute::findOrFail($id);

		$profile_attribute->name = $request->input("name");
        $profile_attribute->label = $request->input("label");
        $profile_attribute->description = $request->input("description");
        $profile_attribute->user_id = $request->input("user_id");
        $profile_attribute->multiline = $request->input("multiline");
        $profile_attribute->requires_upload = $request->input("requires_upload");
        $profile_attribute->allowed_mime_types = $request->input("allowed_mime_types");
        $profile_attribute->enabled = $request->input("enabled");
        $profile_attribute->required = $request->input("required");
        $profile_attribute->parent_id = $request->input("parent_id");
        $profile_attribute->template_id = $request->input("template_id");

		$profile_attribute->save();

		return redirect()->route('profile_attributes.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$profile_attribute = ProfileAttribute::findOrFail($id);
		$profile_attribute->delete();

		return redirect()->route('profile_attributes.index')->with('message', 'Item deleted successfully.');
	}

}
