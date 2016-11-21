<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $chefs = \App\User::whereHas('profile',function($query){
            $query->where('type_id','=',1);
        })->where("id","!=",$request->user()->id)->get();

        $chefsFollowed = \App\Follower::with('chef')->where('follower_id',$request->user()->id)->get();
        $followers = \App\Follower::with('follower')->where('chef_id',$request->user()->id)->get();
        return view('home', compact('chefs','chefsFollowed', 'followers'));
    }
}
