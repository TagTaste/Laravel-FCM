<?php


namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller as HttpController;
use App\Scopes\SendsJsonResponse;


class Controller extends HttpController
{
    use SendsJsonResponse;
}