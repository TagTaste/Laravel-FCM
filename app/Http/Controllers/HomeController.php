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
        $userId = $request->user()->id;

        $chefs = \App\User::where("id","!=",$userId)->get();

       $chefsFollowed = \App\Follower::with('chef')->where('follower_id',$userId)->get();
       $followers = \App\Follower::with('follower')->where('chef_id',$userId)->get();

        $articles = \App\Article::with('template')->with(['ideabooks' => function($query) use ($userId) {
            $query->where('user_id','=',$userId);
        }])->get();


        return view('home', compact('chefs','chefsFollowed', 'followers','articles'));
    }
}
