<?php

namespace App\Http\Controllers\Api\Profile\Company;

use App\Http\Controllers\Api\Controller;
use App\Company\Book;
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
    public function index($profileId, $companyId)
    {
        $this->model = Book::where('company_id',$companyId)->paginate(10);
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
    public function store(Request $request, $profileId, $companyId)
    {
        $company = $request->user()->companies()->where('id',$companyId)->first();

        if(!$company){
            throw new \Exception("This company does not belong to user.");
        }

        $this->model = $company->books()->create(array_filter($request->only($this->fields)));

        return $this->sendResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($profileId,$companyId,$id)
    {
        $this->model = Book::where('company_id',$companyId)->where('id',$id)->first();
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
    public function update(Request $request, $profileId, $companyId, $id)
    {
        $input = $request->only($this->fields);
        $input = array_filter($input);
        if(isset($input['release_date'])){
            $input['release_date'] = date('Y-m-d',strtotime($input['release_date']));
        }

        $company = $request->user()->companies()->where('id',$companyId)->first();

        if(!$company){
            throw new \Exception("This company does not belong to user.");
        }

        $this->model = $company->books()->where('id',$id)->update($input);

        return $this->sendResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $profileId, $companyId, $id)
    {
        $company = $request->user()->companies()->where('id',$companyId)->first();

        if(!$company){
            throw new \Exception("This company does not belong to user.");
        }

        $this->model = $company->books()->where('id',$id)->delete();

        return $this->sendResponse();
    }
}
