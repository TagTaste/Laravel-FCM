<?php

namespace App\Console\Commands\Build\Graph;

use Illuminate\Console\Command;
use Vinelab\NeoEloquent\Exceptions\NeoEloquentException;

class Education extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:graph:education';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuilds profile cache';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function seo_friendly_url($string){
        $string = str_replace('.', '', $string);
        $string = str_replace('. ', '', $string);
        $string = str_replace("'", '', $string);
        $string = str_replace(array('[\', \']'), '', $string);
        $string = preg_replace('/\[.*\]/U', '', $string);
        $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '_', $string);
        $string = htmlentities($string, ENT_COMPAT, 'utf-8');
        $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string );
        $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , '_', $string);
        return strtoupper(trim($string, '_'));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $degrees = \App\Education::skip(100)->limit(900)->pluck('degree');
        $replacer = array(
            '?' => "",
            "'" => "",
            "." => "",
            "(" => "",
            ")" => "",
            "-" => "_",
            ":" => "_",
            " " => "_",
            "." => "_",
            "&AMP;" => "_AND_",
            "B TECH" => "BTECH",
            "B.TECH" => "BTECH",
            "B_TECH" => "BTECH"
        );
        foreach ($degrees as $degree) {
            $test =  str_replace(
                array_keys($replacer),
                array_values($replacer),
                preg_replace('/\_+/', "_",$this->seo_friendly_url($degree))
            );
            echo $degree." | ".$test."\n";
        }
        dd("Test");
        dd($degrees);

        // $counter = 1;
        // \App\Recipe\Profile::whereNull('deleted_at')->chunk(200, function($profiles) use($counter) {
        //     $counter = 1;
        //     foreach($profiles as $model) {
        //         $profileId = (int)$model->user_id;
        //         if ($model->dob) {
        //             $time = strtotime($model->dob);
        //             $date = date('d-m',$time);
        //             $user = \App\Neo4j\User::where('profileId', $profileId)->first();
        //             if (!$user) {
        //                 echo $counter." | Profile id: ".$profileId." not exist. \n";
        //             } else {
        //                 $date_type = \App\Neo4j\DateOfBirth::where('dob', $date)->first();
        //                 $datetypeHaveUser = $date_type->have->where('profileId', $profileId)->first();
        //                 if (!$datetypeHaveUser) {
        //                     $relation = $date_type->have()->attach($user);
        //                     $relation->status = 1;
        //                     $relation->statusValue = "have";
        //                     $relation->save();
        //                     echo $counter." | Date Type id: ".$date.", Profile id: ".$profileId." associated. \n";
        //                 } else {
        //                     $relation = $date_type->have()->edge($user);
        //                     $relation->status = 1;
        //                     $relation->statusValue = "have";
        //                     $relation->save();
        //                     echo $counter." | Date Type id: ".$date.", Profile id: ".$profileId." already associated. \n";
        //                 }
        //             }
        //         } else {
        //             echo $counter." | Profile id: ".$profileId." have no dob. \n";
        //         }
        //     }
        // });
    }
}
