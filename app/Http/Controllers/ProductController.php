<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Product;
use App\ProfileType;
use Illuminate\Http\Request;

class ProductController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		$products = Product::where('user_id',$request->user()->id)->orderBy('id', 'desc')->paginate(10);

		return view('products.index', compact('products'));
	}

	public function showForUser($userId){
		$products = Product::where('user_id',$userId)->orderBy('id', 'desc')->paginate(10);

		return view('products.index', compact('products'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
	    $types = Product::$types;
	    $modes = Product::$modes;
		return view('products.create', compact('modes','types'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$product = new Product();

		$product->name = $request->input("name");
        $product->price = $request->input("price");

        if($request->hasFile('image')){
        	$filename = $request->user()->id . str_random(25) . ".jpeg";
        	$request->image->storeAs('product_images',$filename);
        	$product->image = $filename;
        }

        $product->moq = $request->input("moq");
        $product->type = $request->input("type");
        $product->about = $request->input("about");
        $product->ingredients = $request->input("ingredients");
        $product->certifications = $request->input("certifications");
        $product->portion_size = $request->input("portion_size");
        $product->shelf_life = $request->input("shelf_life");
        $product->mode = $request->input("mode");
        $product->user_id = $request->user()->id;
        $product->profile_type_id = ProfileType::getTypeId('supplier');

		$product->save();

		return redirect()->route('products.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$product = Product::findOrFail($id);

		return view('products.show', compact('product'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$product = Product::findOrFail($id);
        $types = Product::$types;
        $modes = Product::$modes;

		return view('products.edit', compact('product','types','modes'));
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
		$product = Product::findOrFail($id);

		$product->name = $request->input("name");
        $product->price = $request->input("price");
		if($request->hasFile('image')){
        	$product->image = $request->image->store('product_images');
        }        
        $product->moq = $request->input("moq");
        $product->type = $request->input("type");
        $product->about = $request->input("about");
        $product->ingredients = $request->input("ingredients");
        $product->certifications = $request->input("certifications");
        $product->portion_size = $request->input("portion_size");
        $product->shelf_life = $request->input("shelf_life");
        $product->mode = $request->input("mode");
		$product->save();

		return redirect()->route('products.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$product = Product::findOrFail($id);
		$product->delete();

		return redirect()->route('products.index')->with('message', 'Item deleted successfully.');
	}

}
