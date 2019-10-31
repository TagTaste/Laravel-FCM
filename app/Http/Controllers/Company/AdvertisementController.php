<?php 

namespace App\Http\Controllers\Company;

use App\Http\Requests;
use App\Http\Controllers\Api\Controller;

use App\Company\Advertisement;
use Illuminate\Http\Request;

class AdvertisementController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$advertisements = Advertisement::orderBy('id', 'desc')->paginate(10);

		return view('advertisements.index', compact('advertisements'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('advertisements.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$advertisement = new Advertisement();

		$advertisement->title = $request->input("title");
        $advertisement->description = $request->input("description");
        $advertisement->youtube_url = $request->input("youtube_url");
        $advertisement->video = $request->input("video");
        $advertisement->company_id = $request->input("company_id");
        $advertisement->company_id = $request->input("company_id");

		$advertisement->save();

		return redirect()->route('advertisements.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$advertisement = Advertisement::findOrFail($id);

		return view('advertisements.show', compact('advertisement'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$advertisement = Advertisement::findOrFail($id);

		return view('advertisements.edit', compact('advertisement'));
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
		$advertisement = Advertisement::findOrFail($id);

		$advertisement->title = $request->input("title");
        $advertisement->description = $request->input("description");
        $advertisement->youtube_url = $request->input("youtube_url");
        $advertisement->video = $request->input("video");
        $advertisement->company_id = $request->input("company_id");
        $advertisement->company_id = $request->input("company_id");

		$advertisement->save();

		return redirect()->route('advertisements.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$advertisement = Advertisement::findOrFail($id);
		$advertisement->delete();

		return redirect()->route('advertisements.index')->with('message', 'Item deleted successfully.');
	}

}
