<?php


namespace App\Scope;


use App\Http\Api\Response;

trait SendsJsonResponse
{
    private $model;
    public function sendResponse()
    {
        $response = new Response($this->model);
        return $response->json();
    }
}