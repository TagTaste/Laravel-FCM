<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AddHeaderSelectionType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:headerselectiontype';

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
        $headers = \DB::table('collaborate_tasting_header')->get();
        foreach ($headers as $header) {
            if($header->header_type == 'INSTRUCTIONS') {
                \DB::table('collaborate_tasting_header')->where('id',$header->id)->update(['header_selection_type'=>0]);
            } else if($header->header_type == 'OVERALL PREFERENCE' || $header->header_type == 'OVERALL PRODUCT EXPERIENCE') {
                \DB::table('collaborate_tasting_header')->where('id',$header->id)->update(['header_selection_type'=>3]);
            } else {
                \DB::table('collaborate_tasting_header')->where('id',$header->id)->update(['header_selection_type'=>1]);
            }
        }
    }
}
