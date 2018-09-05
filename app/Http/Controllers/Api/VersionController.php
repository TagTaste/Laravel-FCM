<?php
/**
 * Created by PhpStorm.
 * User: aman
 * Date: 27/03/18
 * Time: 4:28 PM
 */

namespace App\Http\Controllers\Api;
use App\Version;
use Illuminate\Http\Request;
use App\Events\UpgradeApkEvent;


class VersionController extends Controller
{
    public function getAndroidVersion(Request $request) {
        $version = Version::getVersion(Version::$APP_ANDROID);
        if($version->latest_version > $version->compatible_version)
        {
            event(new UpgradeApkEvent($request->user()->profile));
        }
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