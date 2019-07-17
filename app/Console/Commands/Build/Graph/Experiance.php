<?php

namespace App\Console\Commands\Build\Graph;

use Illuminate\Console\Command;
use Vinelab\NeoEloquent\Exceptions\NeoEloquentException;

class Experiance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:graph:experiance';

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
        $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '_AND_', $string);
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
        $designations = \App\Profile\Experience::pluck('designation');
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
            "_RSQUO_" => ""
        );
        foreach ($designations as $designation) {
            $string =  str_replace(
                array_keys($replacer),
                array_values($replacer),
                preg_replace('/\_+/', "_",$this->seo_friendly_url($designation))
            );
            if (strlen($string)) {
                $degree_exist = \App\Neo4j\Experiance::where('name',$string)->first();
                if (!$degree_exist) {
                    echo $counter." | ".$string." not exist. \n";
                    \App\Neo4j\Experiance::create([
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
