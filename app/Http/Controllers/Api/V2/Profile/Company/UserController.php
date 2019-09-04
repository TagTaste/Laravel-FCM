<?php

namespace App\Http\Controllers\Api\V2\Profile\Company;

use App\Company;
use App\V2\CompanyUser;
use App\Events\Actions\Admin;
use App\Http\Controllers\Api\Profile\Company\UserController as BaseController;
use App\Profile;
use App\User;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request,$profileId,$companyId)
    {
        $companyUser = CompanyUser::where("company_id",$companyId)->get()->toArray();
        $this->model = [];
        if (count($companyUser)) {
            $this->model = array_column($companyUser, "profile");
        }
        return $this->sendResponse();
    }
}