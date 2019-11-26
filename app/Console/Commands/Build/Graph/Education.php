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
        $counter = 1;
        $degrees = \App\Education::pluck('degree');
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
            "B_TECH" => "BTECH",
            "M TECH" => "MTECH",
            "M.TECH" => "MTECH",
            "M_TECH" => "MTECH",
            "_RSQUO_" => ""
        );
        foreach ($degrees as $degree) {
            $string =  str_replace(
                array_keys($replacer),
                array_values($replacer),
                preg_replace('/\_+/', "_",$this->seo_friendly_url($degree))
            );
            if (strlen($string)) {
                $degree_exist = \App\Neo4j\Degree::where('name',$string)->first();
                if (!$degree_exist) {
                    echo $counter." | ".$string." not exist. \n";
                    \App\Neo4j\Degree::create([
                        "name" => $string
                    ]);
                } else {
                    echo $counter." | ".$string." already exist. \n";
                }
            }
            $counter++;
        }
    }
}
