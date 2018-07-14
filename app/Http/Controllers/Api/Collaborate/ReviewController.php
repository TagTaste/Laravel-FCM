<?php

namespace App\Http\Controllers\Api\Collaborate;

use App\Collaborate\Review;
use App\Collaborate\ReviewHeader;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

class ReviewController extends Controller
{

    protected $model;

    /**
     * Create instance of controller with Model
     *
     * @return void
     */
    public function __construct(Review $model)
    {
        $this->model = $model;
    }

    public function reviewAnswers(Request $request, $collaborateId, $headerId)
    {
        $data = [];
        $answers = $request->input('answer');
//        foreach ($answers as $answer)
//        {
////            $data[] = ['']
//        }
        $this->model = $answers;
        return $this->sendResponse();
    }
}
