<?php namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Api\Controller;

use App\Privacy;
use Illuminate\Http\Request;

class PrivacyController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->model = Privacy::orderBy('id', 'desc')->get();

		return $this->sendResponse();
	}

}
