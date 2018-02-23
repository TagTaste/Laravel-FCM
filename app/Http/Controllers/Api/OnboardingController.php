<?php

namespace App\Http\Controllers\Api;

use App\Onboarding;
use App\SearchClient;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    public function skills()
    {
        $this->model = \DB::table('onboarding')->select('value')->where('key', 'skills')->inRandomOrder()->take(25)->get();
        return $this->sendResponse();

    }

    public function autoCompleteSkills(Request $request) {
        $term = $request->get('term');
        $this->model = \DB::table('onboarding')->where('key','skills')->where('value','like',"%$term%")
            ->take(20)
            ->get();
        return $this->sendResponse();
    }
}
