<?php


namespace App\Scopes;


use App\Http\Api\Response;

trait SendsJsonResponse
{
    protected $model;
    public function sendResponse()
    {
        $response = new Response($this->model);
        return $response->json();
    }
}