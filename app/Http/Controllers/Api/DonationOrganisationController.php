<?php

namespace App\Http\Controllers\Api;

use App\DonationOrganisation;
use Illuminate\Http\Request;

class DonationOrganisationController extends Controller
{
    
    protected $model;

    public function __construct(DonationOrganisation $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        $this->model = DonationOrganisation::where('is_active',1)->whereNull('deleted_at')->get();
        return $this->sendResponse();
    }
}