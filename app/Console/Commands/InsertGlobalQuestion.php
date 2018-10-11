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

            ['header_name'=>"APPEARANCE","header_info"=>"Visual examination of the product- look for color, surface texture, evenness, natural-look, factory-made look etc."],

            ['header_name'=>"AROMA","header_info"=>"Aroma comes from ingredients used and/or the processes followed like cooking, baking etc. To experience aroma, take a deep breath in and if you don't get any aroma then take short, quick and strong sniffs like how a dog sniffs."],

            ['header_name'=>"TASTE","header_info"=>"Slurp noisily and assess the taste/s. Anything too good or too bad, please highlight in the comment box at the end of the section. If you find the sample to be bland, please mention in the comment box."],

            ['header_name'=>"AROMATICS","header_info"=>"Aromatics is the odour/s that you would experience inside your mouth, as you eat. Slurp noisily again, keeping your mouth closed and exhale through the nose and try to identify the odours using the aroma options. Anything too good or too bad may please be highlighted in the comment box."],
            ['header_name'=>"FLAVOR","header_info"=>"As a rule of thumb, Flavor is a combination of Taste (25%) and Aromatics (75%). Congratulations! You just discovered the Flavor of the product that you are tasting."],

            ['header_name'=>"ORAL TEXTURE","header_info"=>"Let us assess the real texture- please look for body and mouthfeel of the beverage"],

            ['header_name'=>"OVERALL PREFERENCE","header_info"=>"RATE the overall experience of the product on the preference."],

        ];
        ;
        $questions2 = '{

	"INSTRUCTIONS": [{
		"title": "INSTRUCTION",
		"subtitle": "Please follow the questionnaire and click answers that match with your observation/s. Remember, there are no right or wrong answers. In case you observe something that is not covered in the questionnaire, you are most welcome to share your additional inputs in the comments box. Anything that stands out as either too good or too bad, may please be highlighted in the comments box.",
		"select_type": 4
	}],


	"APPEARANCE": [{
			"title": "Attributes which meet your expectations",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Color,Texture,Factory-made (uniform and even),Natural (uneven and not uniform but looks nice)"
		},
		{
			"title": "Attributes which need corrections",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Color,Texture,Factory-made (uniform and even),Natural (uneven and not uniform but looks nice)"
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
			"title": "Aroma observed",
			"subtitle": "(We have list of more than 400 aromas, grouped under 11 heads. If you select \"any other\" option please write the identified aroma in the comment box. Use the search box to assess the aroma list.)",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming",
			"is_nested_question": 0,
			"is_mandatory": 1,
			"is_nested_option": 1,
			"nested_option_list": "AROMA"
		},
		{
			"title": "How would you describe the Aroma ?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Pleasant,Inviting,Mouthwatering,Unpleasant,Uninviting,Repelling"
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
			"is_mandatory": 1,
			"question": [{
					"title": "Sweet",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"title": "Salt",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"title": "Sour",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Neutral,Barely Acidic,Mildly Acidic,Moderately Acidic,Strongly Acidic,Intensely Acidic,Very Intensely Acidic,Extremely Acidic"
				},
				{
					"title": "Bitter",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"title": "Umami",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				}
			]
		},
		{
			"title": "Ayurveda Taste",
			"select_type": 2,
			"is_mandatory": 1,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense",
			"is_nested_question": 0,
			"option": "Astringent,Pungent- Masala (Warm Spices),Pungent- Cool Sensation (Cool Spices),Pungent- Chilli"
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
			"option": "Plain Bottled Water,Natural Water,Infused Water,Infused Tea,Infused Coffee,Functional Beverage,Energy Drinks,Carbonated Beverages,Spritzer,Carbonated Fruit Beverage,Fruit Punch,Fruit Syrup,Fruit Drink,Fruit Squash,Fruit Cordial,Fruit Juice,Fruit Juice Concentrate,Fruit Nectar (Sherbet)"
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
			"subtitle": "(Same as mentioned in Aroma section)",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming",
			"is_nested_question": 0,
			"is_mandatory": 1,
			"is_nested_option": 1,
			"nested_option_list": "AROMA"
		},
		{
			"title": "How would you describe the Aromatics ?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Pleasant,Inviting,Mouthwatering,Unpleasant,Uninviting,Repelling"
		},
		{
			"title": "Aftertaste",
			"subtitle": "(Swallow the sample and assess the sensation on your tongue, inside your mouth etc) Identify a particular aftertaste in the comment box.",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "None,Pleasant,Unpleasant,Short,Long,Sufficient"
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


	"FLAVOR": [{
			"title": "What is the Flavor like ?",
			"subtitle": "If you select \"any other\" option please write it in the comment box",
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
			"is_nested_question": 0,
			"is_mandatory": 0
		}
	],

	"ORAL TEXTURE": [{
			"title": "Body",
			"subtitle": "Refers to the heaviness of texture",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Syrup,Full cream milk,Toned Milk,Watery,Any other"
		},
		{
			"title": "Mouthfeel of the beverage",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Chocolatey,Earthy,Bright,Mellow,Sharp,Dry,Brisk,Complex,Delicate,Silky,Velvety,Raw,Heavy,Opulent,Biting,Astringent,Short,Frivolous,Fresh,Watery,Warm,Smooth"
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


	"OVERALL PREFERENCE": [{
			"title": "Is the product sample acceptable?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		},
		{
			"title": "To make it more appealing, which attribute/s needs improvement.",
			"subtitle": "Elaborate in the comment box.",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "None,Appearance,Aroma,Taste,Aromatics,Flavor,Texture"
		},
		{
			"title": "Are all the Six attributes (appearance, aroma, taste, aromatics, flavor and texture) in harmony (balanced) with each other?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		},
		{
			"title": "If not, what is/are out of harmony (balance)?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Appearance,Aroma,Taste,Aromatics,Flavor,Texture"
		},
		{
			"title": "Overall Product Experience",
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

        $data = ['name'=>'General Beverages','keywords'=>"Beverages",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];
        \DB::table('global_questions')->insert($data);












        $headerInfo2 = [['header_name'=>"INSTRUCTIONS"],
            ['header_name'=>"APPEARANCE","header_info"=>"Visual examination of the product- look for color, surface texture, evenness, natural-look, factory-made look etc."],
            ['header_name'=>"AROMA","header_info"=>"Aroma comes from ingredients used and/or the processes followed like cooking, baking etc. To experience aroma, take a deep breath in and if you don't get any aroma then take quick strong sniffs like how a dog sniffs."],
            ['header_name'=>"TASTE","header_info"=>"Take a bite or multiple bites and assess the taste/s. Anything too good or too bad, please highlight in the comment box at the end of the section. If you find sample to be bland, please mention in the comment box."],
            ['header_name'=>"AROMATICS","header_info"=>"Aromatics is the odour/s that you would experience inside your mouth, as you eat. Take a bite again, eat normally, keeping your mouth closed and exhale through the nose and try to identify the odours using the aroma options. Anything too good or too bad may please be highlighted in the comment box."],
            ['header_name'=>"FLAVOR","header_info"=>"As a rule of thumb, Flavor is a combination of Taste (25%) and Aromatics (75%). Congratulations! You just discovered the Flavor of the product that you are tasting."],
            ['header_name'=>"ORAL TEXTURE","header_info"=>"Let us assess the real texture- please look for lip feel, first chew experience, chew down experience, swallow, and most importantly sound."],
            ['header_name'=>"OVERALL PREFERENCE","header_info"=>"RATE the overall experience of the product on the preference."],
        ]
        ;
        $questions2 = '{

	"INSTRUCTIONS": [{
		"title": "Instruction",
		"subtitle": "Please follow the questionnaire and click answers that match with your observation/s. Remember, there are no right or wrong answers. In case you observe something that is not covered in the questionnaire, you are most welcome to share your additional inputs in the comments box. Anything that stands out as either too good or too bad, may please be highlighted in the comments box.",
		"select_type": 4
	}],


	"APPEARANCE": [{
			"title": "Attributes which meet your expectations",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Color,Texture,Factory-made (uniform and even),Natural (uneven and not uniform but looks nice)"
		},
		{
			"title": "Attributes which need corrections",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Color,Texture,Factory-made (uniform and even),Natural (uneven and not uniform but looks nice)"
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
			"title": "Aroma observed",
			"subtitle": "(We have list of more than 400 aromas, grouped under 11 heads. If you select \"any other\" option please write the identified aroma in the comment box. Use the search box to assess the aroma list.)",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming",
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
			"option": "Pleasant,Inviting,Mouthwatering,Unpleasant,Uninviting,Repelling"
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
			"is_mandatory": 1,
			"question": [{
					"title": "Sweet",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"title": "Salt",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"title": "Sour",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Neutral,Barely Acidic,Mildly Acidic,Moderately Acidic,Strongly Acidic,Intensely Acidic,Very Intensely Acidic,Extremely Acidic"
				},
				{
					"title": "Bitter",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"title": "Umami",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				}
			]
		},
		{
			"title": "Ayurveda Taste",
			"select_type": 2,
			"is_mandatory": 1,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense",
			"is_nested_question": 0,
			"option": "Astringent,Pungent- Masala (Warm Spices),Pungent- Cool Sensation (Cool Spices),Pungent- Chilli"
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
			"subtitle": "(Same as mentioned in Aroma section)",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming",
			"is_nested_question": 0,
			"is_mandatory": 1,
			"is_nested_option": 1,
			"nested_option_list": "AROMA"
		},
		{
			"title": "How would you describe the Aromatics?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Pleasant,Inviting,Mouthwatering,Unpleasant,Uninviting,Repelling"
		},
		{
			"title": "Aftertaste",
			"subtitle": "(Swallow the sample and assess the sensation on your tongue, inside your mouth etc) Identify a particular aftertaste in the comment box.",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "None,Pleasant,Unpleasant,Short,Long,Sufficient"
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

	"FLAVOR": [{
			"title": "What is the Flavor like ?",
			"subtitle": "If you select \"any other\" option please write it in the comment box",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yummy,Divine,Delicious,Zesty,Palatable,Appealing,Amazing,Unpalatable,Awful,Any other"
		},
		{
			"title": "Are these Flavors reminding you of any combined flavors that are currently trending",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Wasabi,Sriracha,Smoky Barbeque,Tandoori,Kebab,Jalapeno Cheese,Chipotle,Sour cream and onion,Salsa,Pudina chutney,Creamy truffle"
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


	"ORAL TEXTURE": [{
			"title": "Surface texture",
			"subtitle": "(Hold product between your lips)",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Smooth,Rough,Sticky on lips,Oily on lips"
		},
		{
			"title": "Sound of the product",
			"subtitle": "(Concentrate on sound it produces after the first bite and subsequent bites)",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Crispy,Crunchy,Crackly"
		},
		{
			"title": "Oral Feel",
			"subtitle": "(First chew)",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Soft,Firm,Hard,Moist,Dry,Creamy,Spongy,Runny liquid"
		},
		{
			"title": "Chewing experience",
			"subtitle": "(First chew)",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Shorter time to chew,Moderate time to chew,Longer time to chew,Melt in the mouth,"
		},
		{
			"title": "After swallowing, how do you feel inside the mouth?",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Oily film,Loose particles,Sticking on tooth,Chalky,None"
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


	"OVERALL PREFERENCE": [{
			"title": "Is the product sample acceptable ?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		},
		{
			"title": "To make it more appealing, which attribute/s needs improvement.",
			"subtitle": "Elaborate in the comment box.",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "None,Appearance,Aroma,Taste,Aromatics,Flavor,Texture"
		},
		{
			"title": "Are all the Six attributes (appearance, aroma, taste, aromatics, flavor and texture) in harmony (balanced) with each other?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		},
		{
			"title": "If not, what is/are out of harmony (balance)?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Appearance,Aroma,Taste,Aromatics,Flavor,Texture"
		},
		{
			"title": "Overall Product Experience",
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
        $data = ['name'=>'General Food','keywords'=>"Food",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];
        \DB::table('global_questions')->insert($data);











        $headerInfo2 = [['header_name'=>"INSTRUCTIONS"],

            ['header_name'=>"APPEARANCE","header_info"=>"Visually observe the product, e.g., color, surface appearance , surface texture (without touching and also touch it). 
 Any attribute that stands out as either too good or too bad, may please be highlighted in the comments box at the end of each section."],

            ['header_name'=>"AROMA","header_info"=>"Aroma comes from ingredients used and/or the processes followed like cooking, baking etc. To experience aroma, take a deep breath in and if you don't get any aroma then take short, quick and strong sniffs like how a dog sniffs."],

            ['header_name'=>"TASTE","header_info"=>"Take a bite or multiple bites and assess the taste/s . Anything too good or too bad, please highlight in the comment box at the end of the section. If you find sample to be bland, please mention in the comment box."],

            ['header_name'=>"AROMATICS","header_info"=>"Aromatics is experiencing odour/s inside the mouth, as you eat. Take a bite again, eat normally, keeping your mouth closed and exhale through the nose. Identify the odours using the aroma options. Anything too good or too bad, please highlight in the comment box at the end of the section."],
            ['header_name'=>"ORAL TEXTURE","header_info"=>"Let us assess all the elements of texture, please follow steps, as outlined below:"],

            ['header_name'=>"OVERALL PREFERENCE","header_info"=>"RATE the overall experience of the product on the preference scale ."],

        ];

        $questions2 = '{

	"INSTRUCTIONS": [{
		"title": "INSTRUCTION",
		"subtitle": "Please follow the questionnaire and click answers that match with your observation/s. Remember, there are no right or wrong answers. In case you observe something that is not covered in the questionnaire, you are most welcome to share your additional inputs in the comments box. Anything that stands out as either too good or too bad, may please be highlighted in the comments box.",
		"select_type": 4
	}],


	"APPEARANCE": [{
			"title": "Visual Observation",
			"is_nested_question": 1,
			"is_mandatory": 1,
			"question": [{
					"title": "Surface Appearance",
					"select_type": 2,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Naturally plain,Spice coating,Chunky particles,Fine particles,Crystals "
				},
				{
					"title": "Identify the color and mention it in the comment box.",
					"select_type": 1,
					"is_intensity": 1,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Intensity of identified color",
					"intensity_type": 2,
					"intensity_value": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"title": "About color",
					"select_type": 2,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Natural,Synthetic (compound),Bright,Dull,Shiny,Glazed,Even,Uneven"
				},
				{
					"title": "Surface texture (without touch)",
					"select_type": 2,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Moist,Dry,Loose particles,Rough,Smooth,Melted look"
				}
			]
		},
		{
			"title": "Surface Texture (with touch)",
			"is_nested_question": 1,
			"is_mandatory": 1,
			"question": [{
					"title": "Hold the product in your hand and try to break it",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "No sound,Clear sound,Muffled sound,Flying particles,Sticks on finger,Fun to break,Not fun to break"
				},
				{
					"title": "Any OFF Appearance",
					"subtitle": "(If yes, describe in comment)",
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
			"is_nested_question": 0,
			"is_mandatory": 0
		}
	],


	"AROMA": [{
			"title": "Aroma observed",
			"subtitle": "(We have list of more than 400 aromas, grouped under 11 heads. If you select \"any other\" option please write the identified aroma in the comment box. Use the search box to assess the aroma list.)",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming",
			"is_nested_question": 0,
			"is_mandatory": 1,
			"is_nested_option": 1,
			"nested_option_list": "AROMA"
		},
		{
			"title": "How would you describe the Aroma ?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Pleasant,Inviting,Mouthwatering,Unpleasant,Uninviting,Repelling"
		},
		{
			"title": "Any Off (bad)- aroma, please indicate the intensity",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming",
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
			"is_mandatory": 1,
			"question": [{
					"title": "Sweet",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"title": "Salt",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"title": "Sour",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Neutral,Barely Acidic,Mildly Acidic,Moderately Acidic,Strongly Acidic,Intensely Acidic,Very Intensely Acidic,Extremely Acidic"
				},
				{
					"title": "Bitter",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"title": "Umami",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				}
			]
		},
		{
			"title": "Ayurveda Taste",
			"select_type": 2,
			"is_mandatory": 1,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense",
			"is_nested_question": 0,
			"option": "Astringent (Dryness),Pungent- Masala (Warm Spices),Pungent- Cool Sensation (Cool Spices),Pungent- Chilli"
		},
		{
			"title": "If you were to make your own chocolate what will be the combination of bitter and sweet taste",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "30% Bitter and 70% Sweet,70% Bitter and 30% Sweet,100% Bitter,15% Bitter,10% Bitter and 90% Sweet (Milk Chocolate)"
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
			"title": "Please identify aromatics experienced from the list.",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming",
			"is_nested_question": 0,
			"is_mandatory": 1,
			"is_nested_option": 1,
			"nested_option_list": "AROMA"
		},
		{
			"title": "How would you describe the Aromatics ?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Pleasant,Inviting,Mouthwatering,Unpleasant,Uninviting,Repelling,Strong,Overwhelming"
		},
		{
			"title": "Identify OFF Taste",
			"subtitle": "(Consider aromatics as well)",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 0,
			"is_nested_option": 1,
			"nested_option_list": "OFFAROMA"
		},
		{
			"title": "Aftertaste",
			"subtitle": "Please chew and swallow the product, if possible.",
			"is_nested_question": 1,
			"is_mandatory": 1,
			"question": [{
					"title": "How was the aftertaste?",
					"select_type": 1,
					"is_intensity": 1,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Pleasant,Unpleasant",
					"intensity_type": 2,
					"intensity_value": "None,Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming"
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
					"title": "Any OFF Aftertaste",
					"subtitle": "(If yes, describe in comments)",
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
			"is_nested_question": 0,
			"is_mandatory": 0
		}
	],


	"ORAL TEXTURE": [{
			"title": "Surface texture",
			"subtitle": "Hold the product between the lips",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Dry,Wet,Oily,Smooth,Ultra Smooth,Rough,Very Rough,Loose particles,Sticky"
		},
		{
			"title": "Please bite into the product and identify the sound/s observed.",
			"subtitle": "Crispy- one sound event- sharp, clean, fast and high pitched,e.g., Potato chips.\nCrunchy - multiple low pitched sounds perceived as a series of small events (Grinding),e.g., Rusks.\nCrackly- bite only once without grinding, it is one sudden low pitched sound event that brittles the product, e.g., cracker biscuits; sugar crystals are crackly too",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense",
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Crispy,Crunchy,Crackly"
		},
		{
			"title": "First Chew",
			"subtitle": "Chew for 3-4 times and pause.",
			"is_nested_question": 1,
			"is_mandatory": 1,
			"question": [{
					"title": "What happens to the product when it breaks?",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Crumbles,Fractures"
				},
				{
					"title": "How is the Hardness?",
					"subtitle": "(Hardness is the force needed to chew the sample)",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "No Force,Barely Any Force,Very Slight Force,Slight Force,Moderate Force,Moderately Strong Force,Strong Force,Extremely Strong Force"
				},
				{
					"title": "Size of pieces of crumbs (Chocolate or wafers) that fly while taking a bite",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Small,Sizeable chunks"
				},
				{
					"title": "Uniformity of bite",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Uniform,Non- Uniform"
				},
				{
					"title": "Burst of Flavour",
					"subtitle": "(Moisture release/ Juiciness)",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Dehydrated,Dry,Juiceless,Slightly Juicy,Juicy,Succulent,Syrupy,Mouthwatering"
				},
				{
					"title": "Denseness",
					"subtitle": "Compactness of the main ingredients",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Dense,Light,Fluffy,Tight,Rubbery"
				},
				{
					"title": "Cohesiveness",
					"subtitle": "(Degree to which sample shears or deforms)",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Shears,Partly shears and partly deforms,Deforms"
				},
				{
					"title": "Persistence of sound inside the mouth",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Yes,No"
				}
			]
		},
		{
			"title": "Chew down",
			"subtitle": "Chew the sample 8-10 times to make a pulp.",
			"is_nested_question": 1,
			"is_mandatory": 1,
			"question": [{
					"title": "Moisture absorption",
					"subtitle": "(Amount of saliva absorbed by the sample)",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "No Saliva Absorbed,Barely Any Saliva Absorbed,Very Slightly Saliva Absorbed,Slightly Saliva Absorbed,Moderately Saliva Absorbed,Plenty Saliva Absorbed,Loads of Saliva Absorbed,Extremely High quantity of Saliva Absorbed"
				},
				{
					"title": "Adhesiveness to the palate",
					"subtitle": "(Force needed to remove the product that has stuck to the palate.)",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "No Force,Barely Any Force,Very Slight Force,Slight Force,Moderate Force,Moderately Strong Force,Strong Force,Extremely Strong Force"
				},
				{
					"title": "Rate of Melt",
					"subtitle": "(Consider rate at which the product melts during chew down)",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Slow rate,Moderate rate,High Rate"
				},
				{
					"title": "Roughness of mass",
					"select_type": 2,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Smooth,Ultra smooth,Gritty,Grainy,Coarse,Abrasive (pointed particles),Lumpy"
				},
				{
					"title": "Is the sample sticking to teeth?",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Yes,No"
				}
			]
		},
		{
			"title": "Residual",
			"is_nested_question": 1,
			"is_mandatory": 1,
			"question": [{
					"title": "Do you feel anything left in mouth?",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Yes,No"
				},
				{
					"title": "If yes",
					"select_type": 2,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 0,
					"option": "Oily film,Loose particles,Sticking on tooth,Chalky"
				},
				{
					"title": "How would you describe the Mouthfeel?",
					"select_type": 2,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Delightful,Divine,Luxurious,Indulgent,Amazing,Crunchy,Velvety,Sumptuous"
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
			"is_nested_question": 0,
			"is_mandatory": 0
		}
	],


	"OVERALL PREFERENCE": [{
			"title": "To make it more appealing, which attribute/s needs improvement.",
			"subtitle": "Elaborate in the comment box.",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "None,Appearance,Aroma,Taste,Aromatics,Texture"
		},
		{
			"title": "Is the product sample acceptable ?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		},
		{
			"title": "Overall Product Experience",
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

        $data = ['name'=>'Kit-Kat','keywords'=>"Kit-Kat",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];
        \DB::table('global_questions')->insert($data);










        $headerInfo2 = [['header_name'=>"INSTRUCTIONS"],

            ['header_name'=>"APPEARANCE","header_info"=>"Visually observe the product, e.g., color, surface appearance , surface texture (without touching and also touch it). 
 Any attribute that stands out as either too good or too bad, may please be highlighted in the comments box at the end of each section."],

            ['header_name'=>"AROMA","header_info"=>"Aroma comes from ingredients used and/or the processes followed like cooking, baking etc. To experience aroma, take a deep breath in and if you don't get any aroma then take short, quick and strong sniffs like how a dog sniffs."],

            ['header_name'=>"TASTE","header_info"=>"Take a bite or multiple bites and assess the taste/s . Anything too good or too bad, please highlight in the comment box at the end of the section. If you find sample to be bland, please mention in the comment box."],

            ['header_name'=>"AROMATICS","header_info"=>"Aromatics is experiencing odour/s inside the mouth, as you eat. Take a bite again, eat normally, keeping your mouth closed and exhale through the nose. Identify the odours using the aroma options. Anything too good or too bad, please highlight in the comment box at the end of the section."],
            ['header_name'=>"FLAVOR","header_info"=>"As a rule of thumb, Flavor is a combination of Taste (25%) and Aromatics (75%) . Congratulations! You just discovered the Flavor of the product that you are tasting."],

            ['header_name'=>"ORAL TEXTURE","header_info"=>"Let us assess all the elements of texture, please follow steps, as outlined below:"],

            ['header_name'=>"OVERALL PREFERENCE","header_info"=>"RATE the overall experience of the product on the preference scale ."],

        ];
        $questions2 = '{

	"INSTRUCTIONS": [{
		"title": "Instruction",
		"subtitle": "Please follow the questionnaire and click answers that match with your observation/s. Remember, there are no right or wrong answers. In case you observe something that is not covered in the questionnaire, you are most welcome to share your additional inputs in the comments box. Anything that stands out as either too good or too bad, may please be highlighted in the comments box.",
		"select_type": 4
	}],

	"APPEARANCE": [{
			"title": "Visual Observation",
			"is_nested_question": 1,
			"is_mandatory": 1,
			"question": [{
					"title": "Surface Appearance",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Naturally plain,Spice coating"
				},
				{
					"title": "Identify the color and mention it in the comment box.",
					"select_type": 1,
					"is_intensity": 1,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Intensity of identified color",
					"intensity_type": 2,
					"intensity_value": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"title": "About Color",
					"select_type": 2,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Natural,Synthetic,Bright,Dull,Shiny,Glazed,Even,Uneven,Caramelized spots"
				},
				{
					"title": "Coating",
					"select_type": 2,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Spice,Sugar crystals,Chemical,Even,Blotchy (Patchy)"
				},
				{
					"title": "Surface texture (without touch)",
					"select_type": 2,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Moist,Dry,Oily,Sticky,Rough,Smooth,Dehydrated,Baked,Roasted,Fried,Crisp,Limp,Loose particles"
				},
				{
					"title": "Size",
					"select_type": 2,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Even,Uneven,Small,Medium,Large"
				},
				{
					"title": "Shape of pop corn",
					"subtitle": "(Base of the pop corn is called mushroom and the arms of popcorn are called fly)",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Round,Typical pop corn,"
				}
			]
		},
		{
			"title": "Surface Texture (with touch)",
			"is_nested_question": 1,
			"is_mandatory": 1,
			"question": [{
					"title": "Surface Texture (with touch)",
					"select_type": 2,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Moist,Wet,Oily,Sticky"
				},
				{
					"title": "Any OFF Appearance",
					"subtitle": "(If yes, describe in comment)",
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
			"is_nested_question": 0,
			"is_mandatory": 0
		}
	],

	"AROMA": [{
			"title": "Aroma observed",
			"subtitle": "(We have list of more than 400 aromas, grouped under 11 heads. If you select \"any other\" option please write the identified aroma in the comment box. Use the search box to assess the aroma list.)",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming",
			"is_nested_question": 0,
			"is_mandatory": 1,
			"is_nested_option": 1,
			"nested_option_list": "AROMA"
		},
		{
			"title": "How would you describe the Aroma ?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Pleasant,Inviting,Mouthwatering,Unpleasant,Uninviting,Repelling"
		},
		{
			"title": "Any Off (bad)- aroma, please indicate the intensity",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming",
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
			"is_mandatory": 1,
			"question": [{
					"title": "Sweet",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"title": "Salt",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"title": "Sour",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Neutral,Barely Acidic,Mildly Acidic,Moderately Acidic,Strongly Acidic,Intensely Acidic,Very Intensely Acidic,Extremely Acidic"
				},
				{
					"title": "Bitter",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"title": "Umami",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				}
			]
		},
		{
			"title": "Ayurveda Taste",
			"select_type": 2,
			"is_mandatory": 1,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense",
			"is_nested_question": 0,
			"option": "Astringent (Dryness),Pungent- Masala (Warm Spices),Pungent- Cool Sensation (Cool Spices),Pungent- Chilli"
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
			"title": "Please identify aromatics experienced from the list.",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming",
			"is_nested_question": 0,
			"is_mandatory": 1,
			"is_nested_option": 1,
			"nested_option_list": "AROMA"
		},
		{
			"title": "How would you describe the Aromatics ?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Pleasant,Inviting,Mouthwatering,Unpleasant,Uninviting,Repelling,Strong,Overwhelming"
		},
		{
			"title": "Identify OFF Taste",
			"subtitle": "(consider aromatics as well)",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 0,
			"is_nested_option": 1,
			"nested_option_list": "OFFAROMA"
		},
		{
			"title": "Aftertaste",
			"subtitle": "Please chew and swallow the product, if possible.",
			"is_nested_question": 1,
			"is_mandatory": 1,
			"question": [{
					"title": "How was the aftertaste?",
					"select_type": 1,
					"is_intensity": 1,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Pleasant,Unpleasant",
					"intensity_type": 2,
					"intensity_value": "None,Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming"
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
					"title": "Any OFF Aftertaste",
					"subtitle": "(If yes, describe in comments)",
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
			"is_nested_question": 0,
			"is_mandatory": 0
		}
	],

	"FLAVOR": [{
			"title": "Are you satisfied with the Flavors ?",
			"subtitle": "If you want any change in Flavor like intensity or any other change then mention it in the comment box",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		},
		{
			"title": "What is the Flavor like ?",
			"subtitle": "If selected \"Any other\" option mention it in the comment box",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yummy,Divine,Delicious,Zesty,Palatable,Appealing,Amazing,Unpalatable,Awful,Any other"
		},
		{
			"title": "Are these Flavors reminding you of any combined flavors that are currently trending",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Wasabi,Sriracha,Smoky Barbeque,Tandoori,Kebab,Jalapeno Cheese,Chipotle,Sour cream and onion,Salsa,Pudina chutney"
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

	"ORAL TEXTURE": [{
			"title": "Surface texture",
			"subtitle": "Hold the product between the lips",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Dry,Wet,Oily,Smooth,Rough,Loose particles,Sticky"
		},
		{
			"title": "Please bite into the product and identify the sound/s observed.",
			"subtitle": "Crispy- one sound event- sharp, clean, fast and high pitched,e.g., Potato chips.\nCrunchy - multiple low pitched sounds perceived as a series of small events (Grinding),e.g., Rusks.\nCrackly- bite only once without grinding, it is one sudden low pitched sound event that brittles the product, e.g., cracker biscuits; sugar crystals are crackly too",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense",
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Crispy,Crunchy,Crackly"
		},
		{
			"title": "First Chew",
			"subtitle": "Chew for 3-4 times and pause.",
			"is_nested_question": 1,
			"is_mandatory": 1,
			"question": [{
					"title": "Hardness",
					"subtitle": "Assess the force needed to chew the sample",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "No Force,Barely Any Force,Very Slight Force,Slight Force,Moderate Force,Moderately Strong Force,Strong Force,Extremely Strong Force"
				},
				{
					"title": "Uniformity of bite",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Uniform,Non- Uniform"
				},
				{
					"title": "Burst of Flavor",
					"subtitle": "(Moisture release/ Juiciness)",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Dehydrated,Dry,Juiceless,Slightly Juicy,Juicy,Succulent,Syrupy,Mouthwatering"
				},
				{
					"title": "Denseness",
					"subtitle": "Compactness of the main ingredients",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Dense,Fluffy,Tight,Rubbery"
				},
				{
					"title": "Cohesiveness",
					"subtitle": "(Shearness-breaking off due to structural strain)",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Extremely Shear,Very much Shear,Moderately Shear,Slightly Shear,Slightly Deform,Moderately Deform,Very much Deform,Extremely Deform"
				},
				{
					"title": "Persistence of sound inside the mouth",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Yes,No"
				}
			]
		},
		{
			"title": "Chew down",
			"subtitle": "Chew the sample 8-10 times to make a pulp.",
			"is_nested_question": 1,
			"is_mandatory": 1,
			"question": [{
					"title": "Moisture absorption",
					"subtitle": "(Amount of saliva absorbed by the sample)",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "No Saliva Absorbed,Barely Any Saliva Absorbed,Very Slightly Saliva Absorbed,Slightly Saliva Absorbed,Moderately Saliva Absorbed,Plenty Saliva Absorbed,Loads of Saliva Absorbed,Extremely High quantity of Saliva Absorbed"
				},
				{
					"title": "Cohesiveness of sample",
					"subtitle": "After chewing small particles may or may not come together to form a mass",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "No Mass Formed,Partial Mass Formed,Tight Mass Formed"
				},
				{
					"title": "Adhesiveness to the palate",
					"subtitle": "(Force needed to remove the product that has stuck to the palate.)",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "No Force,Barely Any Force,Very Slight Force,Slight Force,Moderate Force,Moderately Strong Force,Strong Force,Extremely Strong Force"
				},
				{
					"title": "Rate of Melt",
					"subtitle": "(Consider rate at which the product melts during chew down)",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Slow rate,Moderate rate,High Rate"
				},
				{
					"title": "Texture of particles felt inside the mouth",
					"select_type": 2,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Grainy,Coarse,Abrasive (pointed particles)"
				},
				{
					"title": "Is the sample sticking to teeth?",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Yes,No"
				}
			]
		},
		{
			"title": "Residual",
			"is_nested_question": 1,
			"is_mandatory": 1,
			"question": [{
					"title": "Do you feel anything left in mouth?",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Yes,No"
				},
				{
					"title": "If yes",
					"select_type": 2,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 0,
					"option": "Oily film,Loose particles,Sticking on tooth,Chalky"
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
			"is_nested_question": 0,
			"is_mandatory": 0
		}
	],

	"OVERALL PREFERENCE": [{
			"title": "To make it more appealing, which attribute/s needs improvement.",
			"subtitle": "Elaborate in the comment box.",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "None,Appearance,Aroma,Taste,Aromatics,Flavor,Texture"
		},
		{
			"title": "Is the product sample acceptable ?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		},
		{
			"title": "Overall Product Experience",
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

        $data = ['name'=>'Salty Snacks POP Corn','keywords'=>"Salty Snacks,POP Corn",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];
        \DB::table('global_questions')->insert($data);
















        $headerInfo2 = [['header_name'=>"INSTRUCTIONS"],

            ['header_name'=>"APPEARANCE","header_info"=>"Visually observe the product, e.g., color, surface appearance , surface texture (without touching and also touch it). 
 Any attribute that stands out as either too good or too bad, may please be highlighted in the comments box at the end of each section."],

            ['header_name'=>"AROMA","header_info"=>"Aroma comes from ingredients used and/or the processes followed like cooking, baking etc. To experience aroma, take a deep breath in and if you don't get any aroma then take short, quick and strong sniffs like how a dog sniffs."],

            ['header_name'=>"TASTE","header_info"=>"Take a bite or multiple bites and assess the taste/s . Anything too good or too bad, please highlight in the comment box at the end of the section. If you find sample to be bland, please mention in the comment box."],

            ['header_name'=>"AROMATICS","header_info"=>"Aromatics is experiencing odour/s inside the mouth, as you eat. Take a bite again, eat normally, keeping your mouth closed and exhale through the nose. Identify the odours using the aroma options. Anything too good or too bad, please highlight in the comment box at the end of the section."],
            ['header_name'=>"FLAVOR","header_info"=>"As a rule of thumb, Flavor is a combination of Taste (25%) and Aromatics (75%) . Congratulations! You just discovered the Flavor of the product that you are tasting."],

            ['header_name'=>"ORAL TEXTURE","header_info"=>"Let us assess all the elements of texture, please follow steps, as outlined below:"],

            ['header_name'=>"OVERALL PREFERENCE","header_info"=>"RATE the overall experience of the product on the preference scale ."],

        ];
        $questions2 = '{

	"INSTRUCTIONS": [{
		"title": "INSTRUCTION",
		"subtitle": "Please follow the questionnaire and click answers that match with your observation/s. Remember, there are no right or wrong answers. In case you observe something that is not covered in the questionnaire, you are most welcome to share your additional inputs in the comments box. Anything that stands out as either too good or too bad, may please be highlighted in the comments box.",
		"select_type": 4
	}],


	"APPEARANCE": [{
			"title": "Visual Observation",
			"is_nested_question": 1,
			"is_mandatory": 1,
			"question": [{
					"title": "Surface Appearance",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Naturally plain,Spice coating"
				},
				{
					"title": "Identify the color and mention it in the comment box.",
					"select_type": 1,
					"is_intensity": 1,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Intensity of identified color",
					"intensity_type": 2,
					"intensity_value": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"title": "About Color",
					"select_type": 2,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Natural,Synthetic,Bright,Dull,Shiny,Glazed,Even,Uneven,Caramelized spots"
				},
				{
					"title": "Coating",
					"select_type": 2,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Spice,Sugar crystals,Chemical,Even,Blotchy (Patchy)"
				},
				{
					"title": "Surface texture (without touch)",
					"select_type": 2,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Moist,Dry,Oily,Sticky,Rough,Smooth,Dehydrated,Baked,Roasted,Fried,Crisp,Limp,Loose particles"
				},
				{
					"title": "Size",
					"select_type": 2,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Even,Uneven,Small,Medium,Large"
				},
				{
					"title": "Shape of the main ingredient",
					"select_type": 2,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Elliptical,Oval,Same Size,Different Size"
				}
			]
		},
		{
			"title": "Surface Texture (with touch)",
			"is_nested_question": 1,
			"is_mandatory": 1,
			"question": [{
					"title": "Brittleness",
					"subtitle": "(Hold the product in your hand and try to break it)",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Yes,No"
				},
				{
					"title": "Any OFF Appearance",
					"subtitle": "(If yes, describe in comment)",
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
			"is_nested_question": 0,
			"is_mandatory": 0
		}
	],

	"AROMA": [{
			"title": "Aroma observed",
			"subtitle": "(We have list of more than 400 aromas, grouped under 11 heads. If you select \"any other\" option please write the identified aroma in the comment box. Use the search box to assess the aroma list.)",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming",
			"is_nested_question": 0,
			"is_mandatory": 1,
			"is_nested_option": 1,
			"nested_option_list": "AROMA"
		},
		{
			"title": "How would you describe the Aroma ?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Pleasant,Inviting,Mouthwatering,Unpleasant,Uninviting,Repelling"
		},
		{
			"title": "Any Off (bad)- aroma, please indicate the intensity",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming",
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
			"is_mandatory": 1,
			"question": [{
					"title": "Sweet",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"title": "Salt",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"title": "Sour",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Neutral,Barely Acidic,Mildly Acidic,Moderately Acidic,Strongly Acidic,Intensely Acidic,Very Intensely Acidic,Extremely Acidic"
				},
				{
					"title": "Bitter",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"title": "Umami",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				}
			]
		},
		{
			"title": "Ayurveda Taste",
			"select_type": 2,
			"is_mandatory": 1,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense",
			"is_nested_question": 0,
			"option": "Astringent (Dryness),Pungent- Masala (Warm Spices),Pungent- Cool Sensation (Cool Spices),Pungent- Chilli"
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
			"title": "Please identify aromatics experienced from the list.",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming",
			"is_nested_question": 0,
			"is_mandatory": 1,
			"is_nested_option": 1,
			"nested_option_list": "AROMA"
		},
		{
			"title": "How would you describe the Aromatics ?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Pleasant,Inviting,Mouthwatering,Unpleasant,Uninviting,Repelling"
		},
		{
			"title": "Identify OFF Taste",
			"subtitle": "(consider aromatics as well)",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 0,
			"is_nested_option": 1,
			"nested_option_list": "OFFAROMA"
		},
		{
			"title": "Aftertaste",
			"subtitle": "Please chew and swallow the product, if possible.",
			"is_nested_question": 1,
			"is_mandatory": 1,
			"question": [{
					"title": "How was the aftertaste?",
					"select_type": 1,
					"is_intensity": 1,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Pleasant,Unpleasant",
					"intensity_type": 2,
					"intensity_value": "None,Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming"
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
					"title": "Any OFF Aftertaste",
					"subtitle": "(If yes, describe in comments)",
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
			"is_nested_question": 0,
			"is_mandatory": 0
		}
	],

	"FLAVOR": [{
			"title": "Are you satisfied with the Flavors?",
			"subtitle": "If you want any change in Flavor like intensity or any other change then mention it in the comment box",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		},
		{
			"title": "What is the Flavor like?",
			"subtitle": "If selected \"Any other\" option mention it in the comment box",
			"Any other ": "option mention it in the comment box ",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yummy,Divine,Delicious,Zesty,Palatable,Appealing,Amazing,Unpalatable,Awful,Any other"
		},
		{
			"title": "Are these Flavors reminding you of any combined flavors that are currently trending",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Wasabi,Sriracha,Smoky Barbeque,Tandoori,Kebab,Jalapeno Cheese,Chipotle,Sour cream and onion,Salsa,Pudina chutney"
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

	"ORAL TEXTURE": [{
			"title": "Please hold the product between your lips, what you feel is called oral texture. How is it?",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Dry,Wet,Oily,Smooth,Rough,Loose particles,Sticky"
		},
		{
			"title": "Please bite into the product and identify the sound/s observed.",
			"subtitle": "Crispy- one sound event- sharp, clean, fast and high pitched,e.g., Potato chips.\nCrunchy - multiple low pitched sounds perceived as a series of small events (Grinding),e.g., Rusks.\nCrackly- bite only once without grinding, it is one sudden low pitched sound event that brittles the product, e.g., cracker biscuits; sugar crystals are crackly too",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense",
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Crispy,Crunchy,Crackly"
		},
		{
			"title": "First Chew",
			"subtitle": "Chew for 3-4 times and pause.",
			"is_nested_question": 1,
			"is_mandatory": 1,
			"question": [{
					"title": "How is the hardness?",
					"subtitle": "Force needed to chew the sample",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "No Force,Barely Any Force,Very Slight Force,Slight Force,Moderate Force,Moderately Strong Force,Strong Force,Extremely Strong Force"
				},
				{
					"title": "Uniformity of bite",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Uniform,Non- Uniform"
				},
				{
					"title": "Burst of Flavor",
					"subtitle": "(Moisture release/ Juiciness)",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Dehydrated,Dry,Juiceless,Slightly Juicy,Juicy,Succulent,Syrupy,Mouthwatering"
				},
				{
					"title": "Denseness",
					"subtitle": "Compactness of the main ingredients",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Dense,Fluffy,Tight,Rubbery"
				},
				{
					"title": "Persistence of sound inside the mouth",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Yes,No"
				}
			]
		},
		{
			"title": "Chew down",
			"subtitle": "Chew the sample 8-10 times until it becomes pulp",
			"is_nested_question": 1,
			"is_mandatory": 1,
			"question": [{
					"title": "Moisture absorption",
					"subtitle": "(Amount of saliva absorbed by the sample)",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "No Saliva Absorbed,Barely Any Saliva Absorbed,Very Slightly Saliva Absorbed,Slightly Saliva Absorbed,Moderately Saliva Absorbed,Plenty Saliva Absorbed,Loads of Saliva Absorbed,Extremely High quantity of Saliva Absorbed"
				},
				{
					"title": "Cohesiveness of sample",
					"subtitle": "After chewing small particles may or may not come together to form a mass",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "No Mass Formed,Partial Mass Formed,Tight Mass Formed"
				},
				{
					"title": "Adhesiveness to the palate",
					"subtitle": "(Force needed to remove the product that has stuck to the palate.)",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "No Force,Barely Any Force,Very Slight Force,Slight Force,Moderate Force,Moderately Strong Force,Strong Force,Extremely Strong Force"
				},
				{
					"title": "Rate of Melt",
					"subtitle": "(Consider rate at which the product melts during chew down)",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Slow rate,Moderate rate,High Rate"
				},
				{
					"title": "Texture of particles felt inside the mouth",
					"select_type": 2,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Grainy,Coarse,Abrasive (pointed particles)"
				},
				{
					"title": "Is the sample sticking to teeth?",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Yes,No"
				}
			]
		},
		{
			"title": "Residual",
			"is_nested_question": 1,
			"is_mandatory": 1,
			"question": [{
					"title": "Do you feel anything left in the mouth ?",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Yes,No"
				},
				{
					"title": "If yes",
					"select_type": 2,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 0,
					"option": "Oily film,Loose particles,Sticking on tooth,Chalky"
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
			"is_nested_question": 0,
			"is_mandatory": 0
		}
	],


	"OVERALL PREFERENCE": [{
			"title": "To make it more appealing, which attribute/s needs improvement.",
			"subtitle": "Elaborate in the comment box.",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "None,Appearance,Aroma,Taste,Aromatics,Flavor,Texture"
		},
		{
			"title": "Is the product sample acceptable ?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		},
		{
			"title": "Overall Product Experience",
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

        $data = ['name'=>'Salty Snacks-Kari Kari','keywords'=>"Salty Snacks,Kari Kari",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];
        \DB::table('global_questions')->insert($data);
















        $headerInfo2 = [['header_name'=>"INSTRUCTIONS"],

            ['header_name'=>"APPEARANCE","header_info"=>"Visually observe the product, e.g., color, surface appearance , surface texture (without touching and also touch it). 
 Any attribute that stands out as either too good or too bad, may please be highlighted in the comments box at the end of each section."],

            ['header_name'=>"AROMA","header_info"=>"Aroma comes from ingredients used and/or the processes followed like cooking, baking etc. To experience aroma, take a deep breath in and if you don't get any aroma then take short, quick and strong sniffs like how a dog sniffs."],

            ['header_name'=>"TASTE","header_info"=>"Take a bite or multiple bites and assess the taste/s . Anything too good or too bad, please highlight in the comment box at the end of the section. If you find sample to be bland, please mention in the comment box."],

            ['header_name'=>"AROMATICS","header_info"=>"Aromatics is experiencing odour/s inside the mouth, as you eat. Take a bite again, eat normally, keeping your mouth closed and exhale through the nose. Identify the odours using the aroma options. Anything too good or too bad, please highlight in the comment box at the end of the section."],
            ['header_name'=>"FLAVOR","header_info"=>"As a rule of thumb, Flavor is a combination of Taste (25%) and Aromatics (75%) . Congratulations! You just discovered the Flavor of the product that you are tasting."],

            ['header_name'=>"ORAL TEXTURE","header_info"=>"Let us assess all the elements of texture, please follow steps, as outlined below:"],

            ['header_name'=>"OVERALL PREFERENCE","header_info"=>"RATE the overall experience of the product on the preference scale ."],

        ];

        $questions2 = '{

	"INSTRUCTIONS": [{
		"title": "Instruction",
		"subtitle": "Please follow the questionnaire and click answers that match with your observation/s. Remember, there are no right or wrong answers. In case you observe something that is not covered in the questionnaire, you are most welcome to share your additional inputs in the comments box.\nAnything that stands out as either too good or too bad, may please be highlighted in the comments box.",
		"select_type": 4
	}],

	"APPEARANCE": [{
			"title": "Visual Observation",
			"is_nested_question": 1,
			"is_mandatory": 1,
			"question": [{
					"title": "Surface Appearance",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Naturally plain,Spice coating"
				},
				{
					"title": "Identify the color and mention it in the comment box.",
					"select_type": 1,
					"is_intensity": 1,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Intensity of identified color",
					"intensity_type": 2,
					"intensity_value": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"title": "About color",
					"select_type": 2,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Natural,Synthetic,Bright,Dull,Shiny,Glazed,Even,Uneven,Caramelized spots"
				},
				{
					"title": "Coating",
					"select_type": 2,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Spice,Sugar crystals,Chemical,Even,Blotchy (Patchy)"
				},
				{
					"title": "Surface texture (without touch)",
					"select_type": 2,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Moist,Dry,Oily,Sticky,Rough,Smooth,Dehydrated,Baked,Roasted,Fried,Crisp,Limp,Loose particles"
				},
				{
					"title": "Size",
					"select_type": 2,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Even,Uneven,Small,Medium,Large"
				},
				{
					"title": "Shape",
					"select_type": 2,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Wavy,Twisted,Plain,Same Size,Different Size"
				}
			]
		},
		{
			"title": "Surface Texture (with touch)",
			"is_nested_question": 1,
			"is_mandatory": 1,
			"question": [{
					"title": "Brittleness",
					"subtitle": "(Hold the product in your hand and try to break it)",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Yes,No"
				},
				{
					"title": "Any OFF Appearance",
					"subtitle": "(If yes, describe in comment)",
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
			"is_nested_question": 0,
			"is_mandatory": 0
		}
	],

	"AROMA": [{
			"title": "Aroma observed",
			"subtitle": "(We have list of more than 400 aromas, grouped under 11 heads. If you select \"any other\" option please write the identified aroma in the comment box. Use the search box to assess the aroma list.)",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming",
			"is_nested_question": 0,
			"is_mandatory": 1,
			"is_nested_option": 1,
			"nested_option_list": "AROMA"
		},
		{
			"title": "How would you describe the Aroma ?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Pleasant,Inviting,Mouthwatering,Unpleasant,Uninviting,Repelling"
		},
		{
			"title": "Any Off (bad)- aroma, please indicate the intensity",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming",
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
			"is_mandatory": 1,
			"question": [{
					"title": "Sweet",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"title": "Salt",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"title": "Sour",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Neutral,Barely Acidic,Mildly Acidic,Moderately Acidic,Strongly Acidic,Intensely Acidic,Very Intensely Acidic,Extremely Acidic"
				},
				{
					"title": "Bitter",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"title": "Umami",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				}
			]
		},
		{
			"title": "Ayurveda Taste",
			"select_type": 2,
			"is_mandatory": 1,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense",
			"is_nested_question": 0,
			"option": "Astringent (Dryness),Pungent- Masala (Warm Spices),Pungent- Cool Sensation (Cool Spices),Pungent- Chilli"
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
			"title": "Please identify aromatics experienced from the list.",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming",
			"is_nested_question": 0,
			"is_mandatory": 1,
			"is_nested_option": 1,
			"nested_option_list": "AROMA"
		},
		{
			"title": "How would you describe the Aromatics ?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Pleasant,Inviting,Mouthwatering,Unpleasant,Uninviting,Repelling"
		},
		{
			"title": "Identify OFF Taste",
			"subtitle": "(consider aromatics as well)",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 0,
			"is_nested_option": 1,
			"nested_option_list": "OFFAROMA"
		},
		{
			"title": "Aftertaste",
			"subtitle": "Please chew and swallow the product, if possible.",
			"is_nested_question": 1,
			"is_mandatory": 1,
			"question": [{
					"title": "How was the aftertaste?",
					"select_type": 1,
					"is_intensity": 1,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Pleasant,Unpleasant",
					"intensity_type": 2,
					"intensity_value": "None,Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming"
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
					"title": "Any OFF Aftertaste",
					"subtitle": "(If yes, describe in comments)",
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
			"is_nested_question": 0,
			"is_mandatory": 0
		}
	],



	"FLAVOR": [{
			"title": "Are you satisfied with the Flavors?",
			"subtitle": "If you want any change in Flavor like intensity or any other change then mention it in the comment box",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		},
		{
			"title": "What is the Flavor like?",
			"subtitle": "If selected \"Any other\" option mention it in the comment box",
			"Any other ": "option mention it in the comment box ",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yummy,Divine,Delicious,Zesty,Palatable,Appealing,Amazing,Unpalatable,Awful,Any other"
		},
		{
			"title": "Are these Flavors reminding you of any combined flavors that are currently trending",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Wasabi,Sriracha,Smoky Barbeque,Tandoori,Kebab,Jalapeno Cheese,Chipotle,Sour cream and onion,Salsa,Pudina chutney"
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

	"ORAL TEXTURE": [{
			"title": "Surface texture",
			"subtitle": "Hold the product between the lips",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Dry,Wet,Oily,Smooth,Rough,Loose particles,Sticky"
		},
		{
			"title": "Please bite into the product and identify the sound/s observed.",
			"subtitle": "Crispy- one sound event- sharp, clean, fast and high pitched,e.g., Potato chips.\nCrunchy - multiple low pitched sounds perceived as a series of small events (Grinding),e.g., Rusks.\nCrackly- bite only once without grinding, it is one sudden low pitched sound event that brittles the product, e.g., cracker biscuits; sugar crystals are crackly too",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense",
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Crispy,Crunchy,Crackly"
		},
		{
			"title": "First Chew",
			"subtitle": "Chew for 3-4 times and pause.",
			"is_nested_question": 1,
			"is_mandatory": 1,
			"question": [{
					"title": "Hardness",
					"subtitle": "Assess the force needed to chew the sample",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "No Force,Barely Any Force,Very Slight Force,Slight Force,Moderate Force,Moderately-Strong Force,Strong Force,Extremely Strong Force"
				},
				{
					"title": "Uniformity of bite",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Uniform,Non- Uniform"
				},
				{
					"title": "Burst of Flavor",
					"subtitle": "(Moisture release/ Juiciness)",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Dehydrated,Dry,Juiceless,Slightly Juicy,Juicy,Succulent,Syrupy,Mouthwatering"
				},
				{
					"title": "Denseness",
					"subtitle": "Compactness of the main ingredients",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Dense,Fluffy,Tight,Rubbery"
				},
				{
					"title": "Persistence of sound inside the mouth",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Yes,No"
				}
			]
		},
		{
			"title": "Chew down",
			"subtitle": "Chew the sample 8-10 times to make a pulp.",
			"is_nested_question": 1,
			"is_mandatory": 1,
			"question": [{
					"title": "Moisture absorption",
					"subtitle": "(Amount of saliva absorbed by the sample)",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "No Saliva Absorbed,Barely Any Saliva Absorbed,Very Slightly Saliva Absorbed,Slightly Saliva Absorbed,Moderately Saliva Absorbed,Plenty Saliva Absorbed,Loads of Saliva Absorbed,Extremely High quantity of Saliva Absorbed"
				},
				{
					"title": "Cohesiveness of sample",
					"subtitle": "After chewing small particles may or may not come together to form a mass",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "No Mass Formed,Partial Mass Formed,Tight Mass Formed"
				},
				{
					"title": "Adhesiveness to the palate",
					"subtitle": "(Force needed to remove the product that has stuck to the palate.)",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "No Force,Barely Any Force,Very Slight Force,Slight Force,Moderate Force,Moderately Strong Force,Strong Force,Extremely Strong Force"
				},
				{
					"title": "Rate of Melt",
					"subtitle": "(Consider rate at which the product melts during chew down)",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Slow rate,Moderate rate,High Rate"
				},
				{
					"title": "Texture of particles felt inside the mouth",
					"select_type": 2,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Grainy,Coarse,Abrasive (pointed particles)"
				},
				{
					"title": "During chew down is the sample sticking to teeth",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Yes,No"
				}
			]
		},
		{
			"title": "Residual",
			"is_nested_question": 1,
			"is_mandatory": 1,
			"question": [{
					"title": "Do you feel anything left in mouth?",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Yes,No"
				},
				{
					"title": "If yes",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 0,
					"option": "Oily film,Loose particles,Tooth pack (Sample stuck between two teeth),Chalky"
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
			"is_nested_question": 0,
			"is_mandatory": 0
		}
	],

	"OVERALL PREFERENCE": [{
			"title": "Is the product sample acceptable?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		},
		{
			"title": "To make it more appealing, which attribute/s needs improvement.",
			"subtitle": "Elaborate in the comment box.",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "None,Appearance,Aroma,Taste,Aromatics,Flavor,Texture"
		},
		{
			"title": "Overall Product Experience",
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

        $data = ['name'=>'Salty Snacks-potato chips','keywords'=>"Salty Snacks, Potato, Chips",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];
        \DB::table('global_questions')->insert($data);


    }
}
