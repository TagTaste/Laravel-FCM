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
            ['header_name'=>"APPEARANCE","header_info"=>"Observe the visual aspect of the product like its color, surface appearance, surface texture (without touch & with touch). If you observe anything too good or too bad, please highlight in the comments box at the end of the section."],
            ['header_name'=>"AROMA","header_info"=>"Aroma coming from samples can be traced through ingredients and processes that they have undergone like baking and cooking. Smell through nose. Take a deep breath. If you don't get any aroma then take short, quick and strong sniffs."],
            ['header_name'=>"TASTE","header_info"=>"Take a bite, eat normally and assess the taste as mentioned in the section. If you observe anything too good or too bad, please highlight in the comments box at the end of the section."],
            ['header_name'=>"AROMATICS","header_info"=>"Odour through mouth. Take a bite again eat normally keeping your mouth close and exhale through nose. Identify the odours sensed using the aroma list and the search option. If you observe anything too good or too bad, please highlight in the comments box at the end of the section."],
            ['header_name'=>"FLAVOUR","header_info"=>"Flavour is 25% Taste and 75% Aromatics in healthy individuals. Now that you have tasted the sample and experienced the aromatics of it, evaluate the flavour."],
            ['header_name'=>"ORAL TEXTURE","header_info"=>"Take a bite again but proceed only as per the instructions mentioned in the sub section."],
            ['header_name'=>"OVERALL PREFERENCE","header_info"=>"Rate the overall experience of the product on the preference scale and write about balance or imbalance from the 6 main attributes (Appearance, Aroma, Taste, Aromatics, Flavour, and Texture) in the comments box."],
        ];
        $questions2 = '{
	"INSTRUCTIONS": [{
		"title": "INSTRUCTIONS",
		"subtitle": "Please follow the questionnaire and select the answers that are closest to what you sensed during the product tasting. Remember, there are no right or wrong answers. Sample Size: 100 ml per taster. Container Required: White paper cup or transparent plastic cup and strong teaspoon. Incase you observe something missing in the options kindly write it in the comments box. Any attribute too good or too bad should also be highlighted in the comments box at the end of each section.",
		"select_type": 4
	}],

	"APPEARANCE": [{
		"title": "Visual Observation (Without Touch)",
		"is_nested_question": 1,
		"question": [{
			"title": "Identify the color",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Red,Crimson,Rose,Ruby,Wine,Jam,Cherry,Apple,Strawberry,Blood,Brick,Vermilion (Sindoor)"
		}, {
			"title": "Surface appearance",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Bright,Dull,Shiny,Glaze"
		}, {
			"title": "Surface texture (without touch) ",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Smooth,Silky,Seeds,Seed awareness,Skin,Skin awareness,Fibre,Fibre awareness,Small pieces"
		}]
	}, {
		"title": "Surface Texture (With Touch)",
		"is_nested_question": 1,
		"question": [{
			"title": "Thickness",
			"subtitle": "Measure of Resistance of semi solid product when stirred with a spoon",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "No resistance,Barely any resistance,Identifiable but very low resistance,Slightly more resistance,Moderate resistance,High resistance,Very high resistance,Extremely high resistance"
		}, {
			"title": "Viscosity",
			"subtitle": "Degree of Resistance to flow. Evaluate by the rate of flow of semi solid or liquid when sample is poured from a teaspoon.",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Does not leave spoon after tilting,Drops partially or fully but does not fall,Flows but reluctantly,Flows smoothly but slowly,Flows with moderate speed,Flows,Flows freely,Flows very freely"
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

	"AROMA": [{
		"title": "Aromas observed",
		"subtitle": "We have list of around 400 aromas grouped under 11 heads. If you select \'any other\' option, please write the aroma in the comment box. Use search box to locate the selected aroma from the list.",
		"select_type": 2,
		"is_intensity": 1,
		"intensity_type": 2,
		"intensity_value": "Weak,Sufficient,Strong,Overwhelming",
		"is_nested_question": 0,
		"is_mandatory": 1,
		"is_nested_option": 1,
		"nested_option_list": "AROMA"
	}, {
		"title": "What is aroma like?",
		"select_type": 2,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Pleasant,Inviting,Mouthwatering,Unpleasant,Uninviting,Repelling"
	}, {
		"title": "Any off aroma observed?",
		"subtitle": "Bad aromas that render the food unfit for consumption.",
		"select_type": 2,
		"is_intensity": 1,
		"intensity_type": 2,
		"intensity_value": "Weak,Sufficient,Strong,Overwhelming",
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
		"option": "Don\'t like,Can\'t say,Somewhat like,Clearly like,Love it"
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
		"subtitle": "Additional tastes as per Ayurveda",
		"is_nested_question": 1,
		"question": [{
			"title": "Astringent (Dryness)",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "None,Barely detectable,Identifiable but not intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
		}, {
			"title": "Pungent - Masala (Warm spices)",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "None,Barely detectable,Identifiable but not intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
		}, {
			"title": "Pungent - Cool sensation (Cool spices)",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "None,Barely detectable,Identifiable but not intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
		}, {
			"title": "Pungent - Chilli",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "None,Barely detectable,Identifiable but not intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
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
		"subtitle": "We have list of around 400 aromatics grouped under 11 heads. If you select \'any other\' option, please write the aroma in the comment box. Use search box to locate the selected aroma from the list.",
		"select_type": 2,
		"is_intensity": 1,
		"intensity_type": 2,
        "intensity_value": "Weak,Sufficient,Strong,Overwhelming",
		"is_nested_question": 0,
		"is_mandatory": 1,
		"is_nested_option": 1,
		"nested_option_list": "AROMA"
	}, {
		"title": "What is aromatics like?",
		"select_type": 2,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Pleasant,Inviting,Mouthwatering,Unpleasant,Uninviting,Repelling"
	}, {
		"title": "Any Off Taste (consider aromatics as well) observed?",
		"subtitle": "Bad aromas that render the food unfit for consumption.",
		"select_type": 2,
        "is_intensity": 1,
		"intensity_type": 2,
		"intensity_value": "Weak,Sufficient,Strong,Overwhelming",
		"is_nested_question": 0,
		"is_mandatory": 0,
		"is_nested_option": 1,
		"nested_option_list": "OFFAROMA"
	}, {
		"title": "Aftertaste",
		"subtitle": "Swallow the sample and assess the sensation on your tongue, inside your mouth etc.",
		"is_nested_question": 1,
		"question": [{
			"title": "How was the aftertaste?",
			"select_type": 1,
			"is_intensity": 1,
			"intensity_type": 2,
            "intensity_value": "Weak,Sufficient,Strong,Overwhelming",
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "None,Pleasant,Unpleasant"
		}, {
			"title": "Length of the aftertaste?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "None,Short,Sufficient,Long"
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

	"FLAVOUR": [{
		"title": "What is the flavour like?",
		"subtitle": "If selected \'Any other\', mention observed flavour in the comments box.",
		"select_type": 2,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Tasty,Fruity,Delicious,Spicy,Palatable,Appealing,Amazing,Tasteless,Awful,Any other"
	}, {
		"title": "Are you satisfied with the flavours?",
		"subtitle": "If you want any change in flavour like intensity or any other thing then mention it in comment box.",
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
		"is_mandatory": 0,
		"is_nested_question": 0
	}],

	"ORAL TEXTURE": [{
		"title": "First Compression",
		"subtitle": "Place half teaspoon of sample on the tongue, move your tongue up and compress the sample between the tongue and palate. Please don\'t swallow the sample.",
		"is_nested_question": 1,
		"question": [{
			"title": "Slipperiness",
			"subtitle": "Assess the slide of the tongue over the sample. Express the degree of slipperiness from the options; where it starts with drags and ends with slips off easily like water.",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "None,Barely drags,Drags,Slightly slips,Moderately slips,Slips,Very slippery,Extremely slippery"
		}, {
			"title": "Firmness",
			"subtitle": "Force required to compress the sample.",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Extremely soft,Very much soft,Moderately soft,Slightly soft (like jelly),Slightly firm (like cake),Moderately firm,Very firm,Extremely  firm"
		}, {
			"title": "Cohesiveness",
			"subtitle": "Shear-breaking off due to structural strain.",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Extremely shear,Very much shear,Moderately shear,Slightly shear,Slightly deform,Moderately deform,Very much deform,Extremely deform"
		}]
	}, {
		"title": "Manipulation",
		"subtitle": "Compress sample multiple times pause and assess.",
		"is_nested_question": 1,
		"question": [{
			"title": "Moisture absorption",
			"subtitle": "Amount of saliva absorbed by the sample.",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "None,Barely,Very slight,Slight,Moderate,Plenty,High,Extremely high"
		}, {
			"title": "Particle",
			"subtitle": "Mealiness: Softer Graininess, fine round and smooth particles of small size. It is a geometric attribute within the product which are evenly distributed.",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Seed feeling,Fibre feeling,Skin feeling,Mealiness (granular) feeling"
		}]
	}, {
		"title": "Residual (Afterfeel)",
		"subtitle": "After swallowing what is the feeling inside the mouth.",
		"is_nested_question": 1,
		"question": [{
			"title": "Did you feel anything left in mouth?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		}, {
			"title": "If residual was left, did you get...?",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 0,
			"option": "Oily film,Loose particles,Sticking on tooth,Chalky"
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
		"title": "Are all attributes in balance?",
		"select_type": 1,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Yes,No"
	}, {
		"title": "If not, which one of these is out of balance?",
		"select_type": 2,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 0,
		"option": "Appearance,Aroma,Taste,Aromatics,Flavour,Texture"
	}, {
		"title": "Is the product sample acceptable?",
		"select_type": 1,
		"is_intensity": 0,
		"is_nested_question": 0,
		"is_mandatory": 1,
		"option": "Yes,No"
	}, {
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
        $data = ['name'=>'Raw Tomato and Red Chili','keywords'=>"tomato,red chili",'description'=>'Bunfills',
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];

        \DB::table('global_questions')->insert($data);
    }
}
