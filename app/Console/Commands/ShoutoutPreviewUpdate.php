<?php

namespace App\Console\Commands;

use App\Preview;
use App\Shoutout;
use Illuminate\Console\Command;
use Illuminate\Http\File;

class ShoutoutPreviewUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shoutout:preview:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is for updating the shoutout preview data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Shoutout::where('id','>=',2810)->orderBy('id')->chunk(100,function($models){
            foreach($models as $model){
                $preview = $model->preview;

                if(isset($preview) && !is_null($preview))
                {
                    $url = isset($preview['url']) ? $preview['url'] : null ;
                    $response = Preview::get($url);
                    $inputs = [];
                    $inputs['image'] = isset($response->image) ? $response->image : null;
                    $inputs['url'] = isset($response->url) ? $response->url : null;
                    $inputs['title'] = isset($response->title) ? $response->title : null;
                    $inputs['type'] = isset($response->type) ? $response->type : null;
                    $inputs['site'] = isset($response->site) ? $response->site : null;
                    $inputs['description'] = isset($response->description) ? $response->description : null;

                    if(isset($inputs['image']) && !empty($inputs['image'])){
                        $image = $this->getExternalImage($inputs['image'],$model->profile_id);
                        $s3 = \Storage::disk('s3');
                        $filePath = 'p/' . $model->profile_id . "/si";
                        $resp = $s3->putFile($filePath, new File(storage_path($image)), 'public');
                        if($resp){
                            \File::delete(storage_path($image));
                        }
                        $inputs['image'] = $resp;
                    }

                    if(isset($inputs))
                    {
                        echo "model is done ".$model->id."\n";
                        $inputs = json_encode($inputs);
                        $model->update(['preview'=>$inputs,'updated_at'=>$model->updated_at]);
                        $model->addToCache();
                    }

                }
            }
        });
    }

    public function getExternalImage($url,$profileId){
        $path = 'images/p/' . $profileId . "/simages/";
        \Storage::disk('local')->makeDirectory($path);
        $filename = str_random(10) . ".jpg";
        $saveto = storage_path("app/" . $path) .  $filename;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        $raw=curl_exec($ch);
        curl_close ($ch);

        $fp = fopen($saveto,'a');
        fwrite($fp, $raw);
        fclose($fp);
        return "app/" . $path . $filename;
    }

}
