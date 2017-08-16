<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Api\Controller;
use App\Profile\Book;
use \Tagtaste\Api\SendsJsonResponse;
use Illuminate\Http\Request;

class BookController extends Controller
{
    use SendsJsonResponse;

    private $fields = ['title','description','publisher','release_date','url','isbn'];
    /**
     * Display a listing of the resource.
     *N
     * @return \Illuminate\Http\Response
     */
    public function index($profileId)
    {
        $this->model = Book::where('profile_id',$profileId)->get();
        return $this->sendResponse();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->model = $request->user()->profile->books()->create($request->only($this->fields));
        return $this->sendResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($profileId,$id)
    {
        $this->model = Book::where('profile_id',$profileId)->where('id',$id)->first();
        if(!$this->model){
            throw new \Exception("Book not found.");
        }
        return $this->sendResponse();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $profileId, $id)
    {
        $input = $request->only($this->fields);
        $input = array_filter($input);
        if(isset($input['release_date'])){
            $input['release_date'] = "01-".$input['release_date'];
            $input['release_date'] = empty($input['release_date']) ? null : date("Y-m-d",strtotime(trim($input['release_date'])));
        }

        $this->model = $request->user()->profile->books()->
            where('id',$id)->update($input);
        return $this->sendResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $profileId, $id)
    {
        $this->model = $request->user()->profile->books()->where('id',$id)->delete();
        return $this->sendResponse();
    }
}
