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
        $headerInfo1 = [['header_name'=>"INSTRUCTIONS","header_info"=>"Here is info "],
            ['header_name'=>"APPEARANCE","header_info"=>"Here is info "],
            ['header_name'=>"AROMA","header_info"=>"Here is info "],
            ['header_name'=>"SOUND","header_info"=>"Here is info "],
            ['header_name'=>"TASTE","header_info"=>"Here is info "],
            ['header_name'=>"AROMATICS","header_info"=>"Here is info "],
            ['header_name'=>"TEXTURE","header_info"=>"Here is info "],
            ['header_name'=>"OVERALL PREFERENCE","header_info"=>"Here is info "],
        ];

         $questions1 = '{

	"INSTRUCTIONS": [{

		"title": "INSTRUCTIONS",

		"subtitle": "Please follow the questionnaire and select the answers that are closest to what you sensed during product tasting. Remember, there are no right or wrong answers.",

		"select_type": 4

	}],

	"APPEARANCE": [{

		"title": "Can you see the nuts ? ",

		"select_type": 2,

		"is_intensity": 0,

		"is_nested_question": 0,

		"is_mandatory": 1,

		"option": "Yes,No,Full pieces,Evenly cut pieces,Uneven cuts"

	}, {

		"title": "Please identify the nuts / fruits(identify all)",

		"select_type": 2,

		"is_intensity": 0,

		"is_nested_question": 0,

		"is_mandatory": 1,

		"option": "Peanuts,Almonds,Black Raisin,Pumpkin Seeds,Cranberries,Sunflower Seed"

	}, {

		"title": "Evenness in size",

		"select_type": 1,

		"is_intensity": 0,

		"is_nested_question": 0,

		"is_mandatory": 1,

		"option": "Uniform,Naturally non - uniform,Mixed log(non - uniform)"

	}, {

		"title": "Spice coating",

		"select_type": 1,

		"is_intensity": 0,

		"is_nested_question": 0,

		"is_mandatory": 1,

		"option": "Even,Uneven"

	}, {

		"title": "Surface texture",

		"select_type": 2,

		"is_intensity": 0,

		"is_nested_question": 0,

		"is_mandatory": 1,

		"option": "Dehydrated,Bright,Rough,Smooth,Dry,Moist,Wet,Oily,Roasted,Crisp"

	}, {

		"title": "Overall Preference",

		"select_type": 5,

		"is_intensity": 0,

		"is_nested_question": 0,

		"is_mandatory": 1,

		"option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
	}, {

		"title": "Any comments?",

		"select_type": 3,

		"is_intensity": 0,

		"is_nested_question": 0,

		"is_mandatory": 1

	}],

	"AROMA": [{

			"title": "Please select the Aroma that you identified",

			"select_type": 2,

			"is_intensity": 1,

			"intensity_type": 2,

			"intensity_value": "Weak,Sufficient,Strong,Overwhelming",

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": "Nutty,Fruity,Caramelized,Warm Spicy,Pungent,Skin/hull/woody,Jaggery,Dried Sauce,Rancid,Meaty,Roasted,Synthetic,Nut Oil,Herbs,Vinegar,Dehydrated Fruits,Any Off Aroma,Any Other"

		},

		{

			"title": "Overall Preference",

			"select_type": 5,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
		},

		{

			"title": "Any comments?",

			"select_type": 3,

			"is_intensity": 0,

			"is_nested_question": 0,

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

			"is_nested_question": 0,

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

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": "Sweet,Salt,Sour,Bitter,Umami,Bland"

		},

		{

			"title": "Length of after taste",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": "Barely,Short,Sufficient,Long"

		},

		{

			"title": "Chemical feeling observed?",

			"select_type": 1,

			"is_intensity": 1,

			"intensity_type": 2,

			"intensity_value": "Low,Medium,High",

			"is_nested_question": 0,

			"is_mandatory": 0,

			"option": "Warm sensation spices,Chillis,Astringent,Hot temperature,Cold temperature"

		},

		{

			"title": "Was the taste of nuts… ? ",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": "Preserved,Enhanced,Masked by seasoning"

		},

		{

			"title": "Overall Preference",

			"select_type": 5,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
		},

		{

			"title": "Any comments?",

			"select_type": 3,

			"is_intensity": 0,

			"is_nested_question": 0,

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

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": "Nutty,Warm Spicy,Pungent Spicy,Fried Oil,Rancid Oil,Rancid Nut,Caramelized,Vinegar,Honey,Jaggery,Roasted,Synthetic,Diacetyl,Butterscotch,Any Other,Any Off Aromatics"

		},

		{

			"title": "Overall Preference",

			"select_type": 5,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
		},

		{

			"title": "Any comments?",

			"select_type": 3,

			"is_intensity": 0,

			"is_mandatory": 1,

			"is_nested_question": 0

		}

	],

	"TEXTURE": [{

			"title": "Surface texture (between lips)",

			"select_type": 2,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": "Dry,Oily,Smooth,Rough,Loose Particles"

		},

		{

			"title": "First Chew",

			"is_nested_question": 1,

			"question": [{

					"title": "Oral Feel",

					"select_type": 1,

					"is_intensity": 0,

					"is_nested_question": 0,

					"is_mandatory": 1,

					"option": "Hardness,Roughness,Sound,Loose Particles,Burst of Flavors,Any Other"

				}

			]

		},

		{

			"title": "Chewdown experience",

			"is_nested_question": 1,

			"question": [{

					"title": "Compression Feel: Mass",

					"select_type": 1,

					"is_intensity": 0,

					"is_nested_question": 0,

					"is_mandatory": 1,

					"option": "Moisture absorption,Abrasiveness of mass,Moistness,Persistence of sound,Cohesiveness of mass,Any other"

				}

			]

		},

		{

			"title": "Residual",

			"subtitle ": "Swallow and then run your tongue over teeth and inside mouth",

			"is_nested_question": 1,

			"is_mandatory": 0,

			"question": [{

					"title": "Residual feel",

					"select_type": 1,

					"is_intensity": 0,

					"is_nested_question": 0,

					"is_mandatory": 1,

					"option": "Mouthcoating,Oily film,Toothstick,Any other"

				}

			]

		},

		{

			"title": "Overall Preference",

			"select_type": 5,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
		}, {

			"title": "Any comments?",

			"select_type": 3,

			"is_intensity": 0,

			"is_mandatory": 1,

			"is_nested_question": 0

		}

	],

	"OVERALL PREFERENCE": [{

		"title": "Overall Product Preference",

		"select_type": 5,

		"is_intensity": 0,

		"is_nested_question": 0,

		"is_mandatory": 1,

		"option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
	}, {

		"title": "Any comments?",

		"select_type": 3,

		"is_intensity": 0,

		"is_mandatory": 1,

		"is_nested_question": 0

	}]

}';

        $headerInfo2 = [['header_name'=>"INSTRUCTION","header_info"=>"Here is info "],
            ['header_name'=>"APPEARANCE","header_info"=>"Here is info "],
            ['header_name'=>"AROMA","header_info"=>"Here is info "],
            ['header_name'=>"TASTE","header_info"=>"Here is info "],
            ['header_name'=>"AROMATICS","header_info"=>"Here is info "],
            ['header_name'=>"TEXTURE","header_info"=>"Here is info "],
            ['header_name'=>"OVERALL PREFERENCE","header_info"=>"Here is info "],
        ];
        $questions2 = '{
  "INSTRUCTION": [
    {
      "title": "INSTRUCTION",
      "subtitle": "I don\'t need introduction Follow my simple instruction Wine to the left, sway to the right Drop it down low and take it back high ",
      "select_type": 4
    }
  ],
  "APPEARANCE": [
    {
      "title": "Visual Observation",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Broken,Cracked,Uniform Shape"
    },
    {
      "title": "Color of the mass and crust",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Pale,Medium,Deep"
    },
    {
      "title": "Sponginess on touching",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Low,Medium,High"
    },
    {
      "title": "Overall Preference (Appearance)",
      "select_type": 5,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
    },
    {
      "title": "Any comments?",
      "select_type": 3,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1
    }
  ],
  "AROMA": [
    {
      "title": "Please select the Aroma that you identified",
      "select_type": 2,
      "is_intensity": 1,
      "intensity_type": 2,
      "intensity_value": "Low,Medium,High",
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Milky,Buttery,Fruity,Sour,Chocolate,Caramelized,Cheesy,Nutty,Vanilla,Any Other"
    },
    {
      "title": "If you felt fruity aroma, please tick",
      "select_type": 2,
      "is_intensity": 1,
      "intensity_type": 1,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "intensity_value": "15",
      "is_nested_option": 1,
      "option": "Vegetal,Spices,Fruits,Nuts,Floral,Animal,Caramel,Earthy,Chemical,Putrid"
    },
    {
      "title": "Overall Preference (Aroma)",
      "select_type": 5,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
    },
    {
      "title": "Any comments?",
      "select_type": 3,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1
    }
  ],
  "TASTE": [
    {
      "title": "What was the basic taste?",
      "select_type": 1,
      "is_intensity": 1,
      "intensity_type": 2,
      "intensity_value": "Low,Medium,High",
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Sweet,Salt,Sour,Bitter,Umami"
    },
    {
      "title": "Chemical Feeling Factor Observed?",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Yes,No"
    },
    {
      "title": "Overall Preference (Taste)",
      "select_type": 5,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
    },
    {
      "title": "Any comments?",
      "select_type": 3,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1
    }
  ],
  "AROMATICS": [
    {
      "title": "Feel of baked flour",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Yes,No"
    },
    {
      "title": "Please select the Aromatics that you identified",
      "select_type": 2,
      "is_intensity": 1,
      "intensity_type": 2,
      "intensity_value": "Weak,Sufficient,Strong,Overwhelming",
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Eggy,Raisin,Caramelized,Vanilla,Citrus,Blueberry,Strawberry,Banana,Almond,Walnut"
    },
    {
      "title": "Overall Preference (Aromatics)",
      "select_type": 5,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
    },
    {
      "title": "Any comments?",
      "select_type": 3,
      "is_intensity": 0,
      "is_mandatory": 1,
      "is_nested_question": 0
    }
  ],
  "TEXTURE": [
    {
      "title": "Surface/Mass",
      "select_type": 2,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Rough,Smooth,Loose Particles,Oily Lips,Moist,Wet"
    },
    {
      "title": "First Chew",
      "is_nested_question": 1,
      "question": [
        {
          "title": "Uniformity",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Low,Medium,High"
        },
        {
          "title": "Compactness",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Airy,Dense"
        },
        {
          "title": "Burst of flavour",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Low,Medium,High"
        }
      ]
    },
    {
      "title": "Chewdown experience",
      "is_nested_question": 1,
      "question": [
        {
          "title": "Moisture absorption",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Low,Medium,High"
        },
        {
          "title": "Cohesiveness",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Low,Medium,High"
        }
      ]
    },
    {
      "title": "Residual/After-taste (Swallow)",
      "is_nested_question": 1,
      "is_mandatory": 0,
      "question": [
        {
          "title": "Loose Particles",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Yes,No"
        },
        {
          "title": "Mouthcoating-oily/chalky, Toothstick",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Yes,No"
        }
      ]
    },
    {
      "title": "Overall Preference (Appearance)",
      "select_type": 5,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
    },
    {
      "title": "Any comments?",
      "select_type": 3,
      "is_intensity": 0,
      "is_mandatory": 1,
      "is_nested_question": 0
    }
  ],
  "OVERALL PREFERENCE": [
    {
      "title": "Overall Product Preference",
      "select_type": 5,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
    },
    {
      "title": "Any comments?",
      "select_type": 3,
      "is_intensity": 0,
      "is_mandatory": 1,
      "is_nested_question": 0
    }
  ]
}';

        $headerInfo3 = [['header_name'=>"INSTRUCTIONS","header_info"=>null],

            ['header_name'=>"APPEARANCE","header_info"=>"OBSERVE the visual aspect of the product like it's shape, density of mass and color."],

            ['header_name'=>"AROMA","header_info"=>"SNIFF the product. If you experienced aroma, fill up this section. Otherwise, move to the next section."],

            ['header_name'=>"TASTE","header_info"=>"TAKE A BITE and figure out basic taste(s) you experienced."],

            ['header_name'=>"AROMATICS","header_info"=>"OBSERVE the smell that was released after you chewed the product."],

            ['header_name'=>"TEXTURE","header_info"=>"CHEW the product multiple times. Observe if it sticks to the mouth, its loose particles and after-taste."],

            ['header_name'=>"OVERALL PREFERENCE","header_info"=>"RATE the overall experience of the product and provide some comments."],

        ];
        $questions3 = '{

	"INSTRUCTIONS": [{

		"title": "INSTRUCTIONS",

		"subtitle": "Please follow the questionnaire and select the answers that are closest to what you sensed during product tasting. Remember, there are no right or wrong answers.",

		"select_type": 4

	}],


	"APPEARANCE": [{

		"title": "Can you see the nuts?",

		"select_type": 1,

		"is_intensity": 0,

		"is_nested_question": 0,

		"is_mandatory": 1,

		"option": "Yes,No"

	}, {

		"title": "Physical status of the nuts",

		"select_type": 1,

		"is_intensity": 0,

		"is_nested_question": 0,

		"is_mandatory": 1,

		"option": "Full pieces,Evenly cut pieces,Unevenly cut pieces"

	}, {

		"title": "Identify the nuts or fruits",

		"select_type": 2,

		"is_intensity": 0,

		"is_nested_question": 0,

		"is_mandatory": 1,

		"option": "Peanuts Almonds,Black raisin,Pumpkin seeds,Cranberries,Sunflower seed"

	}, {

		"title": "Evenness in size",

		"select_type": 1,

		"is_intensity": 0,

		"is_nested_question": 0,

		"is_mandatory": 1,

		"option": "Uniform,Non-uniform,Naturally non-uniform,Mixed lot"

	}, {

		"title": "Spice coating",

		"select_type": 1,

		"is_intensity": 0,

		"is_nested_question": 0,

		"is_mandatory": 1,

		"option": "Even,Uneven"

	}, {

		"title": "Surface texture",

		"select_type": 2,

		"is_intensity": 0,

		"is_nested_question": 0,

		"is_mandatory": 1,

		"option": "Dehydrated,Bright,Rough,Smooth,Dry,Moist,Wet,Oily,Roasted,Crisp"

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

		"intensity_type": 2,

		"intensity_value": "Weak,Sufficient,Strong,Overwhelming",

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

		"intensity_type": 2,

		"intensity_value": "Low,Medium,High",

		"is_nested_question": 0,

		"is_mandatory": 1,

		"option": "Sweet,Salt,Sour,Bitter,Astringent,Pungent,Umami,Bland"

	}, {

		"title": "Length of after taste",

		"select_type": 1,

		"is_intensity": 0,

		"is_nested_question": 0,

		"is_mandatory": 1,

		"option": "Barely,Short,Sufficient,Long"

	}, {

		"title": "Chemical feeling factor (if observed)",

		"select_type": 1,

		"is_intensity": 1,

		"intensity_type": 2,

		"intensity_value": "Low,Medium,High",

		"is_nested_question": 0,

		"is_mandatory": 1,

		"option": "Warm sensation spices,Chillies,Astringent,Hot temperature,Cold temperature"

	}, {

		"title": "Was the taste of nuts… ? ",

		"select_type": 1,

		"is_intensity": 0,

		"is_nested_question": 0,

		"is_mandatory": 1,

		"option": "Preserved,Enhanced,Masked by seasoning"

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

		"intensity_type": 2,

		"intensity_value": "Weak,Sufficient,Strong,Overwhelming",

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


	"TEXTURE": [{

		"title": "Surface texture",

		"subtitle": "Hold the product between the lips",

		"select_type": 2,

		"is_intensity": 0,

		"is_nested_question": 0,

		"is_mandatory": 1,

		"option": "Dry,Oily,Smooth,Rough,Loose particles"

	}, {

		"title": "Identify type of sound",

		"subtitle": "Crispy: One single high pitch sound. Crunchy: Multiple low pitch sounds in a series. Crackly: One sudden low pitch sound, brittles product.",

		"select_type": 1,

		"is_intensity": 1,

		"intensity_type": 2,

		"intensity_value": "Low,Medium,High",

		"is_nested_question": 0,

		"is_mandatory": 1,

		"option": "Crispy,Crunchy,Crackly"

	}, {

		"title": "First Chew",

		"is_nested_question": 1,

		"question": [{

			"title": "Hardness",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": "Low,Medium,High"

		}, {

			"title": "Roughness",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": "Low,Medium,High"

		}, {

			"title": "Sound",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": "Low,Medium,High"

		}, {

			"title": "Loose particles",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": "Low,Medium,High"

		}, {

			"title": "Burst of flavour",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": "Low,Medium,High"

		}]

	}, {

		"title": "Chew-down experience",

		"is_nested_question": 1,

		"question": [{

			"title": "Moisture absorption",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": "Low,Medium,High"

		}, {

			"title": "Abrasiveness of mass",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": "Low,Medium,High"

		}, {

			"title": "Moistness",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": "Low,Medium,High"

		}, {

			"title": "Persistence of sound",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": "Low,Medium,High"

		}, {

			"title": "Cohesiveness of mass",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": "Low,Medium,High"

		}]

	}, {

		"title": "After-taste",

		"is_nested_question": 1,

		"is_mandatory": 0,

		"question": [{

			"title": "Mouth coating",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": "Low,Medium,High"

		}, {

			"title": "Oily film",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": "Low,Medium,High"

		}, {

			"title": "Toothstick",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": "Low,Medium,High"

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

		"title": "Overall product preference",

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
        $questions4 = '  {"INSTRUCTIONS": [{
         
               "title": "INSTRUCTIONS",
         
               "subtitle": "Please follow the questionnaire and select the answers that are closest to what you sensed during product tasting. Remember, there are no right or wrong answers.",
         
               "select_type": 4
         
            }],
         
         
            "APPEARANCE": [{
         
               "title": "Evenness in size",
         
               "select_type": 1,
         
               "is_intensity": 0,
         
               "is_nested_question": 0,
         
               "is_mandatory": 1,
         
               "option": "Full pieces,Evenly cut pieces,Unevenly cut pieces"
         
            }, {
         
               "title": "Physical status of the nuts",
         
               "select_type": 1,
         
               "is_intensity": 0,
         
               "is_nested_question": 0,
         
               "is_mandatory": 1,
         
               "option": "Uniform size,Natural form,Mixed size"
         
            }, {
         
               "title": "Spice coating",
         
               "select_type": 1,
         
               "is_intensity": 0,
         
               "is_nested_question": 0,
         
               "is_mandatory": 1,
         
               "option": "Even,Uneven"
         
            }, {
         
               "title": "Surface texture",
         
               "select_type": 2,
         
               "is_intensity": 0,
         
               "is_nested_question": 0,
         
               "is_mandatory": 1,
         
               "option": "Dehydrated,Bright,Rough,Smooth,Dry,Moist,Wet,Oily,Roasted,Crisp"
         
            }, {
         
               "title": "Overall preference",
         
               "select_type": 5,
         
               "is_intensity": 0,
         
               "is_nested_question": 0,
         
               "is_mandatory": 1,
         
		        "option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
         
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
         
                  "intensity_value": "15",
         
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
         
		          "option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
         
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
         
                  "intensity_value": "15",
         
                  "is_nested_question": 0,
         
                  "is_mandatory": 1,
         
                  "option": "Sweet,Salt,Sour,Bitter,Astringent,Pungent,Umami,Bland"
         
               }, {
         
                  "title": "Length of after taste",
         
                  "select_type": 1,
         
                  "is_intensity": 0,
         
                  "is_nested_question": 0,
         
                  "is_mandatory": 1,
         
                  "option": "Barely,Short,Sufficient,Long"
         
               }, {
         
                  "title": "Chemical feeling factor (if observed)",
         
                  "select_type": 2,
         
                  "is_intensity": 1,
         
                  "intensity_type": 1,
         
                  "intensity_value": "15",
         
                  "is_nested_question": 0,
         
                  "is_mandatory": 1,
         
                  "option": "Warm sensation spices,Chillies,Astringent,Hot temperature,Cold temperature"
         
               }, {
         
                  "title": "Was the taste of nuts… ? ",
         
                  "select_type": 1,
         
                  "is_intensity": 0,
         
                  "is_nested_question": 0,
         
                  "is_mandatory": 1,
         
                  "option": "Preserved,Enhanced,Masked by seasoning"
         
               }, {
         
                  "title": "Overall preference",
         
                  "select_type": 5,
         
                  "is_intensity": 0,
         
                  "is_nested_question": 0,
         
                  "is_mandatory": 1,
         
		           "option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
         
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
         
                  "intensity_value": "15",
         
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
         
		            "option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
         
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
         
                  "option": "Dry,Oily,Smooth,Rough,Loose particles"
         
               }, {
         
                  "title": "Identify type of sound",
         
                  "subtitle": "Crispy: One single high pitch sound. Crunchy: Multiple low pitch sounds in a series. Crackly: One sudden low pitch sound, brittles product.",
         
                  "select_type": 2,
         
                  "is_intensity": 1,
         
                  "intensity_type": 1,
         
                  "intensity_value": "15",
         
                  "is_nested_question": 0,
         
                  "is_mandatory": 1,
         
                  "option": "Crispy,Crunchy,Crackly"
         
               }, {
         
                  "title": "First Chew",
         
                  "is_nested_question": 1,
         
                  "question": [{
         
                        "title": "Hardness",
         
                        "select_type": 1,
         
                        "is_intensity": 0,
         
                        "is_nested_question": 0,
         
                        "is_mandatory": 1,
         
                        "option": "Low,Medium,High"
         
                     }, {
         
                        "title": "Roughness",
         
                        "select_type": 1,
         
                        "is_intensity": 0,
         
                        "is_nested_question": 0,
         
                        "is_mandatory": 1,
         
                        "option": "Low,Medium,High"
         
                     }, {
         
                        "title": "Sound",
         
                        "select_type": 1,
         
                        "is_intensity": 0,
         
                        "is_nested_question": 0,
         
                        "is_mandatory": 1,
         
                        "option": "Low,Medium,High"
         
                     }, {
         
                        "title": "Loose particles",
         
                        "select_type": 1,
         
                        "is_intensity": 0,
         
                        "is_nested_question": 0,
         
                        "is_mandatory": 1,
         
                        "option": "Low,Medium,High"
         
                     }, {
         
                        "title": "Burst of flavour",
         
                        "select_type": 1,
         
                        "is_intensity": 0,
         
                        "is_nested_question": 0,
         
                        "is_mandatory": 1,
         
                        "option": "Low,Medium,High"
         
                     }]
         
               }, {
         
                  "title": "Chew-down experience",
         
                  "is_nested_question": 1,
         
                  "question": [{
         
                        "title": "Moisture absorption",
         
                        "select_type": 1,
         
                        "is_intensity": 0,
         
                        "is_nested_question": 0,
         
                        "is_mandatory": 1,
         
                        "option": "Low,Medium,High"
         
                     }, {
         
                        "title": "Abrasiveness of mass",
         
                        "select_type": 1,
         
                        "is_intensity": 0,
         
                        "is_nested_question": 0,
         
                        "is_mandatory": 1,
         
                        "option": "Low,Medium,High"
         
                     }, {
         
                        "title": "Moistness",
         
                        "select_type": 1,
         
                        "is_intensity": 0,
         
                        "is_nested_question": 0,
         
                        "is_mandatory": 1,
         
                        "option": "Low,Medium,High"
         
                     }, {
         
                        "title": "Persistence of sound",
         
                        "select_type": 1,
         
                        "is_intensity": 0,
         
                        "is_nested_question": 0,
         
                        "is_mandatory": 1,
         
                        "option": "Low,Medium,High"
         
                     }, {
         
                        "title": "Cohesiveness of mass",
         
                        "select_type": 1,
         
                        "is_intensity": 0,
         
                        "is_nested_question": 0,
         
                        "is_mandatory": 1,
         
                        "option": "Low,Medium,High"
         
                     }]
         
               }, {
         
                  "title": "Residual",
         
                  "is_nested_question": 1,
         
                  "question": [{
         
                        "title": "Mouth coating",
         
                        "select_type": 1,
         
                        "is_intensity": 0,
         
                        "is_nested_question": 0,
         
                        "is_mandatory": 1,
         
                        "option": "Low,Medium,High"
         
                     }, {
         
                        "title": "Oily film",
         
                        "select_type": 1,
         
                        "is_intensity": 0,
         
                        "is_nested_question": 0,
         
                        "is_mandatory": 1,
         
                        "option": "Low,Medium,High"
         
                     }, {
         
                        "title": "Toothstick",
         
                        "select_type": 1,
         
                        "is_intensity": 0,
         
                        "is_nested_question": 0,
         
                        "is_mandatory": 1,
         
                        "option": "Low,Medium,High"
         
                     }]
         
               }, {
         
                  "title": "Overall preference",
         
                  "select_type": 5,
         
                  "is_intensity": 0,
         
                  "is_nested_question": 0,
         
                  "is_mandatory": 1,
         
		            "option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
         
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
         
		        "option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
         
            }, {
         
               "title": "Comments",
         
               "select_type": 3,
         
               "is_intensity": 0,
         
               "is_mandatory": 0,
         
               "is_nested_question": 0
         
            }]
         
  }';
        $headerInfo4 = [['header_name'=>"INSTRUCTIONS"],
            ['header_name'=>"APPEARANCE","header_info"=>"OBSERVE the visual aspect of the product like it's shape, density of mass and color."],

            ['header_name'=>"AROMA","header_info"=>"SNIFF the product. If you experienced aroma, fill up this section. Otherwise, move to the next section."],

            ['header_name'=>"TASTE","header_info"=>"TAKE A BITE and figure out basic taste(s) you experienced."],

            ['header_name'=>"AROMATICS","header_info"=>"OBSERVE the smell that was released after you chewed the product."],

            ['header_name'=>"ORAL TEXTURE","header_info"=>"CHEW the product multiple times. Observe if it sticks to the mouth, its loose particles and after-taste."],

            ['header_name'=>"OVERALL PREFERENCE","header_info"=>"RATE the overall experience of the product and provide some comments."],

        ];
        $data = ['name'=>'Seasoned nuts','keywords'=>"nuts",'description'=>'Seasoned nuts',
             'question_json'=>$questions4,'header_info'=>json_encode($headerInfo4,true)];

         \DB::table('global_questions')->insert($data);
    }


}
