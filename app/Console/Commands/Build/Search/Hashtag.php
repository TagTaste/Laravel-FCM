<?php

namespace App\Console\Commands\Build\Search;

use Illuminate\Console\Command;
use App\Traits\HashtagFactory;

class Hashtag extends Command
{
    use HashtagFactory;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:search:hashtag';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $collaborations = \App\Collaborate::where('title','like','%#%')
                            ->orWhere('description','like','%#%')
                            ->whereNull('deleted_at')
                            ->get();
        foreach($collaborations as $data)
        {
            $totalMatches = [];
            if(preg_match_all('/\s#[A-Za-z0-9_]{1,50}/i',' '.$data->title,$matches)) {
                $totalMatches = array_merge($totalMatches,$matches[0]);
            }
            if(preg_match_all('/\s#[A-Za-z0-9_]{1,50}/i',' '.$data->description,$matches)) {
                $totalMatches = array_merge($totalMatches,$matches[0]);
            }
            if(count($totalMatches)) {
                $this->createHashtag($totalMatches,'App\Collaborate',$data->id);
            }
        }

        $shoutouts = \App\Shoutout::where('content','like','%#%')
                        ->whereNull('deleted_at')
                        ->get();
        foreach($shoutouts as $data)
        {
            $totalMatches = [];
            if(gettype($data->content) == 'array') {
                $content = $data->content['text'];
            } else {
                $content = $data->content;
            }
            if(preg_match_all('/\s#[A-Za-z0-9_]{1,50}/i',' '.$content,$matches)) {
                $totalMatches = array_merge($totalMatches,$matches[0]);
            }
            if(count($totalMatches)) {
                $this->createHashtag($totalMatches,'App\Shoutout',$data->id);
            }
        }

        $photos = \App\V2\Photo::where('caption','like','%#%')->whereNull('deleted_at')->get();
        foreach($photos as $photo)
        {
            $totalMatches = [];
            if(gettype($data->caption) == 'array') {
                $content = $data->caption['text'];
            } else {
                $content = $data->caption;
            }
            if(preg_match_all('/\s#[A-Za-z0-9_]{1,50}/i',' '.$data->content,$matches)) {
                $totalMatches = array_merge($totalMatches,$matches[0]);
            }
            if(count($totalMatches)) {
                $this->createHashtag($totalMatches,'App\V2\Photo',$data->id);
            }
        }  

        $polls = \App\Polling::where('title','like','%#%')->whereNull('deleted_at')->get();
        foreach($polls as $data)
        {
            $totalMatches = [];
            if(preg_match_all('/\s#[A-Za-z0-9_]{1,50}/i',' '.$data->title,$matches)) {
                $totalMatches = array_merge($totalMatches,$matches[0]);
            }
            if(count($totalMatches)) {
                $this->createHashtag($totalMatches,'App\Polling',$data->id);
            }
        }

        $photo_shares = \Db::table('photo_shares')->where('content','like','%#%')->get();
        foreach($photo_shares as $data)
        {
            $totalMatches = [];
            if(gettype($data->content) == 'array') {
                $content = $data->content['text'];
            } else {
                $content = $data->content;
            }
            if(preg_match_all('/\s#[A-Za-z0-9_]{1,50}/i',' '.$content,$matches)) {
                $totalMatches = array_merge($totalMatches,$matches[0]);
            }
            if(count($totalMatches)) {
                $this->createHashtag($totalMatches,'App\Shareable\Photo',$data->id);
            }
        }

        $photo_shares = \Db::table('product_shares')->where('content','like','%#%')->get();
        foreach($photo_shares as $data)
        {
            $totalMatches = [];
            if(gettype($data->content) == 'array') {
                $content = $data->content['text'];
            } else {
                $content = $data->content;
            }
            if(preg_match_all('/\s#[A-Za-z0-9_]{1,50}/i',' '.$content,$matches)) {
                $totalMatches = array_merge($totalMatches,$matches[0]);
            }
            if(count($totalMatches)) {
                $this->createHashtag($totalMatches,'App\Shareable\Product',$data->id);
            }
        }

        $photo_shares = \Db::table('collaborate_shares')->where('content','like','%#%')->get();
        foreach($photo_shares as $data)
        {
            $totalMatches = [];
            if(gettype($data->content) == 'array') {
                $content = $data->content['text'];
            } else {
                $content = $data->content;
            }
            if(preg_match_all('/\s#[A-Za-z0-9_]{1,50}/i',' '.$content,$matches)) {
                $totalMatches = array_merge($totalMatches,$matches[0]);
            }
            if(count($totalMatches)) {
                $this->createHashtag($totalMatches,'App\Shareable\Collaborate',$data->id);
            }
        }

        $photo_shares = \Db::table('poll_shares')->where('content','like','%#%')->get();
        foreach($photo_shares as $data)
        {
            $totalMatches = [];
            if(gettype($data->content) == 'array') {
                $content = $data->content['text'];
            } else {
                $content = $data->content;
            }
            if(preg_match_all('/\s#[A-Za-z0-9_]{1,50}/i',' '.$content,$matches)) {
                $totalMatches = array_merge($totalMatches,$matches[0]);
            }
            if(count($totalMatches)) {
                $this->createHashtag($totalMatches,'App\Shareable\Polling',$data->id);
            }
        }

        $photo_shares = \Db::table('shoutout_shares')->where('content','like','%#%')->get();
        foreach($photo_shares as $data)
        {
            
            $totalMatches = [];
            if(gettype($data->content) == 'array') {
                $content = $data->content['text'];
            } else {
                $content = $data->content;
            }
            if(preg_match_all('/\s#[A-Za-z0-9_]{1,50}/i',' '.$content,$matches)) {
                $totalMatches = array_merge($totalMatches,$matches[0]);
            }
            if(count($totalMatches)) {
                $this->createHashtag($totalMatches,'App\Shareable\Shoutout',$data->id);
            }
        }
        return 1;
    }
}
