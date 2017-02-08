<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$companies = Company::orderBy('id', 'desc')->paginate(10);

		return view('companies.index', compact('companies'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('companies.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$company = new Company();

		$company->name = $request->input("name");
        $company->about = $request->input("about");
        $company->logo = $request->input("logo");
        $company->hero_image = $request->input("hero_image");
        $company->phone = $request->input("phone");
        $company->email = $request->input("email");
        $company->registered_address = $request->input("registered_address");
        $company->established_on = $request->input("established_on");
        $company->status_id = $request->input("status_id");
        $company->status_id = $request->input("status_id");
        $company->type = $request->input("type");
        $company->type = $request->input("type");
        $company->employee_count = $request->input("employee_count");
        $company->client_count = $request->input("client_count");
        $company->annual_revenue_start = $request->input("annual_revenue_start");
        $company->annual_revenue_end = $request->input("annual_revenue_end");
        $company->facebook_url = $request->input("facebook_url");
        $company->twitter_url = $request->input("twitter_url");
        $company->linkedin_url = $request->input("linkedin_url");
        $company->instagram_url = $request->input("instagram_url");
        $company->youtube_url = $request->input("youtube_url");
        $company->pinterest_url = $request->input("pinterest_url");
        $company->google_plus_url = $request->input("google_plus_url");
        $company->user_id = $request->input("user_id");
        $company->user_id = $request->input("user_id");

		$company->save();

		return redirect()->route('companies.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$company = Company::findOrFail($id);

		return view('companies.show', compact('company'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$company = Company::findOrFail($id);

		return view('companies.edit', compact('company'));
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
		$company = Company::findOrFail($id);

		$company->name = $request->input("name");
        $company->about = $request->input("about");
        $company->logo = $request->input("logo");
        $company->hero_image = $request->input("hero_image");
        $company->phone = $request->input("phone");
        $company->email = $request->input("email");
        $company->registered_address = $request->input("registered_address");
        $company->established_on = $request->input("established_on");
        $company->status_id = $request->input("status_id");
        $company->status_id = $request->input("status_id");
        $company->type = $request->input("type");
        $company->type = $request->input("type");
        $company->employee_count = $request->input("employee_count");
        $company->client_count = $request->input("client_count");
        $company->annual_revenue_start = $request->input("annual_revenue_start");
        $company->annual_revenue_end = $request->input("annual_revenue_end");
        $company->facebook_url = $request->input("facebook_url");
        $company->twitter_url = $request->input("twitter_url");
        $company->linkedin_url = $request->input("linkedin_url");
        $company->instagram_url = $request->input("instagram_url");
        $company->youtube_url = $request->input("youtube_url");
        $company->pinterest_url = $request->input("pinterest_url");
        $company->google_plus_url = $request->input("google_plus_url");
        $company->user_id = $request->input("user_id");
        $company->user_id = $request->input("user_id");

		$company->save();

		return redirect()->route('companies.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$company = Company::findOrFail($id);
		$company->delete();

		return redirect()->route('companies.index')->with('message', 'Item deleted successfully.');
	}

}
