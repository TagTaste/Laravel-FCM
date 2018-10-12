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
        $headerInfo2 = [
            ['header_name' => "INSTRUCTIONS"],
            ['header_name' => "DRY TISANES", "header_info" => "Please take one spoon of dry Tisane and place it on a white surface. Assess the appearance and aroma (odour through the nose) make use of the Aroma list. You might find some ingredients easily identifiable whereas many others might require use of aroma as a clue. Please use our aroma list for ease and convenience."],
            ['header_name' => "LIQUOR", "header_info" => "Please serve Liquor (brewed beverage) in a plain white cup or glass. Please observe the Appearance and Aroma of the Liquor; aroma of the liquor is far more interesting and intriguing than that of dry tisanes. You may experience fruity, muscatel etc. aromas, depending on the nature, type and region of the ingredients."],
            ['header_name' => "TASTE", "header_info" => "Now it is time to Taste and Slurp. Take a deep breath first, pucker your lips, slurp forcefully the liquor up into your mouth from the surface of the spoon. The louder the better, we do this to mix oxygen with the liquor as it helps in bringing the aromas to rise. If you find anything too good or too bad, please highlight in the comment box at the end of the section."],
            ['header_name' => "AROMATICS", "header_info" => "Odour through the mouth. Repeat the slurping with same or better force, close your mouth and exhale through the nose. This is to get aromatics going. Identify the odours sensed using the aroma list and the search option (as done for Aroma earlier). Anything too good or too bad observed, please highlight in the comment box."],
            ['header_name' => "MOUTHFEEL", "header_info" => "In Tisanes we are assessing Mouthfeel and not the oral texture separately. Slurp again swish the liquid inside the mouth and gulp the Tisanes."],
            ['header_name' => "OVERALL PREFERENCE", "header_info" => "Please rate your overall experience on the preference scale. Between the two stages of Tisanes, which one did you like the most and why? Please mention in the comment box."],
        ];
        $questions2 = '{

	"INSTRUCTIONS": [{
		"title": "INSTRUCTIONS",
		"subtitle": "Please follow the questionnaire and click answers that match best with your observations. Remember, there are no right or wrong answers. In case you observe something that is not covered in the questionnaire, you are most welcome to share your additional inputs in the comment box. Anything that stands out as either too good or too bad, may please be highlighted in the comments box.",
		"select_type": 4
	}],

	"DRY TISANES": [{
			"title": "Please observe the product by considering appearance and aroma together.",
			"subtitle": "We have list of more than 400 aromas, grouped under 11 heads. If you select \'Any other\' option, please write the identified aroma in the comment box. Use the search box to access the aroma list. Please select maximum of 4 dominant aromas.",
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
			"title": "How is the texture of the product?",
			"subtitle": "Rub the product between your hands, and feel the texture.",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Dry,Moist"
		},
		{
			"title": "Any OFF Appearance?",
			"subtitle": "If yes, describe in comment box.",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
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

	"LIQUOR": [{
			"title": "Appearance",
			"is_nested_question": 1,
			"question": [{
					"title": "Identify the dominant colors.",
					"subtitle": "If selected \'Any other\' option, please mention the observed color in the comment box.",
					"select_type": 2,
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "None,Barely detectable,Identifiable but not intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense",
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Straw,Golden,Green,Reddish,Brown,Garnet,Dark brown,Any other"
				},
				{
					"title": "Physical appearance",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Bright,Slightly Shiny,Dull"
				},
				{
					"title": "Viscosity",
					"subtitle": "Degree of resistance to flow, evaluated by the rate of flow of liquid when the beverage is poured from a teaspoon.",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Does not leave spoon after tilting,Drops partially or fully but does not fall,Flows but reluctantly,Flows smoothly but slowly,Flows with moderate speed,Flows,Flows freely,Flows very freely"
				},
				{
					"title": "Is it Clear or Cloudy?",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Clear,Cloudy"
				},
				{
					"title": "Any OFF Appearance?",
					"subtitle": "If yes, please highlight details in the comment box",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Yes,No"
				}
			]
		},
		{
			"title": "Aroma",
			"is_nested_question": 1,
			"question": [{
					"title": "Did you experience any aroma?",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Yes,No"
				},
				{
					"title": "How would you describe the Aroma?",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Pleasant,Inviting,Mouthwatering,Unpleasant,Uninviting,Repelling"
				}
			]
		},
		{
			"title": "Aromas observed (Aroma)",
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
			"title": "If you experienced any off (bad) aroma, please indicate the intensity (Aroma)",
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
			"option": "Astringent (Dryness),Pungent masala (Warm spices),Pungent cool sensation (Cool spices),Pungent chilli"
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
			"title": "Did you experience any aromatics?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		},
		{
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
			"title": "Identify OFF Taste (consider aromatics as well).",
			"subtitle": "If you select \'Any other\' option, mention felt Aromatics it in the comment box.",
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
			"title": "Head notes: First impression of flavor that you perceived immediately but did not last long.",
			"subtitle": "If you select \'Any other\' option, please write the identified aroma in the comment box.",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Very Mild,Mild,Distinct mild,Distinct,Distinct strong,Strong,Overwhelming",
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Vegetal,Earthy,Fruity,Floral,Nutty,Sweet,Smoky,Spicy,Any other"
		},
		{
			"title": "Body notes: Powerful and stable aromatics that give the overall impression of the tea.",
			"subtitle": "If you select \'Any other\' option, please write the identified aroma in the comment box.",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Very Mild,Mild,Distinct mild,Distinct,Distinct strong,Strong,Overwhelming",
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Herbs,Wood,Earth,Citrus,Tree fruit,Spicy,Sweet,Nutty,Floral,Any other"
		},
		{
			"title": "Tail notes: Aromatics that linger in the mouth after swallowing the tea (aftertaste).",
			"subtitle": "If you select \'Any other\' option, please write the identified aroma in the comment box.",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Very Mild,Mild,Distinct mild,Distinct,Distinct strong,Strong,Overwhelming",
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Green Pepper,Fennel seed,Rose,Jasmine,Orange,Walnut,Roasted nuts,Honey,Cinnamon,Cardamom,Apricot,Wet earth,Any other"
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

	"MOUTHFEEL": [{
			"title": "How was the Mouthfeel?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "None,Pleasant,Unpleasant"
		},
		{
			"title": "Length of the Mouthfeel?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "None,Short,Sufficient,Long"
		},
		{
			"title": "Describe the Mouthfeel?",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Brisk,Complex,Delicate,Silky,Velvety,Raw,Heavy,Opulent,Biting,Astringent,Short,Frivolous,Fresh,Watery,Warm,Smooth"
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
			"title": "Is the product sample acceptable?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		},
		{
			"title": "To make it more appealing, which attributes need improvement? (Consider only liquor)",
			"subtitle": "Elaborate in the comment box.",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "None,Appearance,Aroma,Taste,Aromatics,Mouthfeel"
		},
		{
			"title": "Keeping the product promise in mind, are you satisfied with the sample?",
			"subtitle": "Mention any suggestions in the comment box.",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		},
		{
			"title": "Full product experience",
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

        $data = ['name'=>'Tea Tisanes','keywords'=>"Tisanes",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];
        \DB::table('global_questions')->insert($data);












        $headerInfo2 = [['header_name'=>"INSTRUCTIONS"],
            ['header_name'=>"DRY LEAVES","header_info"=>"Visually observe the product, e.g., color, surface appearance, surface texture (without touching and also touch it). Any attribute that stands out as either too good or too bad, may please be highlighted in the comments box, at the end of each section."],
            ['header_name'=>"INFUSED LEAVES","header_info"=>"Please refer to the instructions with regards to temperature and time of brewing that you need to follow. Brew the tea at the specified temperature and for the specified time and then strain it. Transfer the infused leaves (wet leaves) on a white plate and answer this section. Tea Graders should comment upon the size of the infused leaves in the comment box. Use the comment box to compare the aromas of Dry and Infused leaves (optional)."],
            ['header_name'=>"LIQUOR","header_info"=>"Please serve Liquor (brewed beverage) in a plain white cup or glass. In this section, please observe the Appearance and Aroma of the Liquor. Aroma of Liquor is far more interesting and intriguing than that of DRY and INFUSED Leaves, you may get fruity, muscatel etc. aromas, depending on the nature, type and region of the tea."],
            ['header_name'=>"TASTE","header_info"=>"How to Taste and Slurp? Take a deep breath first, pucker your lips and slurp forcefully the liquor up into your mouth from the surface of the spoon. The louder the better, you do this to mix oxygen with the Liquor as it helps to bring the aromas to rise. Anything too good or too bad observed, please highlight in the comment box at the end of the section."],
            ['header_name'=>"AROMATICS","header_info"=>"Aromatics is experiencing odour/s inside the mouth, as you drink. Take a sip again, keeping your mouth closed and exhale through the nose. Identify the odours using the aroma options. Anything too good or too bad, please highlight in the comment box at the end of the section."],
            ['header_name'=>"MOUTHFEEL","header_info"=>"Let’s assess the mouth-feel, please slurp again, swish the liquid inside the mouth and gulp the tea."],
            ['header_name'=>"OVERALL PREFERENCE","header_info"=>"Rate the overall experience of the sample on the preference scale . Among the 3 stages of Tea which one did you like the most? Please mention the reason/s as well in the comment box."],
        ];
        $questions2 = ' {

 	"INSTRUCTIONS": [{
 		"title": "INSTRUCTIONS",
 		"subtitle": "Please follow the questionnaire and click answers that match best with your observation/s. Remember, there are no right or wrong answers. In case you observe something that is not covered in the questionnaire, you are most welcome to share your additional inputs in the comment box. Anything that stands out as either too good or too bad, may please be highlighted in the comments box.",
 		"select_type": 4
 	}],

 	"DRY LEAVES": [{
 			"title": "Appearance",
 			"is_nested_question": 1,
 			"question": [{
 					"title": "Please identify the type of tea leaves.",
 					"select_type": 1,
 					"is_intensity": 0,
 					"is_nested_question": 0,
 					"is_mandatory": 1,
 					"option": "CTC (Granular),Orthodox (Leafy),Blend (Mix of orthodox & CTC)"
 				},
 				{
 					"title": "Please identify the color.",
 					"subtitle": "If selected \'Any other\' option, please mention the observed color in the comment box.",
 					"select_type": 2,
 					"is_intensity": 0,
 					"is_nested_question": 0,
 					"is_mandatory": 1,
 					"option": "Green,Pear,Seaweed,Mint,Basil,Parrot color,Olive,Army green,Emerald,Brown,Black,Charcoal,Golden,Sand,Honey"
 				},
 				{
 					"title": "Examine leaves. Are they…?",
 					"select_type": 2,
 					"is_intensity": 0,
 					"is_nested_question": 0,
 					"is_mandatory": 1,
 					"option": "Thin leaves,Thick leave,Long,Short,Broken,Curled leaves,Powder"
 				},
 				{
 					"title": "Surface appearance",
 					"select_type": 2,
 					"is_intensity": 0,
 					"is_nested_question": 0,
 					"is_mandatory": 1,
 					"option": "Bright,Dull,Downy (Hairy)"
 				},
 				{
 					"title": "Presence of dried stem?",
 					"select_type": 1,
 					"is_intensity": 0,
 					"is_nested_question": 0,
 					"is_mandatory": 1,
 					"option": "Yes,No"
 				},
 				{
 					"title": "Presence of leafy buds?",
 					"select_type": 1,
 					"is_intensity": 0,
 					"is_nested_question": 0,
 					"is_mandatory": 1,
 					"option": "Yes,No"
 				},
 				{
 					"title": "Size of leaf?",
 					"select_type": 1,
 					"is_intensity": 0,
 					"is_nested_question": 0,
 					"is_mandatory": 1,
 					"option": "Even,Uneven"
 				},
 				{
 					"title": "How is the texture of the leaf?",
 					"subtitle": "Rub it on your hands and feel the texture.",
 					"select_type": 1,
 					"is_intensity": 0,
 					"is_nested_question": 0,
 					"is_mandatory": 1,
 					"option": "Dry,Moist"
 				},
 				{
 					"title": "Any OFF Appearance?",
 					"subtitle": "If yes, describe in comment box.",
 					"select_type": 1,
 					"is_intensity": 0,
 					"is_nested_question": 0,
 					"is_mandatory": 1,
 					"option": "Yes,No"
 				}
 			]
 		},
 		{
 			"title": "Aroma",
 			"is_nested_question": 1,
 			"question": [{
 					"title": "Did you experience any aroma?",
 					"select_type": 1,
 					"is_intensity": 0,
 					"is_nested_question": 0,
 					"is_mandatory": 1,
 					"option": "Yes,No"
 				},
 				{
 					"title": "How would you describe the Aroma?",
 					"select_type": 1,
 					"is_intensity": 0,
 					"is_nested_question": 0,
 					"is_mandatory": 1,
 					"option": "Pleasant,Inviting,Mouthwatering,Unpleasant,Uninviting,Repelling"
 				}
 			]
 		},
 		{
 			"title": "Aromas observed (Aroma)",
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
 			"title": "If you experienced any off (bad) aroma, please indicate the intensity (Aroma).",
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

 	"INFUSED LEAVES": [{
 			"title": "Appearance",
 			"is_nested_question": 1,
 			"question": [{
 					"title": "Brightness of color",
 					"subtitle": "Dullness indicates short shelf life.",
 					"select_type": 1,
 					"is_intensity": 0,
 					"is_nested_question": 0,
 					"is_mandatory": 1,
 					"option": "None,Barely detectable,Identifiable but not intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
 				},
 				{
 					"title": "Consistency of Color",
 					"subtitle": "Inconsistency indicates a blend",
 					"select_type": 1,
 					"is_intensity": 0,
 					"is_nested_question": 0,
 					"is_mandatory": 1,
 					"option": "Uniform,Non-Uniform"
 				},
 				{
 					"title": "Have the leaves unrolled fully?",
 					"select_type": 1,
 					"is_intensity": 0,
 					"is_nested_question": 0,
 					"is_mandatory": 1,
 					"option": "Yes,No"
 				},
 				{
 					"title": "Any OFF Appearance?",
 					"subtitle": "If yes, describe in comment box.",
 					"select_type": 1,
 					"is_intensity": 0,
 					"is_nested_question": 0,
 					"is_mandatory": 1,
 					"option": "Yes,No"
 				}
 			]
 		},
 		{
 			"title": "Aroma",
 			"is_nested_question": 1,
 			"question": [{
 					"title": "Did you experience any aroma?",
 					"select_type": 1,
 					"is_intensity": 0,
 					"is_nested_question": 0,
 					"is_mandatory": 1,
 					"option": "Yes,No"
 				},
 				{
 					"title": "How would you describe the Aroma?",
 					"select_type": 1,
 					"is_intensity": 0,
 					"is_nested_question": 0,
 					"is_mandatory": 1,
 					"option": "Pleasant,Inviting,Mouthwatering,Unpleasant,Uninviting,Repelling"
 				}
 			]
 		},
 		{
 			"title": "Aromas observed (Aroma)",
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

 	"LIQUOR": [{
 			"title": "Appearance",
 			"is_nested_question": 1,
 			"question": [{
 					"title": "Please identify the color.",
 					"subtitle": "If selected \'Any other\' option, please mention the observed color in the comment box.",
 					"select_type": 2,
 					"is_intensity": 1,
 					"intensity_type": 2,
 					"intensity_value": "None,Barely detectable,Identifiable but not intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense",
 					"is_nested_question": 0,
 					"is_mandatory": 1,
 					"option": "Straw,Golden,Green,Reddish,Brown,Garnet,Dark brown,Any other"
 				},
 				{
 					"title": "Physical appearance",
 					"select_type": 1,
 					"is_intensity": 0,
 					"is_nested_question": 0,
 					"is_mandatory": 1,
 					"option": "Bright,Slightly Shiny,Dull"
 				},
 				{
 					"title": "Viscosity",
 					"subtitle": "Degree of resistance to flow, evaluated by the rate of flow of liquid when the beverage is poured from a teaspoon.",
 					"select_type": 1,
 					"is_intensity": 0,
 					"is_nested_question": 0,
 					"is_mandatory": 1,
 					"option": "Does not leave spoon after tilting,Drops partially or fully but does not fall,Flows but reluctantly,Flows smoothly but slowly,Flows with moderate speed,Flows,Flows freely,Flows very freely"
 				},
 				{
 					"title": "Is it Clear or Cloudy?",
 					"select_type": 1,
 					"is_intensity": 0,
 					"is_nested_question": 0,
 					"is_mandatory": 1,
 					"option": "Clear,Cloudy"
 				},
 				{
 					"title": "Any OFF Appearance?",
 					"subtitle": "If yes, please highlight details in the comment box",
 					"select_type": 1,
 					"is_intensity": 0,
 					"is_nested_question": 0,
 					"is_mandatory": 1,
 					"option": "Yes,No"
 				}
 			]
 		},
 		{
 			"title": "Aroma",
 			"is_nested_question": 1,
 			"question": [{
 					"title": "Did you experience any aroma?",
 					"select_type": 1,
 					"is_intensity": 0,
 					"is_nested_question": 0,
 					"is_mandatory": 1,
 					"option": "Yes,No"
 				},
 				{
 					"title": "How would you describe the Aroma?",
 					"select_type": 1,
 					"is_intensity": 0,
 					"is_nested_question": 0,
 					"is_mandatory": 1,
 					"option": "Pleasant,Inviting,Mouthwatering,Unpleasant,Uninviting,Repelling"
 				}
 			]
 		},
 		{
 			"title": "Aromas observed (Aroma)",
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
 			"option": "Astringent (Dryness),Pungent masala (Warm spices),Pungent cool sensation (Cool spices),Pungent chilli"
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
 			"title": "Did you experience any aromatics?",
 			"select_type": 1,
 			"is_intensity": 0,
 			"is_nested_question": 0,
 			"is_mandatory": 1,
 			"option": "Yes,No"
 		},
 		{
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
 			"subtitle": "If you select \'Any other\' option, mention felt Aromatics it in the comment box.",
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
 			"title": "Head notes: First impression of flavor that you perceived immediately but did not last long.",
 			"subtitle": "If you select \'Any other\' option, please write the identified aroma in the comment box.",
 			"select_type": 2,
 			"is_intensity": 1,
 			"intensity_type": 2,
 			"intensity_value": "None,Very Mild,Mild,Distinct mild,Distinct,Distinct strong,Strong,Overwhelming",
 			"is_nested_question": 0,
 			"is_mandatory": 1,
 			"option": "Vegetal,Earthy,Fruity,Floral,Nutty,Sweet,Smoky,Spicy,Any other"
 		},
 		{
 			"title": "Body notes: Powerful and stable aromatics that give the overall impression of the tea.",
 			"subtitle": "If you select \'Any other\' option, please write the identified aroma in the comment box.",
 			"select_type": 2,
 			"is_intensity": 1,
 			"intensity_type": 2,
 			"intensity_value": "None,Very Mild,Mild,Distinct mild,Distinct,Distinct strong,Strong,Overwhelming",
 			"is_nested_question": 0,
 			"is_mandatory": 1,
 			"option": "Herbs,Wood,Earth,Citrus,Tree fruit,Spicy,Sweet,Nutty,Floral,Any other"
 		},
 		{
 			"title": "Tail notes: Aromatics that linger in the mouth after swallowing the tea (aftertaste).",
 			"subtitle": "If you select \'Any other\' option, please write the identified aroma in the comment box.",
 			"select_type": 2,
 			"is_intensity": 1,
 			"intensity_type": 2,
 			"intensity_value": "None,Very Mild,Mild,Distinct mild,Distinct,Distinct strong,Strong,Overwhelming",
 			"is_nested_question": 0,
 			"is_mandatory": 1,
 			"option": "Green Pepper,Fennel seed,Rose,Jasmine,Orange,Walnut,Roasted nuts,Honey,Cinnamon,Cardamom,Apricot,Wet earth,Any other"
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

 	"MOUTHFEEL": [{
 			"title": "How was the Mouthfeel?",
 			"select_type": 1,
 			"is_intensity": 0,
 			"is_nested_question": 0,
 			"is_mandatory": 1,
 			"option": "None,Pleasant,Unpleasant"
 		},
 		{
 			"title": "Length of the Mouthfeel?",
 			"select_type": 1,
 			"is_intensity": 0,
 			"is_nested_question": 0,
 			"is_mandatory": 1,
 			"option": "None,Short,Sufficient,Long"
 		},
 		{
 			"title": "Describe the Mouthfeel?",
 			"select_type": 2,
 			"is_intensity": 0,
 			"is_nested_question": 0,
 			"is_mandatory": 1,
 			"option": "Brisk,Complex,Delicate,Silky,Velvety,Raw,Heavy,Opulent,Biting,Astringent,Short,Frivolous,Fresh,Watery,Warm,Smooth"
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
 			"title": "Is the product sample acceptable?",
 			"select_type": 1,
 			"is_intensity": 0,
 			"is_nested_question": 0,
 			"is_mandatory": 1,
 			"option": "Yes,No"
 		},
 		{
 			"title": "To make it more appealing, which attributes need improvement? (Consider only liquor)",
 			"subtitle": "Elaborate in the comment box.",
 			"select_type": 2,
 			"is_intensity": 0,
 			"is_nested_question": 0,
 			"is_mandatory": 1,
 			"option": "None,Appearance,Aroma,Taste,Aromatics,Mouthfeel"
 		},
 		{
 			"title": "Keeping the product promise in mind, are you satisfied with the sample?",
 			"subtitle": "Mention any suggestions in the comment box.",
 			"select_type": 1,
 			"is_intensity": 0,
 			"is_nested_question": 0,
 			"is_mandatory": 1,
 			"option": "Yes,No"
 		},
 		{
 			"title": "Full product experience ",
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
        $data = ['name'=>'Tea - Normal','keywords'=>"Tea",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];
        \DB::table('global_questions')->insert($data);











        $headerInfo2 = [['header_name'=>"INSTRUCTIONS"],
            ['header_name'=>"APPEARANCE","header_info"=>"Visually observe the product, e.g., color, surface appearance , surface texture (without touching and also touch it). 
 Any attribute that stands out as either too good or too bad, may please be highlighted in the comments box at the end of the section."],
            ['header_name'=>"AROMA","header_info"=>"We experience Aroma when volatile compounds travel from beans/coffee through the air to our nose. There are too many known aromas in coffee; fresh coffee has a stronger aroma than the stale coffee, the degree of roasting impacts aroma and blending creates a wide range of aromas. To experience aroma, take a deep breath in and if you don't get any aroma then take short, quick and strong sniffs like how a dog sniffs."],
            ['header_name'=>"TASTE","header_info"=>"Take a sip or multiple sips and assess the taste/s. Anything too good or too bad, please highlight in the comment box at the end of the section. If you find sample to be bland, please mention in the comment box."],
            ['header_name'=>"AROMATICS","header_info"=>"Aromatics is experiencing odour/s inside the mouth, as you eat. Take a sip again, keeping your mouth closed and exhale through the nose. Identify the odours using the aroma options. Anything too good or too bad, please highlight in the comment box at the end of the section."],
            ['header_name'=>"FLAVOR","header_info"=>"As a rule of thumb, Flavor is a combination of Taste (25%) and Aromatics (75%). Congratulations! You just discovered the Flavor of the product that you are tasting."],
            ['header_name'=>"ORAL TEXTURE","header_info"=>"Let us assess all the elements of body (texture) of our beverage, please follow steps, as outlined below."],
            ['header_name'=>"OVERALL PREFERENCE","header_info"=>"Rate the overall experience of the product and provide some comments."],
        ];

        $questions2 = '{

	"INSTRUCTIONS": [{
		"title": "INSTRUCTIONS",
		"subtitle": "Please follow the questionnaire and click answers that match with your observation/s. Remember, there are no right or wrong answers. In case you observe something that is not covered in the questionnaire, you are most welcome to share your additional inputs in the comments box. Anything that stands out as either too good or too bad, may please be highlighted in the comments box.",
		"select_type": 4
	}],

	"APPEARANCE": [{
		"title": "Identify the color.",
		"subtitle": "If selected \'Any other\' option, mention the identified color in the comment box.",
		"select_type": 2,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Brown (Coffee bean),Rust (Roasted ground coffee),Black (Brew only coffee),Caramel (Milk coffee),Any other"
	}, {
		"title": "First-sight appearance.",
		"select_type": 1,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Bright,Slightly shiny,Dull"
	}, {
		"title": "Is it Clear or Cloudy?",
		"select_type": 1,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Clear,Cloudy"
	}, {
		"title": "Any off-appearance attribute?",
		"subtitle": "If yes, please highlight in the comment box.",
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
	}, {
		"title": "Comments",
		"select_type": 3,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 0
	}],

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
	}, {
		"title": "How would you describe the Aroma?",
		"select_type": 1,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Pleasant,Inviting,Mouthwatering,Unpleasant,Uninviting,Repelling,Natural,Unnatural"
	}, {
		"title": "If you experienced any off (bad) aroma, please indicate the intensity.",
		"select_type": 2,
		"is_intensity": 1,
		"intensity_type": 2,
		"intensity_value": "None,Very Mild,Mild,Distinct mild,Distinct,Distinct strong,Strong,Overwhelming",
		"is_nested_question": 0,
		"is_mandatory": 0,
		"is_nested_option": 1,
		"nested_option_list": "OFFAROMA"
	}, {
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
	}, {
		"title": "Comments",
		"select_type": 3,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 0
	}],

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
		}, {
			"title": "Salt",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "None,Barely detectable,Identifiable but not intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
		}, {
			"title": "Sour",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Neutral,Barely acidic,Mildly acidic,Moderately acidic,Strongly acidic,Intensely acidic,Very intensely acidic,Extremely acidic"
		}, {
			"title": "Bitter",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "None,Barely detectable,Identifiable but not intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
		}, {
			"title": "Umami",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "None,Barely detectable,Identifiable but not intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
		}]
	}, {
		"title": "Ayurveda Taste",
		"select_type": 2,
		"is_intensity": 1,
		"intensity_type": 2,
		"intensity_value": "None,Barely detectable,Identifiable but not intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense",
		"is_nested_question": 0,
		"is_mandatory": 0,
		"option": "Astringent (Dryness),Pungent masala (Warm spices),Pungent cool sensation (Cool spices),Pungent chilli"
	}, {
		"title": "Intensity of natural sweetness.",
		"subtitle": "Applicable only in absence of added sugar.",
		"select_type": 1,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 0,
		"option": "None,Barely detectable,Identifiable but not intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
	}, {
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
	}, {
		"title": "Comments",
		"select_type": 3,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 0
	}],

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
	}, {
		"title": "How would you describe your aromatics experience?",
		"select_type": 1,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Pleasant,Inviting,Mouthwatering,Unpleasant,Uninviting,Repelling"
	}, {
		"title": "Did your observe any aromatics taints (defect).",
		"subtitle": "Low quality or diseased coffee often taints the aroma.",
		"select_type": 2,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 0,
		"option": "Fermenty (Spoiled fruit),Leathery (Rubber),Grassy (Straw, potato)"
	}, {
		"title": "Did you observe any off-aromatics or off-taste experience?",
		"subtitle": "If you select \'Any other\' option, mention felt Aromatics it in the comment box.",
		"select_type": 2,
		"is_intensity": 1,
		"intensity_type": 2,
		"intensity_value": "None,Very Mild,Mild,Distinct mild,Distinct,Distinct strong,Strong,Overwhelming",
		"is_nested_question": 0,
		"is_mandatory": 0,
		"is_nested_option": 1,
		"nested_option_list": "OFFAROMA"
	}, {
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
		}, {
			"title": "Length of the aftertaste?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "None,Short,Sufficient,Long"
		}, {
			"title": "Any OFF Aftertaste ",
			"subtitle": "(If yes, describe in comments)",
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
	}, {
		"title": "Comments",
		"select_type": 3,
		"is_intensity": 0,
		"is_mandatory": 0,
		"is_nested_question": 0
	}],

	"FLAVOR": [{
		"title": "Are you satisfied with the flavors?",
		"subtitle": "If you want any change in flavor like intensity or any other thing then mention it in comment box.",
		"select_type": 1,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Yes,No"
	}, {
		"title": "What is the flavor like?",
		"subtitle": "If selected \'Any other\', mention observed flavor in the comments box.",
		"select_type": 2,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Bitter,Chocolatey,Earthy,Bright,Mellow,Sharp,Dry,Any other"
	}, {
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
	}, {
		"title": "Comments",
		"select_type": 3,
		"is_intensity": 0,
		"is_mandatory": 0,
		"is_nested_question": 0
	}],

	"ORAL TEXTURE": [{
		"title": "Slurp again and assess the coffee.",
		"subtitle": "Coffee without acids is flat and with acids can be bright with a pop or undesirably sour.",
		"select_type": 1,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Flat,Bright,Tangy,Winey"
	}, {
		"title": "How do you describe the body of the coffee?",
		"subtitle": "Body refers to heaviness of texture of the brewed coffee. If selected \'Any other\' option, explain more in the comment box.",
		"select_type": 1,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Syrup,Whole milk,Water,Any other"
	}, {
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
	}, {
		"title": "Comments",
		"select_type": 3,
		"is_intensity": 0,
		"is_mandatory": 0,
		"is_nested_question": 0
	}],

	"OVERALL PREFERENCE": [{
		"title": "Is this coffee defectless?",
		"subtitle": "Defectless sample means a clean cup.",
		"select_type": 1,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Yes,No"
	}, {
		"title": "Are these 4 elements balanced: Aftertaste, Acidity, Body and Flavor?",
		"select_type": 1,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Yes,No"
	}, {
		"title": "If no, which attribute is lacking?",
		"select_type": 2,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 0,
		"option": "Aftertaste,Flavor,Acidity,Body"
	}, {
		"title": "If no, which attribute is overpowering?",
		"select_type": 2,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 0,
		"option": "Aftertaste,Flavor,Acidity,Body"
	}, {
		"title": "Is the product sample acceptable?",
		"select_type": 1,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Yes,No"
	}, {
		"title": "Overall product experience",
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
	}, {
		"title": "Comments",
		"select_type": 3,
		"is_intensity": 0,
		"is_mandatory": 0,
		"is_nested_question": 0
	}]
}';

        $data = ['name'=>'Coffee','keywords'=>"Coffee",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];
        \DB::table('global_questions')->insert($data);










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
 "INSTRUCTIONS": [
    {
      "title": "INSTRUCTIONS",
      "subtitle": "Please follow the questionnaire and select the answers that are closest to what you sensed during the product tasting. Remember, there are no right or wrong answers. In case you observe something missing in the options kindly write it in the comments box. Any attribute too good or too bad should also be highlighted in the comments box at the end of each section.",
      "select_type": 4
    }
  ],

  "APPEARANCE": [
    {
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
      "subtitle": "(If yes, describe in comment)"
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
      "option": [
        {
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

  "AROMA": [
    {
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
      "option": [
        {
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

  "TASTE": [
    {
      "title": "Basic Taste",
      "is_nested_question": 1,
      "question": [
        {
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
      "option": [
        {
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

  "AROMATICS": [
    {
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
      "question": [
        {
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
      "option": [
        {
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

  "FLAVOR": [
    {
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
      "option": [
        {
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

  "ORAL TEXTURE": [
    {
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
      "option": [
        {
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

  "OVERALL PREFERENCE": [
    {
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
      "option": [
        {
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
