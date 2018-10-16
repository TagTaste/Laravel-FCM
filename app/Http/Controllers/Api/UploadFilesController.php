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
        // $validationStatus = $this->validate($request,[
        //     'file.*'=>'required|file|mimetypes:text/plain,image/jpeg,application/octet-stream,audio/mpeg,application/zip,application/pdf,text/html,image/png|max:10500'
        // ]);

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
                $image_size = \Storage::disk('s3')->size($path);
                $last_modified = \Storage::disk('s3')->lastModified($path);
                /**
                 * Return image meta data or false in case of any error
                 * 0 => Width
                 * 1 => Height
                 * 2 => Image Type (Int)
                 * 3 => Dimension is String
                 */
                $image_meta = getimagesize($file_url);

                /**
                 * Replacing key's name with appropriate one's
                 */
                if ($image_meta) {
                    if(array_key_exists("0",$image_meta)){
                        $image_meta["width"] = $image_meta["0"];
                        unset($image_meta["0"]);
                    }
                    if(array_key_exists("1",$image_meta)){
                        $image_meta["height"] = $image_meta["1"];
                        unset($image_meta["1"]);
                    }
                    if(array_key_exists("2",$image_meta)){
                        $image_meta["type"] = $image_meta["2"];
                        unset($image_meta["2"]);
                    }
                    if(array_key_exists("3",$image_meta)){
                        $image_meta["text"] = $image_meta["3"];
                        unset($image_meta["3"]);
                    }
                    $image_meta["last_modified"]=$last_modified;
                    $image_meta["size"]=$image_size;
                    $tempArray["meta"] = $image_meta;
                    $tempArray["url"] = $file_url;
                } else {
                    $image_meta["last_modified"]=$last_modified;
                    $image_meta["size"]=$image_size;
                    $image_meta["mime"] = $fileMime;
                    $tempArray["meta"] = $image_meta;
                    $tempArray["url"] = $file_url;
                }
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
