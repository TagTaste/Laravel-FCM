<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Company_website;
use Illuminate\Http\Request;

class Company_websiteController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$company_websites = Company_website::orderBy('id', 'desc')->paginate(10);

		return view('company_websites.index', compact('company_websites'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('company_websites.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$company_website = new Company_website();

		$company_website->name = $request->input("name");
        $company_website->url = $request->input("url");
        $company_website->company_id = $request->input("company_id");
        $company_website->company_id = $request->input("company_id");

		$company_website->save();

		return redirect()->route('company_websites.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$company_website = Company_website::findOrFail($id);

		return view('company_websites.show', compact('company_website'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$company_website = Company_website::findOrFail($id);

		return view('company_websites.edit', compact('company_website'));
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
		$company_website = Company_website::findOrFail($id);

		$company_website->name = $request->input("name");
        $company_website->url = $request->input("url");
        $company_website->company_id = $request->input("company_id");
        $company_website->company_id = $request->input("company_id");

		$company_website->save();

		return redirect()->route('company_websites.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$company_website = Company_website::findOrFail($id);
		$company_website->delete();

		return redirect()->route('company_websites.index')->with('message', 'Item deleted successfully.');
	}

}
