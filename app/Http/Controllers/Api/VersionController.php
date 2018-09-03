<?php
/**
 * Created by PhpStorm.
 * User: aman
 * Date: 27/03/18
 * Time: 4:28 PM
 */

namespace App\Http\Controllers\Api;
use \App\Version;
use Illuminate\Http\Request;


class VersionController extends Controller
{
    public function getAndroidVersion() {
        $version = Version::getVersion(Version::$APP_ANDROID);
        \Log::info($request->user()->profile->id);
        return response()->json($version);
    }

    public function getIosVersion() {
        $version = Version::getVersion(Version::$APP_IOS);
        return response()->json($version);
    }

    public function setAndroidVersion(Request $request) {
        $version = Version::setVersion($request->input('compatible_version'),
            $request->input('latest_version'),
            Version::$APP_ANDROID);
        return response()->json($version);
    }

    public  function setIosVersion(Request $request) {
        $version = Version::setVersion($request->input('compatible_version'),
            $request->input('latest_version'),
            Version::$APP_IOS);
        return response()->json($version);
    }

}