<?php


namespace App\Http\Api;


use App\Http\Api\Response;

trait SendsJsonResponse
{
    protected $model;
    protected $errors;
    protected $messages;
    
    public function sendResponse()
    {
        $response = new Response($this->model,$this->errors,$this->messages);
        return $response->json();
    }
}