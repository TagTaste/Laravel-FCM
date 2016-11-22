<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\AttributeValue;
use Illuminate\Http\Request;

class AttributeValueController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$attribute_values = AttributeValue::orderBy('id', 'desc')->paginate(10);

		return view('attribute_values.index', compact('attribute_values'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($attributeId)
	{
		
		return view('attribute_values.create', compact('attributeId'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$attribute_value = new AttributeValue();

		$attribute_value->name = $request->input("name");
        $attribute_value->value = $request->input("value");
        $attribute_value->default = $request->input("default");
        $attribute_value->attribute_id = $request->input("attribute_id");

		$attribute_value->save();

		return redirect()->route('attribute_values.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$attribute_value = AttributeValue::findOrFail($id);

		return view('attribute_values.show', compact('attribute_value'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$attribute_value = AttributeValue::findOrFail($id);

		return view('attribute_values.edit', compact('attribute_value'));
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
		$attribute_value = AttributeValue::findOrFail($id);

		$attribute_value->name = $request->input("name");
        $attribute_value->value = $request->input("value");
        $attribute_value->default = $request->input("default");
        $attribute_value->attribute_id = $request->input("attribute_id");

		$attribute_value->save();

		return redirect()->route('attribute_values.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$attribute_value = AttributeValue::findOrFail($id);
		$attribute_value->delete();

		return redirect()->route('attribute_values.index')->with('message', 'Item deleted successfully.');
	}

}
