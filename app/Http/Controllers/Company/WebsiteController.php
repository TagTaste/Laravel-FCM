<?php namespace App\Http\Controllers\Company;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Company\Website;
use Illuminate\Http\Request;

class WebsiteController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($companyId)
	{
		$company_websites = Website::orderBy('id', 'desc')->paginate(10);

		return view('company_websites.index', compact('company_websites','companyId'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($companyId)
	{
		return view('company_websites.create',compact('companyId'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request,$companyId)
	{
	    $company = $request->user()->companies()->where('id',$companyId)->first();
        if(!$company){
            throw new \Exception("You don't have the rights to add websites to this company.");
        }

        $company->websites()->create(['name'=>$request->input('name'),'url'=>$request->input('url')]);

		return redirect()->route('companies.websites.index',['companyId'=>$companyId])->with('message', 'Website created.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($companyId, $id)
	{
		$company_website = Website::findOrFail($id);

		return view('company_websites.show', compact('companyId','company_website'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Request $request, $companyId,$id)
	{
        $company = $request->user()->companies()->where('companies.id','=',$companyId)->first();
        if(!$company){
            throw new \Exception("You don't have the rights to edit websites of this company.");
        }
        $company_website = $company->websites()->where('id',$id)->first();

		return view('company_websites.edit', compact('companyId','company_website'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @param Request $request
	 * @return Response
	 */
	public function update(Request $request, $companyId, $id)
	{
        $company = $request->user()->companies()->where('id',$companyId)->first();
        if(!$company){
            throw new \Exception("You don't have the rights to update websites of this company.");
        }
        $website = $company->websites()->where('id',$id)->update(['name'=>$request->input('name'),'url'=>$request->input('url')]);

		return redirect()->route('companies.websites.index',['companyId'=>$companyId])->with('message', 'Website updated.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $companyId, $id)
	{
        $company = $request->user()->companies()->where('id',$companyId)->first();
        if(!$company){
            throw new \Exception("You don't have the rights to delete websites of this company.");
        }
        $company->websites()->where("id",$id)->delete();

		return redirect()->route('companies.websites.index',['companyId'=>$companyId])->with('message', 'Website deleted.');
	}

}
