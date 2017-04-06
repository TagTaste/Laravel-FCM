<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Api\Controller;

use App\Portfolio;
use Illuminate\Http\Request;

class PortfolioController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$portfolios = Portfolio::orderBy('id', 'desc')->paginate(10);

		return view('portfolios.index', compact('portfolios'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('portfolios.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$portfolio = new Portfolio();

		$portfolio->worked_for = $request->input("worked_for");
        $portfolio->description = $request->input("description");
        $portfolio->company_id = $request->input("company_id");

		$portfolio->save();

		return redirect()->route('portfolios.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$portfolio = Portfolio::findOrFail($id);

		return view('portfolios.show', compact('portfolio'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$portfolio = Portfolio::findOrFail($id);

		return view('portfolios.edit', compact('portfolio'));
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
		$portfolio = Portfolio::findOrFail($id);

		$portfolio->worked_for = $request->input("worked_for");
        $portfolio->description = $request->input("description");
        $portfolio->company_id = $request->input("company_id");

		$portfolio->save();

		return redirect()->route('portfolios.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$portfolio = Portfolio::findOrFail($id);
		$portfolio->delete();

		return redirect()->route('portfolios.index')->with('message', 'Item deleted successfully.');
	}

}
