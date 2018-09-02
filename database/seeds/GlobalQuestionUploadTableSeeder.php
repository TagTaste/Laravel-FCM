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

	"INSTRUCTIONS": [{

		"title": "INSTRUCTIONS",

		"subtitle": "Please follow the questionnaire and select the answers that are closest to what you sensed during product tasting. Remember, there are no right or wrong answers.",

		"select_type": 4

	}],

	"APPEARANCE": [{

		"title": "Can you see the nuts ? ",

		"select_type": 2,

		"is_intensity": 0,

		"is_nested": 0,

		"is_mandatory": 1,

		"option": "Yes,No,Full pieces,Evenly cut pieces,Uneven cuts"

	}, {

		"title": "Please identify the nuts / fruits(identify all)",

		"select_type": 2,

		"is_intensity": 0,

		"is_nested": 0,

		"is_mandatory": 1,

		"option": "Peanuts,Almonds,Black Raisin,Pumpkin Seeds,Cranberries,Sunflower Seed"

	}, {

		"title": "Evenness in size",

		"select_type": 1,

		"is_intensity": 0,

		"is_nested": 0,

		"is_mandatory": 1,

		"option": "Uniform,Naturally non - uniform,Mixed log(non - uniform)"

	}, {

		"title": "Spice coating",

		"select_type": 1,

		"is_intensity": 0,

		"is_nested": 0,

		"is_mandatory": 1,

		"option": "Even,Uneven"

	}, {

		"title": "Surface texture",

		"select_type": 2,

		"is_intensity": 0,

		"is_nested": 0,

		"is_mandatory": 1,

		"option": "Dehydrated,Bright,Rough,Smooth,Dry,Moist,Wet,Oily,Roasted,Crisp"

	}, {

		"title": "Overall Preference",

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

			"intensity_value": "Weak,Sufficient,Strong,Overwhelming",

			"is_nested": 0,

			"is_mandatory": 1,

			"option": "Nutty,Fruity,Caramelized,Warm Spicy,Pungent,Skin/hull/woody,Jaggery,Dried Sauce,Rancid,Meaty,Roasted,Synthetic,Nut Oil,Herbs,Vinegar,Dehydrated Fruits,Any Off Aroma,Any Other"

		},

		{

			"title": "Overall Preference",

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

	"SOUND": [{

			"title": "Identify type of sound: Different nuts emit different sounds",

			"subtitle": "Crispiness: One single high pitch sound, Crunchy: Multiple low pitch sounds in a series, Crackliness: One sudden low pitch sound, brittles product",

			"select_type": 1,

			"is_intensity": 1,

			"intensity_type": 2,

			"intensity_value": "Low,Medium,High",

			"is_nested": 0,

			"is_mandatory": 1,

			"option": "Crispiness,Crunchy,Crackliness"

		}

	],

	"TASTE": [{

			"title": "What was the basic taste?",

			"select_type": 2,

			"is_intensity": 1,

			"intensity_type": 2,

			"intensity_value": "Low,Medium,High",

			"is_nested": 0,

			"is_mandatory": 1,

			"option": "Sweet,Salt,Sour,Bitter,Umami,Bland"

		},

		{

			"title": "Length of after taste",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested": 0,

			"is_mandatory": 1,

			"option": "Barely,Short,Sufficient,Long"

		},

		{

			"title": "Chemical feeling observed?",

			"select_type": 1,

			"is_intensity": 1,

			"intensity_type": 2,

			"intensity_value": "Low,Medium,High",

			"is_nested": 0,

			"is_mandatory": 0,

			"option": "Warm sensation spices,Chillis,Astringent,Hot temperature,Cold temperature"

		},

		{

			"title": "Was the taste of nuts… ? ",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested": 0,

			"is_mandatory": 1,

			"option": "Preserved,Enhanced,Masked by seasoning"

		},

		{

			"title": "Overall Preference",

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

	"AROMATICS": [{

			"title": "Please select the Aromatics that you identified",

			"subtitle": "Aromatics is the smell that is released after you chew the product",

			"select_type": 2,

			"is_intensity": 1,

			"intensity_type": 2,

			"intensity_value": "Weak,Sufficient,Strong,Overwhelming",

			"is_nested": 0,

			"is_mandatory": 1,

			"option": "Nutty,Warm Spicy,Pungent Spicy,Fried Oil,Rancid Oil,Rancid Nut,Caramelized,Vinegar,Honey,Jaggery,Roasted,Synthetic,Diacetyl,Butterscotch,Any Other,Any Off Aromatics"

		},

		{

			"title": "Overall Preference",

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

			"title": "Surface texture (between lips)",

			"select_type": 2,

			"is_intensity": 0,

			"is_nested": 0,

			"is_mandatory": 1,

			"option": "Dry,Oily,Smooth,Rough,Loose Particles"

		},

		{

			"title": "First Chew",

			"is_nested": 1,

			"question": [{

					"title": "Oral Feel",

					"select_type": 1,

					"is_intensity": 0,

					"is_nested": 0,

					"is_mandatory": 1,

					"option": "Hardness,Roughness,Sound,Loose Particles,Burst of Flavors,Any Other"

				}

			]

		},

		{

			"title": "Chewdown experience",

			"is_nested": 1,

			"question": [{

					"title": "Compression Feel: Mass",

					"select_type": 1,

					"is_intensity": 0,

					"is_nested": 0,

					"is_mandatory": 1,

					"option": "Moisture absorption,Abrasiveness of mass,Moistness,Persistence of sound,Cohesiveness of mass,Any other"

				}

			]

		},

		{

			"title": "Residual",

			"subtitle ": "Swallow and then run your tongue over teeth and inside mouth",

			"is_nested": 1,

			"is_mandatory": 0,

			"question": [{

					"title": "Residual feel",

					"select_type": 1,

					"is_intensity": 0,

					"is_nested": 0,

					"is_mandatory": 1,

					"option": "Mouthcoating,Oily film,Toothstick,Any other"

				}

			]

		},

		{

			"title": "Overall Preference",

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
         $data = ['name'=>'Kari Kari','keywords'=>"Form for Japanese snacks",'description'=>'Kari Kari, Japan, Snacks, Healthy Snacks',
             'question_json'=>$questions];

         \DB::table('global_questions')->insert($data);
    }


}
