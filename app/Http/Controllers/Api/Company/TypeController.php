<?php namespace App\Http\Controllers\Api\Company;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Company\Type;
use \Tagtaste\Api\SendsJsonResponse;
use Illuminate\Http\Request;

class TypeController extends Controller {

    use SendsJsonResponse;
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->model = Type::all()->pluck('name','id');
        return $this->sendResponse();
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('company_types.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$company_type = new Type();

		$company_type->name = $request->input("name");
        $company_type->description = $request->input("description");

		$company_type->save();

		return redirect()->route('company.types.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$company_type = Type::findOrFail($id);

		return view('company_types.show', compact('company_type'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$company_type = Type::findOrFail($id);

		return view('company_types.edit', compact('company_type'));
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
		$company_type = Type::findOrFail($id);

		$company_type->name = $request->input("name");
        $company_type->description = $request->input("description");

		$company_type->save();

		return redirect()->route('company.types.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$company_type = Type::findOrFail($id);
		$company_type->delete();

		return redirect()->route('company.types.index')->with('message', 'Item deleted successfully.');
	}

}
