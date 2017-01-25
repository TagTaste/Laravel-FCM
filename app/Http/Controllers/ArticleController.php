<?php namespace App\Http\Controllers;

use App\Article;
use App\DishArticle;
use App\Http\Requests;
use App\Privacy;
use App\Template;
use Illuminate\Http\Request;

class ArticleController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{

	    $articles = $request->user()->getArticles();

		return view('articles.index', compact('articles'));
	}

	/**
	 * Show the form for creating new artcile of type $type.
	 * 
	 * @param  String $type
	 * @return Response
	 */
	public function create(Request $request, $type)
	{
		$privacy = Privacy::getAll();

		$templates = Template::forType($type);
		$dishes = false;
		$requiresTitle = true;
		if($type == 'recipe'){
			$dishes = DishArticle::getAsArray($request->user()->id,ProfileType::getTypeId('chef'));
			$requiresTitle = false;
		}
		return view('articles.create', compact('type','privacy', 'templates','dishes','requiresTitle'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$inputs = $request->input('article');
		$inputs['user_id'] = $request->user()->id;

		$article = Article::create($inputs);
		
		$type = $request->input("type");

		$typeInputs = $request->input($type);
		$typeInputs['article_id'] = $article->id;

		//make this into a factory.
		$class = "\App\\" . ucfirst($type) . "Article";
        if($class::$expectsFiles){
            foreach($class::$fileInputs as $fileInput => $storagePath){
                $inputName = $type . "." . $fileInput;
                if($request->hasFile($inputName)){
                    $file = $request->file($inputName);
                    $filePath = $file->store($storagePath);
                    //get file name, laravel has something for this?
                    $names = explode("/",$filePath);
                    $typeInputs[$fileInput] = end($names);
                }
            }
        }


		$particularArticle = $class::create($typeInputs);



		return redirect()->route('articles.index')->with('message', 'Article created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$article = Article::findOrFail($id);

		return view('articles.show', compact('article'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id, $type)
	{
		$article = Article::findOrFail($id);
		$requiresTitle = true;

		$privacy = Privacy::getAll();

		$templates = Template::forType($type);

		return view('articles.edit', compact('article', 'requiresTitle', 'type', 'privacy', 'templates'));
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
		$article = Article::findOrFail($id);
		$article->update($request->input('article'));

		$type = $request->input("type");
		$typeInputs = $request->input($type);

		$class = "\App\\" . ucfirst($type) . "Article";
        if($class::$expectsFiles){
            foreach($class::$fileInputs as $fileInput => $storagePath){
                $inputName = $type . "." . $fileInput;
                if($request->hasFile($inputName)){
                    $file = $request->file($inputName);
                    $filePath = $file->store($storagePath);
                    //get file name, laravel has something for this?
                    $names = explode("/",$filePath);
                    $typeInputs[$fileInput] = end($names);
                }
            }
        }
		$article->$type->update($typeInputs);

		return redirect()->route('articles.index')->with('message', 'Article updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
		$article = Article::findOrFail($id);
		$article->delete();

		return redirect()->route('articles.index')->with('message', 'Article deleted successfully.');
	}

}
