<?php

use Illuminate\Database\Seeder;

class GlobalQuestionUploadTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $questions = '{
	"INSTRUCTION": [{
		"title": "INSTRUCTION",
		"subtitle": "I don\'t need introduction Follow my simple instruction Wine to the left, sway to the right Drop it down low and take it back high ",
		"select_type": 4
	}],
	"APPEARANCE": [{
		"title": "Visual Observation",
		"select_type": 1,
		"is_intensity": 0,
		"is_nested": 0,
		"is_mandatory" : 1,
		"option": "Broken,Cracked,Uniform Shape"
	}, {
		"title": "Color of the mass and crust",
		"select_type": 1,
		"is_intensity": 0,
		"is_nested": 0,
		"is_mandatory": 1,
		"option": "Pale,Medium,Deep"
	}, {
		"title": "Sponginess on touching",
		"select_type": 1,
		"is_intensity": 0,
		"is_nested": 0,
		"is_mandatory": 1,
		"option": "Low,Medium,High"
	}, {
		"title": "Overall Preference (Appearance)",
		"select_type": 5,
		"is_intensity": 0,
		"is_nested": 0,
		"is_mandatory": 1,
		"option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
	}, {
		"title": "Any comments?",
		"select_type": 3,
		"is_intensity": 0,
		"is_nested": 0,
		"is_mandatory": 1
	}],
	"AROMA": [{
			"title": "Please select the Aroma that you identified",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "Low,Medium,High",
			"is_nested": 0,
			"is_mandatory": 1,
			"option": "Milky,Buttery,Fruity,Sour,Chocolate,Caramelized,Cheesy,Nutty,Vanilla,Any Other"
		},
		{
			"title": "If you felt fruity aroma, please tick",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 1,
			"is_nested": 0,
			"is_mandatory": 1,
			"intensity_value":"15",
			"nested_option" : 1,
			"option": "Vegetal,Spices,Fruits,Nuts,Floral,Animal,Caramel,Earthy,Chemical,Putrid"
		},
		{
			"title": "Overall Preference (Aroma)",
			"select_type": 5,
			"is_intensity": 0,
			"is_nested": 0,
			"is_mandatory": 1,
			"option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
		},
		{
			"title": "Any comments?",
			"select_type": 3,
			"is_intensity": 0,
			"is_nested": 0,
			"is_mandatory": 1
		}
	],
	"TASTE": [{
			"title": "What was the basic taste?",
			"select_type": 1,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "Low,Medium,High",
			"is_nested": 0,
			"is_mandatory": 0,
			"option": "Sweet,Salt,Sour,Bitter,Umami"
		},
		{
			"title": "Chemical Feeling Factor Observed?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested": 0,
			"is_mandatory": 0,
			"option": "Yes,No"
		},
		{
			"title": "Overall Preference (Taste)",
			"select_type": 5,
			"is_intensity": 0,
			"is_nested": 0,
			"is_mandatory": 0,
			"option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
		},
		{
			"title": "Any comments?",
			"select_type": 3,
			"is_intensity": 0,
			"is_nested": 0,
			"is_mandatory": 0
		}
	],
	"AROMATICS": [{
			"title": "Feel of baked flour",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		},
		{
			"title": "Please select the Aromatics that you identified",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "Weak,Sufficient,Strong,Overwhelming",
			"is_nested": 0,
			"is_mandatory": 1,
			"option": "Eggy,Raisin,Caramelized,Vanilla,Citrus,Blueberry,Strawberry,Banana,Almond,Walnut"
		},
		{
			"title": "Overall Preference (Aromatics)",
			"select_type": 5,
			"is_intensity": 0,
			"is_nested": 0,
			"is_mandatory": 1,
			"option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
		},
		{
			"title": "Any comments?",
			"select_type": 3,
			"is_intensity": 0,
			"is_mandatory": 1,
			"is_nested": 0
		}
	],
	"TEXTURE": [{
			"title": "Surface/Mass",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested": 0,
			"is_mandatory": 1,
			"option": "Rough,Smooth,Loose Particles,Oily Lips,Moist,Wet"
		},
		{
			"title": "First Chew",
			"is_nested": 1,
			"question": [{
					"title": "Uniformity",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested": 0,
					"is_mandatory": 1,
					"option": "Low,Medium,High"
				},
				{
					"title": "Compactness",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested": 0,
					"is_mandatory": 1,
					"option": "Airy,Dense"
				},
				{
					"title": "Burst of flavour",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested": 0,
					"is_mandatory": 1,
					"option": "Low,Medium,High"
				}
			]
		},
		{

			"title": "Chewdown experience",
			"is_nested": 1,
			"question": [{
					"title": "Moisture absorption",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested": 0,
					"is_mandatory": 1,
					"option": "Low,Medium,High"
				},
				{
					"title": "Cohesiveness",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested": 0,
					"is_mandatory": 1,
					"option": "Low,Medium,High"
				}
			]
		},
		{
			"title": "Residual/After-taste (Swallow)",
			"is_nested": 1,
			"is_mandatory": 0,
			"question": [{
					"title": "Loose Particles",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested": 0,
					"is_mandatory": 1,
					"option": "Yes,No"
				},
				{
					"title": "Mouthcoating-oily/chalky, Toothstick",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested": 0,
					"is_mandatory": 1,
					"option": "Yes,No"
				}
			]
		},
		{
			"title": "Overall Preference (Appearance)",
			"select_type": 5,
			"is_intensity": 0,
			"is_nested": 0,
			"is_mandatory": 1,
			"option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
		}, {
			"title": "Any comments?",
			"select_type": 3,
			"is_intensity": 0,
			"is_mandatory": 1,
			"is_nested": 0
		}
	],
	"OVERALL PREFERENCE": [{
		"title": "Overall Product Preference",
		"select_type": 5,
		"is_intensity": 0,
		"is_nested": 0,
		"is_mandatory": 1,
		"option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
	}, {
		"title": "Any comments?",
		"select_type": 3,
		"is_intensity": 0,
		"is_mandatory": 1,
		"is_nested": 0
	}]

}';
         $data = ['name'=>'Rusk Tasting','keywords'=>"Rusk, Solid Category,Veg,Any Other",'description'=>'I don\'t need introduction Follow my simple instruction Wine',
             'question_json'=>$questions];

         \DB::table('global_questions')->insert($data);
    }


}
