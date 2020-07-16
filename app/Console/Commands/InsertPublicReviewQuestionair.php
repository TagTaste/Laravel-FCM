<?php

namespace App\Console\Commands;

use App\PublicReviewProduct\Questions;
use Illuminate\Console\Command;

class InsertPublicReviewQuestionair extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'public:review:globalquestion:insert';

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



 ['header_name'=>"INSTRUCTIONS",'header_selection_type'=>"0"],

 ['header_name' => "Your Food Shot",'header_selection_type' => "3"],

 ['header_name' => "AROMA","header_info" => ["text" => "At this stage, we are assessing only aromas (odors) through the nose, so please don't take a bite yet. Now bring the product closer to your nose and take a deep breath; you may also take 3-4 short, quick and strong sniffs. Aromas arising from the product can be traced to the ingredients and the processes (like baking, cooking, fermentation etc) which the product might have undergone."],'header_selection_type'=>"1"],

 ['header_name' => "TASTE","header_info" => ["text" => "Eat normally and assess the tastes.\n\nAll the tastes except Umami are self-explanatory. Umami taste is felt when you get a continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth; has a long- lasting aftertaste and some people crave for more."],'header_selection_type'=>"1"],

  ['header_name' => "TEXTURE","header_info" => ["text" => "Let's experience the Texture (Feel) now. ‘Feel’ starts when the product comes in contact with the mouth and the ‘Feel’ may even last after the product has been swallowed. Texture (Feel) is all about the joy we get from what we eat."],'header_selection_type'=>"1"],

['header_name'=>"PRODUCT EXPERIENCE","header_info"=> ["text" => "Consider all the attributes - Appearance, Aroma, Taste, Aromatics to flavor and Texture of food and beverage pair; rate the overall experience of the product on all the parameters taken together."],'header_selection_type'=>"2"]

];
        $questions2 = '{

    "INSTRUCTIONS": [{
        "title": "Instruction",
        "subtitle": "<b>Welcome to the Product Review!</b>\n\nTo review, follow the questionnaire and select the answers that match your observations.\n\nPlease click (i) on every screen / page for guidance related to questions.\n\nAny attribute that stands out as either too good or too bad, may please be highlighted in the <b>comment box</b> at the end of each section.\n\nRemember, there are no right or wrong answers.",
        "select_type": 4,
        "question_info": {
          "images": ["https://s3.ap-south-1.amazonaws.com/static4.tagtaste.com/Public.png"],
           "video_link": "https://www.youtube.com/watch?v=TGOdxQhgi5Y"
        }
        
        
    }],
  	"Your Food Shot": [



		{



			"title": "<b>Take</b> a selfie with the product",



			"subtitle": "Reviews look more authentic when you post them with a photograph.",

      "placeholder_image": "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/shelfi+with+product.png",


			"select_type": 6



		}



	],
	"AROMA": [{
            "title": "What all aromas have you sensed?",
            "subtitle": "Directly use the search box to select the aromas that you have identified or follow the category based aroma list. In case you can\'t find the identified aromas, select \"Any other\" and if unable to sense any aroma at all, then select \"Absent\".",
            
            "select_type": 2,
            "is_intensity": 1,
            "intensity_type": 2,
            "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense",
            "is_nested_question": 0,
            "is_mandatory": 1,
            "is_nested_option": 1,
            "nested_option_list": "AROMA",
            "nested_option_title": "AROMAS"
        },
        {
            "title": "Overall Preference of Aroma",
            "select_type": 5,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "Dislike Extremely",
                    "color_code": "#8C0008"
                },
                {
                    "value": "Dislike Moderately",
                    "color_code": "#C92E41"
                },
                {
                    "value": "Dislike Slightly",
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
                    "value": "Like Extremely",
                    "color_code": "#305D03"
                }
            ]
        }
    ],
    
    "TASTE": [{
            "title": "Which Basic tastes have you sensed?",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 2,
            "track_consistency":1,
            "option": [{
                    "value": "Sweet",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "track_consistency":1,
                    "intensity_consistency":"weak",
                    "benchmark_intensity":4.0,
                    "benchmark_score":50,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Salt",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Sour",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Acidic,Weakly Acidic,Mildly Acidic,Moderately Acidic,Intensely Acidic,Very Intensely Acidic,Extremely Acidic"
                },
                {
                    "value": "Bitter",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Umami",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "No Basic Taste",
                    "option_type": 2,
                    "is_intensity": 0
                }
            ]
        },
        {
            "title": "Which Ayurvedic tastes have you sensed?",
            "select_type": 2,
            "is_intensity": 0,
            "is_mandatory": 1,
            "is_nested_question": 0,
            "is_nested_option": 0,
            "option": [{
                    "value": "Astringent (Puckery - Raw Banana)",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Pungent (Spices / Garlic)",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate, Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Pungent Cool Sensation (Mint)",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Pungent Chilli",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense, Very Intense, Extremely Intense"
                },
                {
                    "value": "No Ayurvedic Taste",
                    "is_intensity": 0,
                    "option_type": 2
                    
                }
            ]
        },
        
        {
            "title": "Overall Preference of Taste",
            "select_type": 5,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "Dislike Extremely",
                    "color_code": "#8C0008"
                },
                {
                    "value": "Dislike Moderately",
                    "color_code": "#C92E41"
                },
                {
                    "value": "Dislike Slightly",
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
                    "value": "Like Extremely",
                    "color_code": "#305D03"
                }
            ]
        }
    ],
    "TEXTURE": [{
            "title": "While eating, how does the product feel inside the mouth?",
            "subtitle": "Please eat one teaspoon of the product and assess.",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_mandatory": 1,
            "select_type": 1,
            "option": [{
                    "value": "Like water",
                    "is_intensity": 0
                },
                {
                    "value": "Like toned milk",
                    "is_intensity": 0
                },
                {
                    "value": "Like full cream milk",
                    "is_intensity": 0
                },
                {
                    "value": "Like honey",
                    "is_intensity": 0
                },
                {
                    "value": "Like condensed milk",
                    "is_intensity": 0
                },
                {
                    "value": "Like puree (tomato)",
                    "is_intensity": 0
                },
                {
                    "value": "Like paste (peanut butter)",
                    "is_intensity": 0
                }
            ]
        },
        {
            "title": "While eating, which textures can you experience inside your mouth?",
            "subtitle": "Please select a maximum of 3 options.",
            "select_type": 2,
            "is_intensity": 0,
            "is_mandatory": 1,
            "is_nested_question": 0,
            "is_nested_option": 0,
            "option": [{
                    "value": "Smooth",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Pasty",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate, Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Sticky",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Grainy",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Gritty",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate, Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Chunky",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Seeds",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Fibre",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate, Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Skin",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Rubbery",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate, Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Lumpy",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Any other",
                    "is_intensity": 1,
                     "option_type": 1,
                     "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

                }
                
            ]
        },
      {
            "title": "Do you feel any mouth coating inside your mouth, and to what extent?",
            "subtitle": "Eat normally and assess.",
            "select_type": 1,
            "is_intensity": 0,
            "is_mandatory": 1,
            "is_nested_question": 0,
            "is_nested_option": 0,
            "option": [{
                    "value": "Yes",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "No",
                    "is_intensity": 0

                }
                
            ]
        },
        {
            "title": "After swallowing the product, do you feel anything left inside the mouth?",
            "select_type": 2,
            "is_intensity": 0,
            "is_mandatory": 1,
            "is_nested_question": 0,
            "is_nested_option": 0,
            "option": [{
                    "value": "Oily film",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Loose particles",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate, Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Sticking on tooth / palate",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Stuck between teeth",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Chalky",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate, Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "No residue",
                    "is_intensity": 0,
                     "option_type": 2
                     

                },
                {
                    "value": "Any other",
                    "is_intensity": 1,
                     "option_type": 1,
                     "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

                }
                
            ]
        },
        
        {
            "title": "Overall Preference of Texture",
            "select_type": 5,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "Dislike Extremely",
                    "color_code": "#8C0008"
                },
                {
                    "value": "Dislike Moderately",
                    "color_code": "#C92E41"
                },
                {
                    "value": "Dislike Slightly",
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
                    "value": "Like Extremely",
                    "color_code": "#305D03"
                }
            ]
        }
        
    ],
    "PRODUCT EXPERIENCE": [
      {
            "title": "With this sauce which food would you like to eat?",
            "select_type": 2,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "Fried snacks",
                    "is_intensity": 0
                },
                {
                    "value": "Tossed vegetables",
                    "is_intensity": 0
                },
                {
                    "value": "Bread",
                    "is_intensity": 0
                },
                {
                    "value": "Rice",
                    "is_intensity": 0
                },
                {
                    "value": "Noodles",
                    "is_intensity": 0
                },
                {
                    "value": "Grilled meats",
                    "is_intensity": 0
                },
                {
                    "value": "Tossed chicken",
                    "is_intensity": 0
                },
                {
                    "value": "All of them",
                    "option_type": 2,
                    "is_intensity": 0
                },
                {
                    "value": "Any other",
                    
                     "option_type": 1,

                    "is_intensity": 0
                     

                }
            ]
        },
        {
            "title": "Did this product succeed in satisfying your basic senses?",
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "Yes",
                    "is_intensity": 0
                },
                {
                    "value": "No",
                    "is_intensity": 0
                }
            ]
        },
        {
            "title": "Which attributes can be improved further?",
            "select_type": 2,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "Appearance",
                    "is_intensity": 0
                },
                {
                    "value": "Aroma",
                    "is_intensity": 0
                },
                {
                    "value": "Taste",
                    "is_intensity": 0
                },
                {
                    "value": "Aromatics to flavor",
                    "is_intensity": 0
                },
                {
                    "value": "Texture",
                    "is_intensity": 0
                },
                {
                    "value": "Balanced product",
                     "option_type": 2,
                    "is_intensity": 0
                }
            ]
        },
        {
            "title": "Overall Product Preference",
            "select_type": 5,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "Dislike Extremely",
                    "color_code": "#8C0008"
                },
                {
                    "value": "Dislike Moderately",
                    "color_code": "#C92E41"
                },
                {
                    "value": "Dislike Slightly",
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
                    "value": "Like Extremely",
                    "color_code": "#305D03"
                }
            ]
        },
        {
            "title": "Comments",
            "placeholder": "Share feedback in your own words…",
            "select_type": 3,
            "is_intensity": 0,
            "is_mandatory": 1,
            "is_nested_question": 0
        }
    ]
}';

        $data = ['name'=>'DeletedHeaderPublicReview1','keywords'=>"DeletedHeaderPublicReview1",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];

        \DB::table('public_review_global_questions')->insert($data);

        $globalQuestion = \DB::table('public_review_global_questions')->orderBy('id', 'desc')->first();

        $headerData = [];
        // header_selection_type
        // for instruction = 0  , overall preferance = 2 others = 1
        foreach ($headerInfo2 as $item)
        {
            $headerData[] = ['header_type'=>$item['header_name'],'is_active'=>1,'header_selection_type'=>$item['header_selection_type'],
                'global_question_id'=>$globalQuestion->id,'header_info'=>isset($item['header_info']) ? json_encode($item['header_info']) : null];
        }
        \Log::info($headerData);
        \DB::table('public_review_question_headers')->insert($headerData);

        $questions = $questions2;
        $questions = json_decode($questions,true);

        foreach ($questions as $key=>$question)
        {
            $data = [];
            $header = \DB::table('public_review_question_headers')->select('id')->where('header_type','like',$key)
                ->where('global_question_id',$globalQuestion->id)->first();
            $headerId = $header->id;
            \Log::info("header id ".$headerId);
            foreach ($question as $item)
            {
                $subtitle = isset($item['subtitle']) ? $item['subtitle'] : null;
                $subquestions = isset($item['question']) ? $item['question'] : [];
                $isNested = isset($item['is_nested_question']) && $item['is_nested_question'] == 1 ? 1 : 0;
                $isMandatory = isset($item['is_mandatory']) && $item['is_mandatory'] == 1 ? 1 : 0;
				$option = isset($item['option']) ? $item['option'] : null;
				
                if(isset($item['select_type']) && !is_null($option))
                {
                    $value = $item['option'];
                    if(is_string($value))
                    {
                        $value = explode(',',$option);
                        $option = [];
                        $i = 1;
                        foreach($value as $v){
                            if(is_null($v) || empty($v))
                                continue;
                            if($v == 'Any other' || $v == 'any other')
                                $option_type = 1;
                            else if($v == 'none' || $v == 'None')
                                $option_type = 2;
                            else
                                $option_type = 0;
                            $option[] = [
                                'id' => $i,
                                'value' => $v,
                                'option_type' => $option_type
                            ];
                            $i++;
                        }
                    }
                    else
                    {
                        $option = [];
                        $i = 1;
                        foreach($value as $v){
                            if(!isset($v['value']))
                            {
                                continue;
                            }
                            $option[] = [
                                'id' => $i,
                                'value' => $v['value'],
                                'colorCode'=> isset($v['color_code']) ? $v['color_code'] : null,
                                'is_intensity'=>isset($v['is_intensity']) ? $v['is_intensity'] : null,
                                'intensity_type'=>isset($v['intensity_type']) ? $v['intensity_type'] : null,
                                'intensity_value'=>isset($v['intensity_value']) ? $v['intensity_value'] : null,
								'option_type'=>isset($v['option_type']) ? $v['option_type'] : 0,
								'image_url'=>isset($v['image_url']) ? $v['image_url'] : null
                            ];
                            $i++;
                        }
                    }
                }
                else
                {
                    $value = explode(',',$option);
                    $option = [];
                    $i = 1;
                    foreach($value as $v){
                        if(is_null($v) || empty($v))
                            continue;
                        if($v == 'Any other' || $v == 'any other')
                            $option_type = 1;
                        else if($v == 'none' || $v == 'None')
                            $option_type = 2;
                        else
                            $option_type = 0;
                        $option[] = [
                            'id' => $i,
                            'value' => $v,
                            'option_type'=>$option_type
                        ];
                        $i++;
                    }
                }
                if(count($option))
                    $item['option'] = $option;
				unset($item['question']);
                $data = ['title'=>$item['title'],'subtitle'=>$subtitle,'is_nested_question'=>$isNested,
                    'questions'=>json_encode($item,true),'parent_question_id'=>null,
                    'header_id'=>$headerId,'is_mandatory'=>$isMandatory,'is_active','global_question_id'=>$globalQuestion->id];
                \Log::info("question ");
                \Log::info($data);
                $x = Questions::create($data);

                $nestedOption = json_decode($x->questions);
                $extraQuestion = [];
                if(isset($nestedOption->is_nested_option))
                {
                    if($nestedOption->is_nested_option)
                    {

                        if(isset($nestedOption->nested_option_list))
                        {
                            echo $nestedOption->nested_option_list;
                            $extra = \Db::table('public_review_global_nested_option')->where('is_active',1)->where('type','like',$nestedOption->nested_option_list)->get();
                            foreach ($extra as $nested)
                            {
                                $parentId = $nested->parent_id == 0 ? null : $nested->parent_id;
                                $description = isset($nested->description) ? $nested->description : null;
                                $option_type = isset($nested->option_type) ? $nested->option_type : 0;
                                $extraQuestion[] = ["sequence_id"=>$nested->s_no,'parent_id'=>$parentId,'value'=>$nested->value,'question_id'=>$x->id,
                                    'is_active'=>1, 'global_question_id'=>$globalQuestion->id,'header_id'=>$headerId,'description'=>$description,'is_intensity'=>$nested->is_intensity, 'option_type'=>$option_type];
                            }
                        }
                        else if(isset($nestedOption->nested_option_array))
                        {
                            $extra = $nestedOption->nested_option_array;
                            foreach ($extra as $nested)
                            {
                                $parentId = $nested->parent_id == 0 ? null : $nested->parent_id;
                                $description = isset($nested->description) ? $nested->description : null;
                                $extraQuestion[] = ["sequence_id"=>$nested->s_no,'parent_id'=>$parentId,'value'=>$nested->value,'question_id'=>$x->id,
                                    'is_active'=>$nested->is_active, 'global_question_id'=>$globalQuestion->id,'header_id'=>$headerId,
                                    'description'=>$description,'is_intensity'=>$nested->is_intensity,'option_type'=>0];
                            }
                        }
                        else
                        {
                            echo "something wrong in nested option value";
                            return 0;
                        }
                        print_r($extraQuestion);
                        \DB::table('public_review_nested_options')->insert($extraQuestion);


                        $paths = \DB::table('public_review_nested_options')->where('question_id',$x->id)->where('global_question_id',$globalQuestion->id)
                            ->whereNull('parent_id')->get();

                        foreach ($paths as $path)
                        {
                            \DB::table('public_review_nested_options')->where('question_id',$x->id)->where('global_question_id',$globalQuestion->id)
                                ->where('id',$path->id)->update(['path'=>$path->value,'parent_sequence_id'=>$path->sequence_id]);
                        }
                        $questions = \DB::table('public_review_nested_options')->where('question_id',$x->id)->where('global_question_id',
                            $globalQuestion->id)->get();

                        foreach ($questions as $question)
                        {
                            $checknestedIds = \DB::table('public_review_nested_options')->where('question_id',$x->id)
                                ->where('global_question_id',$globalQuestion->id)
                                ->where('parent_id',$question->sequence_id)->get()->pluck('id');

                            if(count($checknestedIds))
                            {
                                $pathname =  \DB::table('public_review_nested_options')->where('question_id',$x->id)
                                    ->where('global_question_id',$globalQuestion->id)
                                    ->where('sequence_id',$question->sequence_id)->first();
                                \DB::table('public_review_nested_options')->where('question_id',$x->id)->where('global_question_id',$globalQuestion->id)
                                    ->whereIn('id',$checknestedIds)->update(['path'=>$pathname->path,'parent_sequence_id'=>$pathname->parent_sequence_id]);
                                \DB::table('public_review_nested_options')->where('question_id',$x->id)->where('global_question_id',$globalQuestion->id)
                                    ->where('id',$question->id)->update(['is_nested_option'=>1]);
                            }

                        }
                        $paths = \DB::table('public_review_nested_options')->where('question_id',$x->id)
                            ->where('global_question_id',$globalQuestion->id)->whereNull('parent_id')->get();

                        foreach ($paths as $path)
                        {
                            \DB::table('public_review_nested_options')->where('question_id',$x->id)->where('global_question_id',$globalQuestion->id)
                                ->where('id',$path->id)->update(['path'=>null]);
                        }
                    }
                }

                foreach ($subquestions as $subquestion)
                {
                    $subtitle = isset($subquestion['subtitle']) ? $subquestion['subtitle'] : null;
                    $isNested = isset($subquestion['is_nested_question']) && $subquestion['is_nested_question'] == 1 ? 1 : 0;
                    $isMandatory = isset($subquestion['is_mandatory']) && $subquestion['is_mandatory'] == 1 ? 1 : 0;
                    // for sub questions
                    $option = isset($subquestion['option']) ? $subquestion['option'] : null;
                    if(isset($subquestion['select_type']) && !is_null($option))
                    {
                        $value = $subquestion['option'];
                        if(is_string($value))
                        {
                            $value = explode(',',$option);
                            $option = [];
                            $i = 1;
                            foreach($value as $v){
                                if(is_null($v) || empty($v))
                                    continue;
                                if($v == 'Any other' || $v == 'any other')
                                    $option_type = 1;
                                else if($v == 'none' || $v == 'None')
                                    $option_type = 2;
                                else
                                    $option_type = 0;
                                $option[] = [
                                    'id' => $i,
                                    'value' => $v,
                                    'option_type'=>$option_type,
									'image_url'=>isset($v['image_url']) ? $v['image_url'] : null
                                ];
                                $i++;
                            }
                        }
                        else
                        {
                            $option = [];
                            $i = 1;
                            foreach($value as $v){
                                if(!isset($v['value']))
                                {
                                    continue;
                                }
                                $option[] = [
                                    'id' => $i,
                                    'value' => $v['value'],
                                    'colorCode'=> isset($v['color_code']) ? $v['color_code'] : null,
                                    'is_intensity'=>isset($v['is_intensity']) ? $v['is_intensity'] : null,
                                    'intensity_type'=>isset($v['intensity_type']) ? $v['intensity_type'] : null,
                                    'intensity_value'=>isset($v['intensity_value']) ? $v['intensity_value'] : null,
                                    'option_type'=>isset($v['option_type']) ? $v['option_type'] : 0,
									'image_url'=>isset($v['image_url']) ? $v['image_url'] : null
                                ];
                                $i++;
                            }
                        }
                    }
                    else {
                        $value = explode(',', $option);
                        $option = [];
                        $i = 1;
                        foreach ($value as $v) {
                            if (is_null($v) || empty($v))
                                continue;
                            if($v == 'Any other' || $v == 'any other')
                                $option_type = 1;
                            else if($v == 'none' || $v == 'None')
                                $option_type = 2;
                            else
                                $option_type = 0;
                            $option[] = [
                                'id' => $i,
                                'value' => $v,
                                'option_type'=>$option_type,
								'image_url'=>isset($v['image_url']) ? $v['image_url'] : null
                            ];
                            $i++;
                        }
                    }
                    if(count($option))
                        $subquestion['option'] = $option;
                    unset($subquestion['question']);
                    $subData = ['title'=>$subquestion['title'],'subtitle'=>$subtitle,'is_nested_question'=>$isNested,
                        'questions'=>json_encode($subquestion,true),'parent_question_id'=>$x->id,
                        'header_id'=>$headerId,'is_mandatory'=>$isMandatory,'is_active'=>1,'global_question_id'=>$globalQuestion->id];
                    \Log::info("question sub ");
                    \Log::info($subData);
                    Questions::create($subData);

                }
            }
        }
    }
}