<?php

namespace App\Console\Commands;
use App\Collaborate;
use App\Company;
use App\Events\NewFeedable;
use App\Job;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CollaborationQuestions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $questions = '{
	"INSTRUCTION": [{
		"title": "INSTRUCTION",
		"subtitle": "Bitch, I don\'t need introduction Follow my simple instruction Wine to the left, sway to the right Drop it down low and take it back high "
	}],
	"APPEARANCE": [{
		"title": "Visual Observation",
		"select_type": 1,
		"intensity_type": 0,
		"nested_question": 0,
		"option": "Broken,Cracked,Uniform Shape"
	}, {
		"title": "Color of the mass and crust",
		"select_type": 1,
		"intensity_type": 0,
		"nested_question": 0,
		"option": "Pale,Medium,Deep"
	}, {
		"title": "Sponginess on touching",
		"select_type": 1,
		"intensity_type": 0,
		"nested_question": 0,
		"option": "Low,Medium,High"
	}, {
		"title": "Overall Preference (Appearance)",
		"select_type": 1,
		"intensity_type": 0,
		"nested_question": 0,
		"option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
	}, {
		"title": "Any comments?",
		"select_type": 3,
		"intensity_type": 0,
		"nested_question": 0
	}],
	"AROMA": [{
			"title": "Please select the Aroma that you identified",
			"select_type": 2,
			"intensity_type": 1,
			"intensity_scale": 2,
			"intensity_value": "Weak,Sufficient,Strong,Overwhelming",
			"nested_question": 0,
			"option": "Milky,Buttery,Fruity,Sour,Chocolate,Caramelized,Cheesy,Nutty,Vanilla,Any Other"
		},
		{
			"title": "If you felt fruity aroma, please tick",
			"select_type": 2,
			"intensity_type": 0,
			"nested_question": 0,
			"option": "Citrus,Blueberry,Strawberry,Banana,Almond,Walnut,Raisins,Dry Plums,Pine Apple,Mango"
		},
		{
			"title": "Overall Preference (Aroma)",
			"select_type": 1,
			"intensity_type": 0,
			"nested_question": 0,
			"option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
		},
		{
			"title": "Any comments?",
			"select_type": 3,
			"intensity_type": 0,
			"nested_question": 0
		}
	],
	"TASTE": [{
			"title": "What was the basic taste?",
			"select_type": 1,
			"intensity_type": 1,
			"intensity_scale": 2,
			"intensity_value": "Low,Medium,High",
			"nested_question": 0,
			"option": "Sweet,Salt,Sour,Bitter,Umami"
		},
		{
			"title": "Chemical Feeling Factor Observed?",
			"select_type": 1,
			"intensity_type": 0,
			"nested_question": 0,
			"option": "Yes,No"
		},
		{
			"title": "Overall Preference (Taste)",
			"select_type": 1,
			"intensity_type": 0,
			"nested_question": 0,
			"option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
		},
		{
			"title": "Any comments?",
			"select_type": 3,
			"intensity_type": 0,
			"nested_question": 0
		}
	],
	"AROMATICS": [{
			"title": "Feel of baked flour",
			"select_type": 1,
			"intensity_type": 0,
			"nested_question": 0,
			"option": "Yes,No"
		},
		{
			"title": "Please select the Aromatics that you identified",
			"select_type": 2,
			"intensity_type": 1,
			"intensity_scale": 2,
			"intensity_value": "Weak,Sufficient,Strong,Overwhelming",
			"nested_question": 0,
			"option": "Eggy,Raisin,Caramelized,Vanilla,Citrus,Blueberry,Strawberry,Banana,Almond,Walnut"
		},
		{
			"title": "Overall Preference (Aromatics)",
			"select_type": 1,
			"intensity_type": 0,
			"nested_question": 0,
			"option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
		},
		{
			"title": "Any comments?",
			"select_type": 3,
			"intensity_type": 0,
			"nested_question": 0
		}
	],
	"TEXTURE": [{
			"title": "Surface/Mass",
			"select_type": 2,
			"intensity_type": 0,
			"nested_question": 0,
			"option": "Rough,Smooth,Loose Particles,Oily Lips,Moist,Wet"
		},
		{
			"title": "First Chew",
			"nested_question": 1,
			"question": [{
					"title": "Uniformity",
					"select_type": 1,
					"intensity_type": 0,
					"nested_question": 0,
					"option": "Low,Medium,High"
				},
				{
					"title": "Compactness",
					"select_type": 1,
					"intensity_type": 0,
					"nested_question": 0,
					"option": "Airy,Dense"
				},
				{
					"title": "Burst of flavour",
					"select_type": 1,
					"intensity_type": 0,
					"nested_question": 0,
					"option": "Low,Medium,High"
				}
			]
		},
		{

			"title": "Chewdown experience",
			"nested_question": 1,
			"question": [{
					"title": "Moisture absorption",
					"select_type": 1,
					"intensity_type": 0,
					"nested_question": 0,
					"option": "Low,Medium,High"
				},
				{
					"title": "Cohesiveness",
					"select_type": 1,
					"intensity_type": 0,
					"nested_question": 0,
					"option": "Low,Medium,High"
				}
			]
		},
		{
			"title": "Residual/After-taste (Swallow)",
			"nested_question": 1,
			"question": [{
					"title": "Loose Particles",
					"select_type": 1,
					"intensity_type": 0,
					"nested_question": 0,
					"option": "Yes,No"
				},
				{
					"title": "Mouthcoating-oily/chalky, Toothstick",
					"select_type": 1,
					"intensity_type": 0,
					"nested_question": 0,
					"option": "Yes,No"
				}
			]
		},
		{
			"title": "Overall Preference (Appearance)",
			"select_type": 1,
			"intensity_type": 0,
			"nested_question": 0,
			"option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
		}, {
			"title": "Any comments?",
			"select_type": 3,
			"intensity_type": 0,
			"nested_question": 0
		}
	],
	"OVERALL PREFERENCE": [{
		"title": "Overall Product Preference",
		"select_type": 1,
		"intensity_type": 0,
		"nested_question": 0,
		"option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
	}, {
		"title": "Any comments?",
		"select_type": 3,
		"intensity_type": 0,
		"nested_question": 0
	}]

}';
    protected $signature = 'Collaboration:Question';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'insert question in collaborate_tasting_questions';

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
        $questions = $this->questions;
        $questions = json_decode($questions,true);
        $collaborateId = 429;
        foreach ($questions as $key=>$question)
        {
            $data = [];
            $header = \DB::table('collaborate_tasting_header')->select('id')->where('header_type','=',$key)
                ->where('collaborate_id',$collaborateId)->first();
            $headerId = $header->id;
            foreach ($question as $item)
            {
                $subtitle = isset($item['subtitle']) ? $item['subtitle'] : null;
                $subquestions = isset($item['question']) ? $item['question'] : [];
                $isNested = isset($item['nested_question']) && $item['nested_question'] == 1 ? 1 : 0;
                unset($item['question']);
                $data = ['title'=>$item['title'],'subtitle'=>$subtitle,'is_nested'=>$isNested,'questions'=>json_encode($item,true),'parent_question_id'=>0,
                        'header_type_id'=>$headerId,'is_mandatory'=>1,'is_active','collaborate_id'=>$collaborateId];

                $x = Collaborate\Questions::create($data);
                \Log::info($x->id);
                foreach ($subquestions as $subquestion)
                {
                    $subtitle = isset($subquestion['subtitle']) ? $subquestion['subtitle'] : null;
                    $isNested = isset($subquestion['nested_question']) && $subquestion['nested_question'] == 1 ? 1 : 0;
                    unset($subquestion['question']);
                    $subData = ['title'=>$subquestion['title'],'subtitle'=>$subtitle,'is_nested'=>$isNested,'questions'=>json_encode($subquestion,true),'parent_question_id'=>$x->id,
                        'header_type_id'=>$headerId,'is_mandatory'=>1,'is_active','collaborate_id'=>$collaborateId];
                    $x = Collaborate\Questions::create($subData);
                    \Log::info($x->id);

                }
            }
        }
    }
}

