<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InsertGlobalQuestion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'globalquestion:insert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert a new global question';

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
        $headerInfo2 = [['header_name'=>"INSTRUCTIONS"],
            ['header_name'=>"APPEARANCE","header_info"=>"Observe the visual aspect of the product."],
            ['header_name'=>"AROMA","header_info"=>"Sniff the product. If you experienced aroma, fill up this section. Otherwise, move to the next section."],
            ['header_name'=>"TASTE","header_info"=>"Take a bite and figure out basic taste(s) you experienced."],
            ['header_name'=>"AROMATICS","header_info"=>"Observe the smell that was released after you have chipped the product."],
            ['header_name'=>"ORAL TEXTURE","header_info"=>"Chew the product multiple times. Observe if it sticks to the mouth, its loose particles and after-taste."],
            ['header_name'=>"OVERALL PREFERENCE","header_info"=>"Rate the overall experience of the product and provide some comments."],
        ];
        $questions2 = '{
	"INSTRUCTIONS": [{
		"title": "INSTRUCTIONS",
		"subtitle": "Please follow the questionnaire and select the answers that are closest to what you sensed during product tasting. Remember, there are no right or wrong answers.",
		"select_type": 4
	}],

	"APPEARANCE": [{
		"title": "Color of snacks",
		"select_type": 1,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Natural,Synthetic,Hybrid"
	}, {
		"title": "Evenness in size",
		"select_type": 1,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Even,Uneven,Small,Medium,Large"
	}, {
		"title": "Surface texture",
		"select_type": 2,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Moist,Wet,Oily,Sticky,Rough,Smooth,Baked,Roasted,Dehydrated,Fried,Crisp,Limp"
	}, {
		"title": "Brittleness",
		"select_type": 1,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Yes,No"
	}, {
		"title": "Any off appearance (If yes, describe in comment)",
		"select_type": 1,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Yes,No"
	}, {
		"title": "Overall preference",
		"select_type": 5,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Don\'t like,Can\'t say,Somewhat like,Clearly like,Love it"
	}, {
		"title": "Comments",
		"select_type": 3,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 0
	}],

	"AROMA": [{
		"title": "Aromas observed",
		"select_type": 2,
		"is_intensity": 1,
		"intensity_type": 1,
		"intensity_value": 15,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"is_nested_option": 1,
		"nested_option_list": "AROMA"
	}, {
		"title": "Overall preference",
		"select_type": 5,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Don\'t like,Can\'t say,Somewhat like,Clearly like,Love it"
	}, {
		"title": "Comments",
		"select_type": 3,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 0
	}],

	"TASTE": [{
		"title": "Basic taste",
		"select_type": 2,
		"is_intensity": 1,
		"intensity_type": 1,
		"intensity_value": 15,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Sweet,Salt,Sour,Bitter,Umami,Astringent,Pungent"
	}, {
		"title": "Chemical feeling factor (if observed)",
		"select_type": 1,
		"is_intensity": 1,
		"intensity_type": 2,
		"intensity_value": "Low,Medium,High",
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Astringent,Hot temperature,Cold temperature"
	}, {
		"title": "Pungency (Spiciness) of Spices & Herbs",
		"select_type": 1,
		"is_intensity": 1,
		"intensity_type": 1,
		"intensity_value": 15,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Warming sensation,Cooling sensation,Burning sensation"
	}, {
		"title": "After-taste",
		"is_nested_question": 1,
		"question": [{
			"title": "After-taste",
			"select_type": 1,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "Low,Medium,High",
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Good,Bad"
		}, {
			"title": "Duration of the after-taste",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "None,Short,Sufficient,Long"
		}, {
			"title": "Any off taste (If yes, describe in comments)",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		}]
	}, {
		"title": "Overall preference",
		"select_type": 5,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Don\'t like,Can\'t say,Somewhat like,Clearly like,Love it"
	}, {
		"title": "Comments",
		"select_type": 3,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 0
	}],

	"AROMATICS": [{
		"title": "Aromatics observed",
		"subtitle": "Aromatics is the smell that is released after you chew the product",
		"select_type": 2,
		"is_intensity": 1,
		"intensity_type": 1,
		"intensity_value": 15,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"is_nested_option": 1,
		"nested_option_list": "AROMA"
	}, {
		"title": "Overall preference",
		"select_type": 5,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Don\'t like,Can\'t say,Somewhat like,Clearly like,Love it"
	}, {
		"title": "Comments",
		"select_type": 3,
		"is_intensity": 0,
		"is_mandatory": 0,
		"is_nested_question": 0
	}],

	"ORAL TEXTURE": [{
		"title": "Surface texture",
		"subtitle": "Hold the product between the lips",
		"select_type": 2,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Dry,Wet,Oily,Smooth,Rough,Loose particles"
	}, {
		"title": "Sound",
		"is_nested_question": 1,
		"question": [{
			"title": "Type of Sound",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Crispy,Crunchy,Crackly"
		}]
	}, {
		"title": "First chew",
		"is_nested_question": 1,
		"question": [{
			"title": "First chew",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "Low,Medium,High",
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Soft,Hard,Cracks,Breaks,Deforms,Crumbly,Flaky,Dry,Juicy,Airy"
		}, {
			"title": "Oral feel",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "Low,Medium,High",
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Dense,Non-uniform face,Uniform face,Small Particles,Big Particles"
		}]
	}, {
		"title": "Chew down",
		"is_nested_question": 1,
		"question": [{
			"title": "Compression Feel",
			"select_type": 1,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "Low,Medium,High",
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Absorbs moisture,Does not absorbs moisture,Smooth mass,Scattered mass,Adheres to palate,Abrasive particles,Melts fast in mouth,Sticks to teeth,Melts slow in mouth"
		}]
	}, {
		"title": "Residual",
		"is_nested_question": 1,
		"question": [{
			"title": "Residual Feel",
			"select_type": 1,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "Low,Medium,High",
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Oily film,Chalky,Loose particles,Mouth coating,Toothstick"
		}]
	}, {
		"title": "Overall preference",
		"select_type": 5,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Don\'t like,Can\'t say,Somewhat like,Clearly like,Love it"
	}, {
		"title": "Comments",
		"select_type": 3,
		"is_intensity": 0,
		"is_mandatory": 0,
		"is_nested_question": 0
	}],

	"OVERALL PREFERENCE": [{
		"title": "Full product experience",
		"select_type": 5,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Don\'t like,Can\'t say,Somewhat like,Clearly like,Love it"
	}, {
		"title": "Comments",
		"select_type": 3,
		"is_intensity": 0,
		"is_mandatory": 0,
		"is_nested_question": 0
	}]
}';
         $data = ['name'=>'Salty snacks','keywords'=>"Salty snacks",'description'=>'Salty snacks',
             'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];

         \DB::table('global_questions')->insert($data);
    }
}
