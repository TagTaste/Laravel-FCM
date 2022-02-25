<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\File;



class CollaborateReviewCalculation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'review:calculation';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'collaborate review time calculation';
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
        
        $finalData = \DB::select("SELECT c.title as collaborate_name, cr.collaborate_id,cr.batch_id,cr.profile_id,p.handle as profile_name,MIN(cr.created_at) as start_time,
        MAX(cr.updated_at) as completion_time, TIMEDIFF(MAX(cr.updated_at),MIN(cr.created_at)) as review_time_taken FROM `collaborate_tasting_user_review` 
        as cr join collaborates as c  on (c.id=cr.collaborate_id) join profiles as p on p.id = cr.profile_id where cr.current_status=3 
        group by cr.profile_id,cr.batch_id,cr.collaborate_id");
        foreach($finalData as $value){
            $seconds= strtotime($value->review_time_taken) - strtotime('00:00:00');
            $value->review_time_seconds = $seconds;
            $value->collabore_link = "https://dev.tagtaste.com/collaborations/".$value->collaborate_id."/product-review";
            $value->profile_link = "https://dev.tagtaste.com/@".$value->profile_name;
            $finalData1[] = (Array)$value;

        }
        $relativePath = "reports/surveysAnsweredExcel";
        $name = "surveys-" . "-" . uniqid();
       // $finalData = array_values($finalData);

        $excel = Excel::create($name, function ($excel) use ($name, $finalData1) {
            // Set the title
            $excel->setTitle($name);

            // Chain the setters
            $excel->setCreator('Tagtaste')
                ->setCompany('Tagtaste');

            // Call them separately
            $excel->setDescription('Collaboration Review time calculation');

            $excel->sheet('Sheetname', function ($sheet) use ($finalData1) {
                $sheet->fromArray($finalData1, null, 'A1', true, true);
                // ->getFont()->setBold(true);
                foreach ($sheet->getColumnIterator() as $row) {

                    foreach ($row->getCellIterator() as $cell) {

                        if (!is_null($cell->getValue()) && str_contains($cell->getValue(), '/@')) {
                            $cell_link = $cell->getValue();
                            $cell->getHyperlink()
                                ->setUrl($cell_link)
                                ->setTooltip('Click here to access profile');
                        }
                    }
                }
            })->store('xlsx', false, true);
        });
        $excel->getActiveSheet()->getStyle("1:1")->getFont()->setBold(true);
        $excel_save_path = storage_path("exports/" . $excel->filename . ".xlsx");
        $s3 = \Storage::disk('s3');
        $resp = $s3->putFile($relativePath, new File($excel_save_path), ['visibility' => 'public']);
        
        $url = \Storage::url($resp);
        unlink($excel_save_path);
        $linkMessage = 'Excel Report Review '.$url;
        \Mail::raw($linkMessage, function ($message) {
            
            $message->to('v-hussein@tagtaste.com', 'Hussein Shaikh');
            // $message->cc('john@johndoe.com', 'John Doe');
            // $message->bcc('john@johndoe.com', 'John Doe');
            
            $message->subject('Review Excel');
        
        });
        
    }
}
