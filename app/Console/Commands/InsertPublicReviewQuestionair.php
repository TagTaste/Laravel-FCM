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



            ['header_name' => "INSTRUCTIONS", 'header_selection_type' => "0"],

            ['header_name' => "Your Food Shot", 'header_selection_type' => "3"],




            ['header_name' => "APPEARANCE", "header_info" => ["text" => "Examine the product and assess the questions outlined below.Examine the product and assess the questions outlined below.Examine the product and assess the questions outlined below.Examine the product and assess the questions outlined below.Examine the product and assess the questions outlined below.Examine the product and assess the questions outlined below.this is the last line."], 'header_selection_type' => "1"],





            ['header_name' => "AROMA", "header_info" => ["images" => ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/share/share-shoutout-small.png","https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/share/share-shoutout-small.png","https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/share/share-shoutout-small.png","https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/share/share-shoutout-small.png","https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/share/share-shoutout-small.png"] ], 'header_selection_type' => "1"],





            ['header_name' => "TASTE", "header_info" => ["video_link" => "https://www.youtube.com/watch?v=TGOdxQhgi5Y"], 'header_selection_type' => "1"],





            ['header_name' => "AROMATICS TO FLAVORS", "header_info" => ["text" => "You have already identified the taste and now in this section, you will be identifying aromatics. Unlike aromas, aromatics are the odors that reach the sensors of the nose from inside the mouth ( reverse action). Reverse Action - As we eat with our mouth closed, food releases odors.  Identify the Odor/s inside the mouth using the same aroma list that you have already used to identify odor/s under the aroma section.", "images" => ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/share/share-shoutout-small.png"], "video_link" => "https://www.youtube.com/watch?v=HCjNJDNzw8Y"], 'header_selection_type' => "1"],





            ['header_name' => "TEXTURE", "header_info" => ["text" => "We have covered taste and odor/s (inside and outside the mouth). Now it is the turn of ‘feel’ inside the mouth. ‘Feel’ starts when the food comes in contact with the mouth; the ‘feel’ changes as the food is processed inside the mouth because of chewing (Applied Pressure) and the ‘feel’ may even last after the food has been swallowed. Foods when chewed may make SOUND (like chips), give us joy (like creamy foods), pain (like sticky foods) or even disgust for some (like rubbery foods -mushroom). Texture (mouthfeel) is all about the joy we get from what we eat.", "images" => ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/share/share-shoutout-small.png"], "video_link" => "https://www.youtube.com/watch?v=TGOdxQhgi5Y"], 'header_selection_type' => "1"],





            ['header_name' => "PRODUCT EXPERIENCE", "header_info" => ["text" => "Consider all the attributes like Appearance, Aroma, Taste, Aromatics, Flavor, Texture and rate the overall experience of the product on the preference scale.", "images" => ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/share/share-shoutout-small.png","https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/share/share-shoutout-small.png","https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/share/share-shoutout-small.png","https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/share/share-shoutout-small.png","https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/share/share-shoutout-small.png"], "video_link" => "https://www.youtube.com/watch?v=TGOdxQhgi5Y"], 'header_selection_type' => "2"]



        ];

        $questions2 = '{
	"INSTRUCTIONS": [

		{

			"title": "INSTRUCTION",

			"subtitle": "Welcome to product review!\nIf the product involves cooking (like instant noodles) or mixing (like packaged bhelpuri), you must follow the instructions fully as mentioned on the packet. To review, follow the questionnaire and select answers that match with your observations.\nRemember, there are no right or wrong answers.",

			"select_type": 4,

			"question_info": {
				"text": "Take a bite, eat normally and assess the taste/s and its intensity as mentioned in the section. What is Umami? When the taste causes continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth and has a long lasting aftertasteTake a bite, eat normally and assess the taste/s and its intensity as mentioned in the section. What is Umami? When the taste causes continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth and has a long lasting aftertasteTake a bite, eat normally and assess the taste/s and its intensity as mentioned in the section. roof, back of the mouth and has a long lasting aftertasteTake a bite, eat normally and assess the taste/s and its intensity as mentioned in the section. What is Umami? When the taste causes continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth and has a long lasting aftertasteTake a bite, eat normally and assess the taste/s and its intensity as mentioned in the section. What is Umami? When the taste causes continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth and has a long lasting aftertaste",
				"images": ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/share/share-shoutout-small.png"],
				"video_link": "https://www.youtube.com/watch?v=HCjNJDNzw8Y"
			}
		}

	],

	"Your Food Shot": [

		{

			"title": "Take a selfie with the product",

			"subtitle": "This will act as a social proof that you did taste.",

			"select_type": 6

		}

	],

	"APPEARANCE": [

		{

			"title": "How is the color of the product?",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,
			"option": [

				{

					"value": "Tempting",
					"is_intensity": 0
				},
				{
					"value": "Just fine",
					"is_intensity": 0

				},
				{
					"value": "Not appealing",
					"is_intensity": 0
				}

			]

		},

		{

			"title": "How is the visual texture of the product?",

			"select_type": 2,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [

				{

					"value": "Crispy",
					"is_intensity": 0
				},
				{
					"value": "Firm",
					"is_intensity": 0

				},
				{
					"value": "Lumpy",
					"is_intensity": 0
				},
				{

					"value": "Sticky",
					"is_intensity": 0
				},
				{
					"value": "Soft",
					"is_intensity": 0

				},
				{
					"value": "Hard",
					"is_intensity": 0
				},
				{

					"value": "Tender",
					"is_intensity": 0
				},
				{
					"value": "Stringy",
					"is_intensity": 0

				},
				{
					"value": "Chewy",
					"is_intensity": 0
				},
				{

					"value": "Chunky",
					"is_intensity": 0
				},
				{
					"value": "Crusty",
					"is_intensity": 0

				},
				{
					"value": "Dry",
					"is_intensity": 0
				},
				{

					"value": "Oily",
					"is_intensity": 0
				},
				{
					"value": "Mushy",
					"is_intensity": 0

				},
				{
					"value": "Rubbery",
					"is_intensity": 0
				},
				{
					"value": "Smooth",
					"is_intensity": 0
				}

			]
		},
		{

			"title": "Overall preference of Appearance",

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

	"AROMA": [

		{

			"title": "Which all aromas did you observe?",

			"subtitle": "Click on the following search box to select the aromas that you observed. Select \"Any other\" in case you are unable to find or recall its name or \"Absent\" if you could not smell any Aroma at all.",

			"select_type": 2,

			"is_intensity": 1,

			"intensity_type": 2,

			"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense",

			"is_nested_question": 0,

			"is_mandatory": 1,

			"is_nested_option": 1,

			"nested_option_list": "AROMA"

		},

		{

			"title": "Overall preference of Aroma",

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

	"TASTE": [

		{

			"title": "Which Basic tastes did you observe?",
			"is_nested_question": 0,
			"is_intensity": 0,
			"is_nested_option": 0,
			"is_mandatory": 1,
			"select_type": 2,
			"option": [

				{

					"value": "Sweet",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
				},
				{
					"value": "Salt",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				},
				{
					"value": "Sour",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Acidic,Weakly Acidic,Mildly Acidic, Moderately Acidic, Intensely Acidic, Very Intensely Acidic, Extremely Acidic"
				},
				{
					"value": "Bitter",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				},
				{
					"value": "Umami",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				},
				{
					"value": "No Basic Taste",
					"is_intensity": 0

				}


			]

		},

		{
			"title": "Which Ayurvedic tastes did you observe?",

			"select_type": 2,
			"is_intensity": 0,
			"is_mandatory": 1,

			"is_nested_question": 0,

			"is_nested_option": 0,

			"option": [

				{
					"value": "Astringent(Dryness)",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
				},
				{
					"value": "Pungent (Spices / Garlic)",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"

				},
				{
					"value": "Pungent Cool Sensation (Mint)",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"

				},
				{
					"value": "Pungent Chilli",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
				},
				{
					"value": "No Ayurvedic Taste",
					"is_intensity": 0

				}

			]

		},
		{

			"title": "Overall preference of Taste",

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

	"AROMATICS TO FLAVORS": [

		{

			"title": "Which all aromatics did you observe?",

			"subtitle": "Click on the following search box to select the aromatics that you observed. Select \"Any other\" in case you are unable to find or recall its name or \"Absent\" if you could not smell any Aromatics at all.",

			"select_type": 2,

			"is_intensity": 1,

			"intensity_type": 2,

			"intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense",

			"is_nested_question": 0,

			"is_mandatory": 1,

			"is_nested_option": 1,

			"nested_option_list": "AROMA"

		},

		{

			"title": "After swallowing the food, how was the aftertaste?",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [

				{
					"value": "Pleasant",
					"is_intensity": 0

				},
				{
					"value": "Unpleasant",
					"is_intensity": 0
				},
				{
					"value": "Can\'t Say",
					"is_intensity": 0
				}

			]
		},
		{

			"title": "How was the flavor experienced by you?",
			"subtitle": "Flavor is experienced only inside the mouth when the taste and aromatics (odor through the mouth) work together.",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [

				{
					"value": "Natural & pleasant",
					"is_intensity": 0

				},
				{
					"value": "Natural but unpleasant",
					"is_intensity": 0
				},
				{
					"value": "Artificial but pleasant",
					"is_intensity": 0
				},
				{
					"value": "Artificial & unpleasant",
					"is_intensity": 0

				},
				{
					"value": "Bland",
					"is_intensity": 0
				}

			]
		},
		{

			"title": "Overall preference of Aromatics",

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

	"TEXTURE": [

		{

			"title": "Take a single bite and pause. What kind of sound did you hear?",
			"subtitle": "Crispy (Potato chips): One sharp, clean, fast, and high pitched sound.\nCrunchy (Rusks): Multiple low pitched crushing sounds perceived as a series of small events.\nCrackly (Sugar crystals): One sudden low pitched sound that brittles the product.",

			"select_type": 1,

			"is_nested_question": 0,
			"is_intensity": 0,
			"is_mandatory": 1,
			"option": [

				{
					"value": "Crispy",
					"is_intensity": 0

				},
				{
					"value": "Crunchy",
					"is_intensity": 0

				},
				{
					"value": "Crackly",
					"is_intensity": 0

				},
				{
					"value": "No sound",
					"is_intensity": 0

				}

			]

		},

		{

			"title": "Chew the product 3-4 times and pause. How is the texture of the product?",

			"select_type": 2,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,
			"option": [

				{
					"value": "Spongy",
					"is_intensity": 0

				},
				{
					"value": "Firm",
					"is_intensity": 0
				},
				{
					"value": "Lumpy",
					"is_intensity": 0

				},
				{
					"value": "Sticky",
					"is_intensity": 0

				},
				{
					"value": "Soft",
					"is_intensity": 0
				},
				{
					"value": "Hard",
					"is_intensity": 0

				},
				{
					"value": "Tender",
					"is_intensity": 0

				},
				{
					"value": "Stringy",
					"is_intensity": 0
				},
				{
					"value": "Chewy",
					"is_intensity": 0

				},
				{
					"value": "Chunky",
					"is_intensity": 0

				},
				{
					"value": "Dry",
					"is_intensity": 0
				},
				{
					"value": "Oily",
					"is_intensity": 0

				},
				{
					"value": "Mushy",
					"is_intensity": 0

				},
				{
					"value": "Rubbery",
					"is_intensity": 0

				}
			]

		},
		{

			"title": "Did you feel anything left inside the mouth after swallowing the product?",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,
			"option": [

				{
					"value": "Oily film",
					"is_intensity": 0

				},
				{
					"value": "Loose particles",
					"is_intensity": 0
				},
				{
					"value": "Sticking on tooth",
					"is_intensity": 0

				},
				{
					"value": "Chalky",
					"is_intensity": 0

				},
				{
					"value": "No residue",
					"is_intensity": 0
				}
			]

		},

		{

			"title": "Overall preference of Texture",

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

	"PRODUCT EXPERIENCE": [{

			"title": "Overall product preference",

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

			"select_type": 3,

			"is_intensity": 0,

			"is_mandatory": 0,

			"is_nested_question": 0

		}

	]

}';

        $data = ['name'=>'Live generic questions with selfie','keywords'=>"Masala/ Seasoning",'description'=>null,
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
                            $option[] = [
                                'id' => $i,
                                'value' => $v
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
                                'intensity_value'=>isset($v['intensity_value']) ? $v['intensity_value'] : null
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
                        $option[] = [
                            'id' => $i,
                            'value' => $v
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
                            $extra = \Db::table('public_review_global_nested_option')->where('type','like',$nestedOption->nested_option_list)->get();
                            foreach ($extra as $nested)
                            {
                                $parentId = $nested->parent_id == 0 ? null : $nested->parent_id;
                                $description = isset($nested->description) ? $nested->description : null;
                                $extraQuestion[] = ["sequence_id"=>$nested->s_no,'parent_id'=>$parentId,'value'=>$nested->value,'question_id'=>$x->id,
                                    'is_active'=>1, 'global_question_id'=>$globalQuestion->id,'header_id'=>$headerId,'description'=>$description,'is_intensity'=>$nested->is_intensity];
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
                                    'description'=>$description,'is_intensity'=>$nested->is_intensity];
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
                                ->where('id',$path->id)->update(['path'=>$path->value]);
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
                                    ->whereIn('id',$checknestedIds)->update(['path'=>$pathname->path]);
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
                                $option[] = [
                                    'id' => $i,
                                    'value' => $v
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
                                    'intensity_value'=>isset($v['intensity_value']) ? $v['intensity_value'] : null
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
                            $option[] = [
                                'id' => $i,
                                'value' => $v
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
