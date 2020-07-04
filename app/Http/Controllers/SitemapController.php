<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Api\Controller;
use App\Strategies\Paginator;
use Illuminate\Http\Request;
use Carbon\Carbon;
// model that need to generate sitemap
use App\PublicReviewProduct;
use App\Profile;
use App\Company;
use App\Shoutout;
use App\Photo;
use App\Collaborate;
use App\Polling;
use App\Shareable\Photo as ShareablePhoto;
use App\Shareable\Collaborate as ShareableCollaborate;
use App\Shareable\Product as ShareableProduct;
use App\Shareable\Polling as ShareablePolling;
use App\Shareable\Shoutout as ShareableShoutout;

class SitemapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $publicReviewProduct = PublicReviewProduct::first();
        $profile = Profile::first();
        $company = Company::first();
        $shoutout = Shoutout::first();
        $photo = Photo::first();
        $collaborate = Collaborate::first();
        $polling = Polling::first();
        $shareablePhoto = ShareablePhoto::first();
        $shareableCollaborate = ShareableCollaborate::first();
        $shareableProduct = ShareableProduct::first();
        $shareablePolling = ShareablePolling::first();
        $shareableShoutout = ShareableShoutout::first();
        return response()
            ->view('sitemap.index', [
                "publicReviewProduct" => $publicReviewProduct,
                "profile" => $profile,
                "company" => $company,
                "shoutout" => $shoutout,
                "photo" => $photo,
                "collaborate" => $collaborate,
                "polling" => $polling,
                "shareablePhoto" => $shareablePhoto,
                "shareableCollaborate" => $shareableCollaborate,
                "shareableProduct" => $shareableProduct,
                "shareablePolling" => $shareablePolling,
                "shareableShoutout" => $shareableShoutout
            ])
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function publicReviewProducts(Request $request)
    {
        $publicReviewProduct = PublicReviewProduct::whereNull('deleted_at')
            ->pluck('id');
        return response()
            ->view('sitemap.publicReviewProduct', [
                "publicReviewProduct" => $publicReviewProduct,
            ])
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profiles(Request $request)
    {
        $final_profile = [];
        $profiles = Profile::whereNull('deleted_at')
            ->pluck('handle');

        foreach ($profiles as $key => $profile) {
            array_push($final_profile, "@".$profile);
        }

        return response()
            ->view('sitemap.profiles', [
                "profiles" => $final_profile,
            ])
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function companies(Request $request)
    {
        $companies = Company::whereNull('deleted_at')
            ->pluck('id');
        return response()
            ->view('sitemap.companies', [
                "companies" => $companies,
            ])
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shoutouts(Request $request)
    {
        $shoutouts = Shoutout::whereNull('deleted_at')
            ->pluck('id');
        return response()
            ->view('sitemap.shoutouts', [
                "shoutouts" => $shoutouts,
            ])
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function photos(Request $request)
    {
        $photos = Photo::whereNull('deleted_at')
            ->pluck('id');
        return response()
            ->view('sitemap.photos', [
                "photos" => $photos,
            ])
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function collaborations(Request $request)
    {
        $collaborations = Collaborate::whereNull('deleted_at')
            ->whereNotIn('state', [2, 3])
            ->get(['id', 'collaborate_type']);
        return response()
            ->view('sitemap.collaborations', [
                "collaborations" => $collaborations,
            ])
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function polls(Request $request)
    {
        $polls = Polling::whereNull('deleted_at')
            ->pluck('id');
        return response()
            ->view('sitemap.polls', [
                "polls" => $polls,
            ])
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sharedPhotos(Request $request)
    {
        $sharedPhotos = ShareablePhoto::whereNull('deleted_at')
            ->get(['id','photo_id']);
        return response()
            ->view('sitemap.sharedPhotos', [
                "sharedPhotos" => $sharedPhotos,
            ])
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sharedCollaborations(Request $request)
    {
        $sharedCollaborations = ShareableCollaborate::whereNull('deleted_at')
            ->get(['id','collaborate_id']);
        return response()
            ->view('sitemap.sharedCollaborations', [
                "sharedCollaborations" => $sharedCollaborations,
            ])
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sharedProducts(Request $request)
    {
        $sharedProducts = ShareableProduct::whereNull('deleted_at')
            ->get(['id','product_id']);
        return response()
            ->view('sitemap.sharedProducts', [
                "sharedProducts" => $sharedProducts,
            ])
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sharedPolls(Request $request)
    {
       $sharedPolls = ShareablePolling::whereNull('deleted_at')
            ->get(['id','poll_id']);
        return response()
            ->view('sitemap.sharedPolls', [
                "sharedPolls" => $sharedPolls,
            ])
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sharedShoutouts(Request $request)
    {
       $sharedShoutouts = ShareableShoutout::whereNull('deleted_at')
            ->get(['id','shoutout_id']);
        return response()
            ->view('sitemap.sharedShoutouts', [
                "sharedShoutouts" => $sharedShoutouts,
            ])
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function miscellaneous(Request $request)
    {
        return response()
            ->view('sitemap.miscellaneous')
            ->header('Content-Type', 'text/xml');
    }

}
