<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Tagtaste\Api\SendsJsonResponse;

class UploadFilesController extends Controller
{
    use SendsJsonResponse;
    public function uploadFiles(Request $request){
        $profile_id = $request->user()->profile->id;
        $fileUrlArray = [];
        /**
         * Validating all files by mime type & size of less than 10MB
         */
        $validationStatus = $this->validate($request,[
            'file.*'=>'required|file|mimetypes:text/plain,image/jpeg,application/octet-stream,audio/mpeg,application/zip,application/pdf,text/html,image/png|max:10500'
        ]);

        if ($request->hasFile('file')){
            $files = $request->file('file');
            foreach ($files as $file) {
                $fileMime = $file->getMimeType();
                $filename = $file->getClientOriginalName();
                $fileExt = \File::extension($filename);
                $filename = "TagTaste_".str_random(15).".".$fileExt;
                /**
                 * Storing the file on S3
                 */
                $path = $file->storeAs('global/file/'.$profile_id,$filename,['visibility'=>'public',"disk"=>"s3"]);
                $file_url = \Storage::disk('s3')->url($path);
                $tempArray = [];
                $tempArray["file_mime_type"] = $fileMime;
                $tempArray["size"] = \Storage::disk('s3')->size($path);
                $tempArray["last_modified"] = \Storage::disk('s3')->lastModified($path);
                $tempArray["file"]["url"] = $file_url;
                /**
                 * Creating transcoded files in case of video file
                 */
                if($fileExt == "mp4" || $fileExt == "3gp" || $fileExt == "avi" || $fileExt == "flv" || $fileExt == "wmv" || $fileExt == "mov"){
                    $mediaJson =  $this->videoTranscodingNew($path);
                    $mediaJson = json_decode($mediaJson,true);
                    $tempArray["file"]["transcode"] = $mediaJson;
                }
                $fileUrlArray[] = $tempArray;
            }
            $this->model = $fileUrlArray;
            return $this->sendResponse();
        } else {
            return $this->sendResponse();
        }  
    }

    public function videoTranscodingNew($url)
    {
        $profileId = request()->user()->profile->id;
        $curl = curl_init();
        $data = [
            'profile_id' => $profileId,
            'file_path' => $url
        ];
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('TRANSCODING_APIGATEWAY_URL'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                // Set here requred headers
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "content-type: application/json",
            ),
        ));
        $response = curl_exec($curl);
        $response = json_decode($response);
        $this->body = $response->body;
        return json_encode($this->body,true);
    }
}
