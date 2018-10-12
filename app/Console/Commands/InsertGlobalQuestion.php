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
            ['header_name'=>"APPEARANCE","header_info"=>"Pour the beverage into the transparent glass. Conduct the visual examination of the beverage as advice and then look for natural look or factory made look. Anything too good or too bad observed please highlight in the comment box at the end of the section."],
            ['header_name'=>"AROMA","header_info"=>"Aroma comes from ingredients used and/or the processes followed like cooking, baking etc. To experience aroma, take a deep breath in and if you don't get any aroma then take short, quick and strong sniffs like how a dog sniffs."],
            ['header_name'=>"TASTE","header_info"=>"How to Taste and Slurp? Take a deep breath first, pucker your lips and slurp forcefully the liquor up into your mouth from the surface of the spoon. The louder the better, you do this to mix oxygen with the beverage as it helps to bring the aromas to rise. Anything too good or too bad observed, please highlight in the comment box at the end of the section."],
            ['header_name'=>"AROMATICS","header_info"=>"Aromatics is experiencing odour/s inside the mouth, as you eat. Slurp noisily while keeping your mouth closed and exhale through the nose. Identify the odours using the aroma options. Anything too good or too bad, please highlight in the comment box at the end of the section."],
            ['header_name'=>"FLAVOR","header_info"=>"As a rule of thumb, Flavor is a combination of Taste (25%) and Aromatics (75%) . Congratulations! You just discovered the Flavor of the product that you are tasting."],
            ['header_name'=>"ORAL TEXTURE","header_info"=>"Let us assess all the elements of texture, please follow steps, as outlined below."],
            ['header_name'=>"OVERALL PREFERENCE","header_info"=>"Rate the overall experience of the product."],
        ];

        $questions2 = '{
	"INSTRUCTIONS": [{
		"title": "INSTRUCTIONS",
		"subtitle": "Please follow the questionnaire and select the answers that are closest to what you sensed during the product tasting. Remember, there are no right or wrong answers. In case you observe something missing in the options kindly write it in the comments box. Any attribute too good or too bad should also be highlighted in the comments box at the end of each section.",
		"select_type": 4
	}],

	"APPEARANCE": [{
			"title": "Identify the color and mention it in the comment box.",
			"select_type": 1,
			"is_mandatory": 1,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense",
			"is_nested_question": 0,
			"option": "Intensity of identified color"
		},
		{
			"title": "About Color",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Natural,Synthetic,Bright,Dull,Shiny"
		},
		{
			"title": "Surface Texture (without touch)",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Silky,Frothy,Bubbly,Homogeneous,Water separated,Sediments,Seed awareness,Fibre awareness,Skin awareness,Pulp awareness"
		},
		{
			"title": "Visual feel of the beverage",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Juice from concentrate,100% Juice,Pulpy Juice,Nectar Juice,Freshly squeezed juice,Distilled,Blended,Natural look,Unnatural look"
		},
		{
			"title": "Viscosity",
			"subtitle": "Take beverage in a big spoon and pour from it to assess the ease of flow.",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Does not leave spoon,Drops partially or fully but does not fall,Flows reluctantly,Flows smoothly but slowly,Flows with moderate speed,Flows,Flows freely,Flows very freely"
		},
		{
			"title": "Any OFF Appearance",
			"subtitle": "(If yes, describe in comment)",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		},
		{
			"title": "Overall preference",
			"select_type": 5,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "Dislike Extremely",
					"color_code": "#8C0008"
				},
				{
					"value": "Dislike Strongly",
					"color_code": "#D0021B"
				},
				{
					"value": "Dislike Moderately",
					"color_code": "#C92E41"
				},
				{
					"value": "Can\'t Say",
					"color_code": "#E27616"
				},
				{
					"value": "Like Slightly",
					"color_code": "#AC9000"
				},
				{
					"value": "Like Moderately",
					"color_code": "#7E9B42"
				},
				{
					"value": "Like Strongly",
					"color_code": "#577B33"
				},
				{
					"value": "Like Extremely",
					"color_code": "#305D03"
				}
			]
		},
		{
			"title": "Comments",
			"select_type": 3,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 0
		}
	],

	"AROMA": [{
			"title": "Aromas observed",
			"subtitle": "We have list of more than 400 aromas, grouped under 11 heads. If you select \'Any other\' option please write the identified aroma in the comment box. Use the search box to assess the aroma list.",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Very Mild,Mild,Distinct mild,Distinct,Distinct strong,Strong,Overwhelming",
			"is_nested_question": 0,
			"is_mandatory": 1,
			"is_nested_option": 1,
			"nested_option_list": "AROMA"
		},
		{
			"title": "How would you describe the Aroma?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Pleasant,Inviting,Mouthwatering,Unpleasant,Uninviting,Repelling,Natural,Unnatural"
		},
		{
			"title": "If you experienced any off (bad) aroma, please indicate the intensity.",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Very Mild,Mild,Distinct mild,Distinct,Distinct strong,Strong,Overwhelming",
			"is_nested_question": 0,
			"is_mandatory": 0,
			"is_nested_option": 1,
			"nested_option_list": "OFFAROMA"
		},
		{
			"title": "Overall preference",
			"select_type": 5,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "Dislike Extremely",
					"color_code": "#8C0008"
				},
				{
					"value": "Dislike Strongly",
					"color_code": "#D0021B"
				},
				{
					"value": "Dislike Moderately",
					"color_code": "#C92E41"
				},
				{
					"value": "Can\'t Say",
					"color_code": "#E27616"
				},
				{
					"value": "Like Slightly",
					"color_code": "#AC9000"
				},
				{
					"value": "Like Moderately",
					"color_code": "#7E9B42"
				},
				{
					"value": "Like Strongly",
					"color_code": "#577B33"
				},
				{
					"value": "Like Extremely",
					"color_code": "#305D03"
				}
			]
		},
		{
			"title": "Comments",
			"select_type": 3,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 0
		}
	],

	"TASTE": [{
			"title": "Basic Taste",
			"is_nested_question": 1,
			"question": [{
					"title": "Sweet",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"title": "Salt",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"title": "Sour",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Neutral,Barely acidic,Mildly acidic,Moderately acidic,Strongly acidic,Intensely acidic,Very intensely acidic,Extremely acidic"
				},
				{
					"title": "Bitter",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"title": "Umami",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				}
			]
		},
		{
			"title": "Ayurveda Taste",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Barely detectable,Identifiable but not intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense",
			"is_nested_question": 0,
			"is_mandatory": 0,
			"option": "Astringent (Dryness),Pungent- masala (Warm spices),Pungent- cool sensation (Cool spices),Pungent- chilli"
		},
		{
			"title": "Is this beverage fermented?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		},
		{
			"title": "Based on your experience so far, how would you describe your beverage?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Plain bottled water,Natural water,Infused water,Infused tea,Infused coffee,Functional beverage,Energy drinks,Carbonated beverages,Spritzer,Carbonated fruit beverage,Fruit punch,Fruit syrup,Fruit drink,Fruit squash,Fruit cordial,Fruit juice,Fruit juice concentrate,Fruit nectar (Sherbet)"
		},
		{
			"title": "Overall preference",
			"select_type": 5,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "Dislike Extremely",
					"color_code": "#8C0008"
				},
				{
					"value": "Dislike Strongly",
					"color_code": "#D0021B"
				},
				{
					"value": "Dislike Moderately",
					"color_code": "#C92E41"
				},
				{
					"value": "Can\'t Say",
					"color_code": "#E27616"
				},
				{
					"value": "Like Slightly",
					"color_code": "#AC9000"
				},
				{
					"value": "Like Moderately",
					"color_code": "#7E9B42"
				},
				{
					"value": "Like Strongly",
					"color_code": "#577B33"
				},
				{
					"value": "Like Extremely",
					"color_code": "#305D03"
				}
			]
		},
		{
			"title": "Comments",
			"select_type": 3,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 0
		}
	],

	"AROMATICS": [{
			"title": "Aromatics observed",
			"subtitle": "We have list of more than 400 aromas, grouped under 11 heads. If you select \'Any other\' option please write the identified aroma in the comment box. Use the search box to assess the aroma list.",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Very Mild,Mild,Distinct mild,Distinct,Distinct strong,Strong,Overwhelming",
			"is_nested_question": 0,
			"is_mandatory": 1,
			"is_nested_option": 1,
			"nested_option_list": "AROMA"
		},
		{
			"title": "Did you observe any off-aromatics or off-taste experience?",
			"subtitle": "If you select \'Any other\' option, mention felt Aromatics in the comment box.",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Very Mild,Mild,Distinct mild,Distinct,Distinct strong,Strong,Overwhelming",
			"is_nested_question": 0,
			"is_mandatory": 0,
			"is_nested_option": 1,
			"nested_option_list": "OFFAROMA"
		},
		{
			"title": "Aftertaste",
			"subtitle": "Gulp the beverage and assess the taste and aromatics felt in the mouth.",
			"is_nested_question": 1,
			"question": [{
					"title": "How was the aftertaste?",
					"select_type": 1,
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "None,Very Mild,Mild,Distinct mild,Distinct,Distinct strong,Strong,Overwhelming",
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Pleasant,Unpleasant"
				},
				{
					"title": "Length of the aftertaste?",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Short,Sufficient,Long"
				},
				{
					"title": "Any OFF Aftertaste (If yes, describe in comments)",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Yes,No"
				}
			]
		},
		{
			"title": "Overall preference",
			"select_type": 5,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "Dislike Extremely",
					"color_code": "#8C0008"
				},
				{
					"value": "Dislike Strongly",
					"color_code": "#D0021B"
				},
				{
					"value": "Dislike Moderately",
					"color_code": "#C92E41"
				},
				{
					"value": "Can\'t Say",
					"color_code": "#E27616"
				},
				{
					"value": "Like Slightly",
					"color_code": "#AC9000"
				},
				{
					"value": "Like Moderately",
					"color_code": "#7E9B42"
				},
				{
					"value": "Like Strongly",
					"color_code": "#577B33"
				},
				{
					"value": "Like Extremely",
					"color_code": "#305D03"
				}
			]
		},
		{
			"title": "Comments",
			"select_type": 3,
			"is_intensity": 0,
			"is_mandatory": 0,
			"is_nested_question": 0
		}
	],

	"FLAVOR": [{
			"title": "Are you satisfied with the flavors?",
			"subtitle": "If you want any change in flavor like intensity or any other thing then mention it in comment box.",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		},
		{
			"title": "What is the flavor like?",
			"subtitle": "If selected \'Any other\', mention observed flavor in the comments box.",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yummy,Divine,Delicious,Zesty,Palatable,Appealing,Amazing,Unpalatable,Awful,Any other"
		},
		{
			"title": "Overall preference",
			"select_type": 5,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "Dislike Extremely",
					"color_code": "#8C0008"
				},
				{
					"value": "Dislike Strongly",
					"color_code": "#D0021B"
				},
				{
					"value": "Dislike Moderately",
					"color_code": "#C92E41"
				},
				{
					"value": "Can\'t Say",
					"color_code": "#E27616"
				},
				{
					"value": "Like Slightly",
					"color_code": "#AC9000"
				},
				{
					"value": "Like Moderately",
					"color_code": "#7E9B42"
				},
				{
					"value": "Like Strongly",
					"color_code": "#577B33"
				},
				{
					"value": "Like Extremely",
					"color_code": "#305D03"
				}
			]
		},
		{
			"title": "Comments",
			"select_type": 3,
			"is_intensity": 0,
			"is_mandatory": 0,
			"is_nested_question": 0
		}
	],

	"ORAL TEXTURE": [{
			"title": "Can you drink the beverage with a straw?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yes,Yes but with difficulty,No"
		},
		{
			"title": "Slipperiness",
			"subtitle": "Take a sip and express slipperiness by selecting valid options.",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Like water,Like toned milk,Like full cream milk,Like syrup"
		},
		{
			"title": "Did you feel any particles inside your mouth?",
			"subtitle": "If selected \'Any other\' option, please mention in the comment box.",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Like sand,Like pulp,Like seeds,Like fibres,Any other"
		},
		{
			"title": "Assess the feeling of the above identified particles inside your mouth.",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Smooth,Rough"
		},
		{
			"title": "Is the beverage or its particles sticking on the palate or teeth?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		},
		{
			"title": "Did you feel anything left in the mouth after swallowing?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		},
		{
			"title": "If yes, was it...?",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 0,
			"option": "Pulp,Loose particles,Chalky,Fibre"
		},
		{
			"title": "As you have assessed the beverage for all the attributes, is this beverage…?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "From concentrate,Not from concentrate,Nectar,Pulpy,Fully distilled,Semi distilled"
		},
		{
			"title": "Overall preference",
			"select_type": 5,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "Dislike Extremely",
					"color_code": "#8C0008"
				},
				{
					"value": "Dislike Strongly",
					"color_code": "#D0021B"
				},
				{
					"value": "Dislike Moderately",
					"color_code": "#C92E41"
				},
				{
					"value": "Can\'t Say",
					"color_code": "#E27616"
				},
				{
					"value": "Like Slightly",
					"color_code": "#AC9000"
				},
				{
					"value": "Like Moderately",
					"color_code": "#7E9B42"
				},
				{
					"value": "Like Strongly",
					"color_code": "#577B33"
				},
				{
					"value": "Like Extremely",
					"color_code": "#305D03"
				}
			]
		},
		{
			"title": "Comments",
			"select_type": 3,
			"is_intensity": 0,
			"is_mandatory": 0,
			"is_nested_question": 0
		}
	],

	"OVERALL PREFERENCE": [{
			"title": "How will you categorize the beverage?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Nutritional,Thirst quencher"
		},
		{
			"title": "Is their balance between natural and artificial component?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		},
		{
			"title": "To make it more appealing, which attributes need improvement?",
			"subtitle": "Elaborate in the comment box.",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 0,
			"option": "None,Appearance,Aroma,Taste,Aromatics,Flavor,Oral texture"
		},
		{
			"title": "Is the product sample acceptable?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		},
		{
			"title": "Overall product experience ",
			"select_type": 5,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "Dislike Extremely",
					"color_code": "#8C0008"
				},
				{
					"value": "Dislike Strongly",
					"color_code": "#D0021B"
				},
				{
					"value": "Dislike Moderately",
					"color_code": "#C92E41"
				},
				{
					"value": "Can\'t Say",
					"color_code": "#E27616"
				},
				{
					"value": "Like Slightly",
					"color_code": "#AC9000"
				},
				{
					"value": "Like Moderately",
					"color_code": "#7E9B42"
				},
				{
					"value": "Like Strongly",
					"color_code": "#577B33"
				},
				{
					"value": "Like Extremely",
					"color_code": "#305D03"
				}
			]
		},
		{
			"title": "Comments",
			"select_type": 3,
			"is_intensity": 0,
			"is_mandatory": 0,
			"is_nested_question": 0
		}
	]
}';

        $data = ['name'=>'Beverage','keywords'=>"Beverage",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];
        \DB::table('global_questions')->insert($data);






    }
}
