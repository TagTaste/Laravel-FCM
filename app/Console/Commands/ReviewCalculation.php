<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\File;
use App\Collaborate\Batches;


class ReviewCalculation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'review:batch_calculation {id}';
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
        $collab_id = $this->argument('id');



        $batches = Batches::select('id', 'name')->where('collaborate_id', $collab_id)->get();

        $finalData = [];
        $dataset = [];
        // dd($batches);
        foreach ($batches as $batch) {
            $finalData = \DB::select(\DB::raw("SELECT  cr.profile_id,p.handle as profile_name ,MIN(cr.created_at) as start_time,
                    MAX(cr.updated_at) as end_time,TIMEDIFF(MAX(cr.updated_at),MIN(cr.created_at)) as duration
            FROM `collaborate_tasting_user_review` 
                    as cr join collaborates as c  on (c.id=cr.collaborate_id) join profiles as p on p.id = cr.profile_id 
            where cr.current_status=3 and cr.batch_id =:batch_id and cr.collaborate_id=:collab_id
                    group by cr.profile_id"), array('batch_id' => $batch->id, 'collab_id' => $collab_id));


            foreach ($finalData as $value) {
                $dataset[$value->profile_id][$batch->id]["start_time"] = $value->start_time;
                $dataset[$value->profile_id][$batch->id]["end_time"] = $value->end_time;
                $dataset[$value->profile_id][$batch->id]["duration"] = $value->duration;
                $dataset[$value->profile_id][$batch->id]["product"] = $batch->name;
                $seconds = strtotime($value->duration) - strtotime('00:00:00');
                $dataset[$value->profile_id][$batch->id]["review_time_seconds"] = $seconds;
                $dataset[$value->profile_id]["profile_url"] = "https://dev.tagtaste.com/@" . $value->profile_name;
            }
        }

        $final = [];
        $i = 0;
        foreach ($dataset as $value) { //dd($value);
            $final[$i]["S.No"] = $i+1;
            $final[$i]["profile_url"] = $value["profile_url"];
            $product = 1;
            foreach ($batches as $batch) {

                $final[$i]["Product " . $product] = isset($value[$batch->id]) ? $value[$batch->id]["product"] : $batch->name;
                $final[$i]["start_time_(" . $batch->id] = isset($value[$batch->id]) ? $value[$batch->id]["start_time"] : "";
                $final[$i]["end_time_(" . $batch->id] = isset($value[$batch->id]) ? $value[$batch->id]["end_time"] : "";
                $final[$i]["duration_(" . $batch->id] = isset($value[$batch->id]) ? $value[$batch->id]["duration"] : "";
                $final[$i]["review_time_seconds_(" . $batch->id] = isset($value[$batch->id]) ? $value[$batch->id]["review_time_seconds"] : "";
                $product++;
            }
            $i++;
        }
        $relativePath = "reports/reviewCalculation";
        $name = "collaborate-" . "-" . uniqid();
        // $finalData = array_values($finalData);

        $excel = Excel::create($name, function ($excel) use ($name, $final) {
            // Set the title
            $excel->setTitle($name);

            // Chain the setters
            $excel->setCreator('Tagtaste')
                ->setCompany('Tagtaste');

            // Call them separately
            $excel->setDescription('Collaboration Review time calculation');

            $excel->sheet('Sheetname', function ($sheet) use ($final) {
                $sheet->fromArray($final, null, 'A1', true, true);
                // ->getFont()->setBold(true);
                foreach ($sheet->getColumnIterator() as $row) {
                    $cellcount = 0;
                    foreach ($row->getCellIterator() as $cell) {

                        if (!is_null($cell->getValue()) && str_contains($cell->getValue(), '/@')) {
                            $cell_link = $cell->getValue();
                            $cell->getHyperlink()
                                ->setUrl($cell_link)
                                ->setTooltip('Click here to access profile');
                        }
                        if ($cellcount == 0 && str_contains($cell->getValue(), '_(')) $cell->setValueExplicit(substr($cell->getValue(), 0, strpos($cell->getValue(), "_(")));
                        $cellcount++;
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
      
        $linkMessage = 'Excel Report Review ' . $url;
        \Mail::raw($linkMessage, function ($message) {

            $message->to('v-hussein@tagtaste.com');
            // $message->cc('john@johndoe.com', 'John Doe');
            // $message->bcc('john@johndoe.com', 'John Doe');

            $message->subject('Review Excel');
        });
    }
}
