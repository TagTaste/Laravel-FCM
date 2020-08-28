<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MandatoryFieldsCollaboration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addMandatoryFields:collaboration';

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
        $collaborates = \DB::table('collaborates')->get();
        foreach($collaborates as $collaborate) {
            $inputs = [];
            if($collaborate->document_required == 1) {
                $inputs[] = ['collaborate_id'=>$collaborate->id, 'mandatory_field_id'=>8];
            }
            if($collaborate->is_taster_residence == 1) {
                $inputs[] = ['collaborate_id'=>$collaborate->id, 'mandatory_field_id'=>7];
            }
            $inputs[] = ['collaborate_id'=>$collaborate->id, 'mandatory_field_id'=>3];
            $inputs[] = ['collaborate_id'=>$collaborate->id, 'mandatory_field_id'=>5];
            \DB::table('collaborate_mandatory_mapping')->insert($inputs);
        }
    }
}
