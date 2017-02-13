<?php


namespace App\Http\Api;


class Response
{
    protected $data = [];
    protected $errors = [];
    protected $messages = [];

    public function __construct($data, $errors = [], $messages = [])
    {
        $this->data = $data;
        $this->errors = $errors;
        $this->messages = $messages;
    }

    public function json()
    {
        $response = $this->buildResponse();
        return response()->json($response);
    }

    public function buildResponse()
    {
        return ['data'=>$this->data,'errors' =>$this->errors,'messages'=>$this->messages];
    }
}