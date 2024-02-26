<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\FlagReview;
use App\Profile;
use Carbon\Carbon;

class ModelFlagReview extends Command
{
    use FlagReview;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flag:review {--model= : Model name of a review}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flag a review based on some pre-defined conditions';

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
        $modelOpt = $this->option('model');

        switch ($modelOpt) {
            case 'survey':
                $modelClass = 'App\SurveyAttemptMapping';
                break;
            case 'product':
                $modelClass = 'App\Collaborate\BatchAssign';
                break;
            case 'public_product':
                $modelClass = 'App\PublicReviewUserTiming';
                break;
        }
        
        $modelClass::chunk(200, function($reviews){
            $model = class_basename(get_class($reviews->first()));
            $profile = new Profile();
            foreach($reviews as $review){
                if(!empty($review->end_review) && !is_null($review->end_review) && !is_null($review->duration)){
                    $user_id = $profile->where('id', $review->profile_id)->first()->user_id;
                    $start_review = Carbon::parse($review->start_review);

                    $flag = $this->flagReview($start_review, $review->duration, $review->id, $model, $user_id);
                    $review->update(["is_flag" => $flag]);
                }
            }            
        });
    }
}
