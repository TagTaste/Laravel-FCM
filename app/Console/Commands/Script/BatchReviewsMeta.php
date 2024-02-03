<?php

namespace App\Console\Commands\Script;

use Illuminate\Console\Command;
use App\Collaborate\Questions as Questions;
use App\Collaborate\Batches as Batches;
use App\Collaborate\ReviewHeader as ReviewHeader;
use App\Collaborate as Collab;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\File;

class BatchReviewsMeta extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generateBatchData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get review data batch wise';

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
        $finalList = [];

        $collaborates = Collab::whereNotIn('state',[2,4])->where('collaborate_type','product-review')->orderBy('id')->get();
        
        foreach ($collaborates as $model) {
            $headers = ReviewHeader::where('collaborate_id', $model->id)->where('header_selection_type', 2)->first();
            $headerId = $headers['id'] ?? 'not';
            if($headerId != 'not'){
                $question = Questions::select('id','questions->option as option')->where('header_type_id', $headerId)->where('questions->select_type', 5)->get();

                $questionId = $question->pluck('id');
                $maxRating = json_decode($question->pluck('option')->first());
                $overAllPreferences = \DB::table('collaborate_tasting_user_review')->select('tasting_header_id', 'question_id', 'leaf_id', 'batch_id', 'value', \DB::raw('count(*) as total'))->where('current_status', 3)
                ->where('collaborate_id', $model->id)->whereIn('question_id', $questionId)
                ->orderBy('tasting_header_id', 'ASC')->orderBy('batch_id', 'ASC')->orderBy('leaf_id', 'ASC')->groupBy('tasting_header_id', 'question_id', 'leaf_id', 'value', 'batch_id')->get();

                $batches = Batches::where('collaborate_id', $model->id)->orderBy('id')->get();
                
                foreach ($batches as $batch) {
                    $item  = [];
                    $item['collaborate_id'] = $model->id;
                    $item['collaborate_year'] = date("Y", strtotime($model->created_at));
                    $item['batch_id'] = $batch['id'];
                    $item['batch_name'] = $batch['name'];
                    $item['type'] = $model['type']->name;
                    $item['category'] = $model['categories']->name;

                    $totalValue = 0;
                    $totalReview = 0;
                    foreach ($overAllPreferences as $overAllPreference) {
                        if ($headerId == $overAllPreference->tasting_header_id && $batch->id == $overAllPreference->batch_id) {
                            $totalReview = $totalReview + $overAllPreference->total;
                            $totalValue = $totalValue + $overAllPreference->leaf_id * $overAllPreference->total;
                        }
                    }
                    if ($totalValue && $totalReview)
                        $item['rating'] = number_format((float)($totalValue / $totalReview), 2, '.', '');
                    else
                        $item['rating'] = "0.00";
                    $item['review_count'] = $totalReview;
                    $item['scale'] = count((array)$maxRating);

                    $finalList[] = $item;
                }               
            }
        }

        $name = 'review data';
        $excel = Excel::create($name, function ($excel) use ($name, $finalList) {
            // Set the title
            $excel->setTitle($name);

            // Chain the setters
            $excel->setCreator('Tagtaste')
                ->setCompany('Tagtaste');

            // Call them separately
            $excel->setDescription('Collaboration Review ');

            $excel->sheet('Sheetname', function ($sheet) use ($finalList) {
                $sheet->fromArray($finalList, null, 'A1', true, true);
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
        $excel_save_path = storage_path("logs/" . $excel->filename . ".xlsx");
    }
}
