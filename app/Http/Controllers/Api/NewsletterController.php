<?php namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$newsletters = Newsletter::orderBy('id', 'desc')->paginate(10);

		return view('newsletters.index', compact('newsletters'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('newsletters.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
        $this->model = \DB::table('newsletters')->where('email',$request->input('email'))->exists();

        if($this->model)
        {
            $this->model = false;
            return $this->sendResponse();
        }

		$newsletter = new Newsletter();

		$newsletter->email = $request->input("email");

		$this->model = $newsletter->save();

        return $this->sendResponse();
    }

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$newsletter = Newsletter::findOrFail($id);

		return view('newsletters.show', compact('newsletter'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$newsletter = Newsletter::findOrFail($id);

		return view('newsletters.edit', compact('newsletter'));
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
		$newsletter = Newsletter::findOrFail($id);

		$newsletter->email = $request->input("email");

		$newsletter->save();

		return redirect()->route('newsletters.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$newsletter = Newsletter::findOrFail($id);
		$newsletter->delete();

		return redirect()->route('newsletters.index')->with('message', 'Item deleted successfully.');
	}

}
