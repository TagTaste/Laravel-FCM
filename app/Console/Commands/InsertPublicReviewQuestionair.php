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

            ['header_name'=>"INSTRUCTIONS"],


            ['header_name'=>"APPEARANCE","header_info"=>"Visual examination of the product- look for color, presentation, size and texture of the product."],


            ['header_name'=>"AROMA","header_info"=>"Aroma coming from the product can be traced to ingredients and process/es (like baking, cooking, fermentation etc.) which the product has undergone. Now smell it vigorously through your nose; at this stage, we are only assessing the aroma (odor through the nose), so please don't take a bite yet. Bring the product closer to your nose and take a deep breath. Further, take short, quick and strong sniffs like how a dog sniffs. "],



            ['header_name'=>"TASTE","header_info"=>"Take a bite, eat normally and assess the taste/s and its intensity as mentioned in the section. 

What is Umami?

When the taste causes continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth and has a long lasting aftertaste."],


            ['header_name'=>"AROMATICS TO FLAVORS","header_info"=>"Aromatics is different from the aroma, it is about experiencing odor/s inside the mouth, as you eat. Please take a bite again, eat normally, keeping your mouth closed and exhale through the nose. Identify the odours inside your mouth using the aroma/aromatics list"],


            ['header_name'=>"TEXTURE","header_info"=>"Let us assess the (oral) texture- please look for lip feel, first chew experience, chew down experience, swallow, and most importantly sound (whenever applicable)."],


            ['header_name'=>"OVERALL PRODUCT EXPERIENCE","header_info"=>"RATE the overall experience of the product on the preference scale."]


        ];


        $questions2 = '{

	"INSTRUCTIONS": [

		{

			"title": "Instruction",

			"subtitle": "Please follow the questionnaire and click answers that match with your observation/s. Remember, there are no right or wrong answers. In case you observe something that is not covered in the questionnaire, you are most welcome to share your additional inputs in the comments box.\n Anything that stands out as either too good or too bad, may please be highlighted in the comments box.",

			"select_type": 4

		}

	],

	"APPEARANCE": [

		{

			"title": "How is the color of the product",

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

			"title": "Did you like the presentation of the product (like shape, plating etc)",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [

				{

					"value": "Impressive",
					"is_intensity": 0
				},
				{
					"value": "Average",
					"is_intensity": 0

				},
				{
					"value": "Below average",
					"is_intensity": 0
				}

			]
		},
		{

			"title": "Assess the portion size of the product",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [

				{

					"value": "Generous",
					"is_intensity": 0
				},
				{
					"value": "Adequate",
					"is_intensity": 0

				},
				{
					"value": "Inadequate",
					"is_intensity": 0
				}

			]
		},
		{

			"title": "How is the texture of the product",

			"select_type": 1,

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
					"value": "Creamy",
					"is_intensity": 0
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

		}

	],

	"AROMA": [

		{

			"title": "Identify the observed Aroma. Please mention a maximum of 2 dominant aromas.",

			"subtitle": "We have a list of aromas/ aromatics, grouped under different heads. If you select \"any other \" option please write the identified aromas. Use the search box to find any aroma/aromatics from the list.",

			"select_type": 2,

			"is_intensity": 1,

			"intensity_type": 2,

			"intensity_value": "Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming",

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

		}

	],

	"TASTE": [

		{

			"title": "Basic Taste",

			"is_nested_question": 0,
			"is_intensity": 1,
			"is_nested_option": 0,
			"is_mandatory": 1,
			"select_type": 2,
			"option": [

				{

					"value": "Sweet",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely detectable,Identifiable but not very intense ,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"value": "Salt",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely detectable,Identifiable but not very intense ,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"

				},
				{
					"value": "Sour",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Acidic,Mildly Acidic,Moderately Acidic,Strongly Acidic,Intensely Acidic,Very Intensely Acidic,Extremely Acidic"
				},
				{
					"value": "Bitter",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely detectable,Identifiable but not very intense ,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"

				},
				{
					"value": "Umami",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely detectable,Identifiable but not very intense ,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"

				},
				{
					"value": "No Basic Taste",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely detectable,Identifiable but not very intense ,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"

				}

			]

		},

		{
			"title": "Ayurveda Taste",

			"select_type": 2,
			"is_intensity": 1,
			"is_mandatory": 1,

			"is_nested_question": 0,

			"is_nested_option": 0,

			"option": [

				{
					"value": "Astringent (Dryness)",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely detectable,Identifiable but not very intense ,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"value": "Pungent (Spices/ Garlic)",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely detectable,Identifiable but not very intense ,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"

				},
				{
					"value": "Pungent Cool Sensation (Mint)",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Acidic,Mildly Acidic,Moderately Acidic,Strongly Acidic,Intensely Acidic,Very Intensely Acidic,Extremely Acidic"

				},
				{
					"value": "Pungent Chilli",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely detectable,Identifiable but not very intense ,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"value": "No Ayurveda Taste",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely detectable,Identifiable but not very intense ,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
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

		}

	],

	"AROMATICS TO FLAVORS": [

		{

			"title": "Identify the Aromatics observed. Please mention a maximum of 2 dominant aromatics.",

			"subtitle": "We have a list of aromas/ aromatics, grouped under different heads. If you select \"any other\" option please write the identified aromatics. Use the search box to find any  aroma/aromatics from the list.",

			"select_type": 2,

			"is_intensity": 1,

			"intensity_type": 2,

			"intensity_value": "Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming",

			"is_nested_question": 0,

			"is_mandatory": 1,

			"is_nested_option": 1,

			"nested_option_list": "AROMA"

		},

		{

			"title": "After swallowing the food did you feel the presence of any aftertaste",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [

				{
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

			"title": "FLAVOR",

			"subtitle": "As a rule of thumb, Flavor is a combination of Taste (25%) and Aromatics (75%). Congratulations! You just discovered the flavor/s of the product that you are tasting.",

			"is_nested_question": 1,

			"is_mandatory": 1,

			"question": [

				{

					"title": "Did you experience any Flavors?",

					"select_type": 1,

					"is_intensity": 0,

					"is_nested_question": 0,

					"is_mandatory": 1,

					"option": [

						{
							"value": "No Flavor",
							"is_intensity": 0

						},
						{
							"value": "Can\'t say",
							"is_intensity": 0
						},
						{
							"value": "Desirable Flavor",
							"is_intensity": 0

						},
						{
							"value": "Undesirable Flavor",
							"is_intensity": 0
						}

					]


				},
				{
					"title": "Was the observed flavor natural or any of the trending inspirational flavors. Please select the relevant options.",

					"select_type": 2,

					"is_intensity": 0,

					"is_nested_question": 0,

					"is_mandatory": 0,
					"option": [

						{
							"value": "Natural",
							"is_intensity": 0

						},
						{
							"value": "Wasabi",
							"is_intensity": 0
						},
						{
							"value": "Sriracha",
							"is_intensity": 0

						},
						{
							"value": "Smoky Barbeque",
							"is_intensity": 0
						},
						{
							"value": "Tandoori",
							"is_intensity": 0

						},
						{
							"value": "Kebab",
							"is_intensity": 0
						},
						{
							"value": "Jalapeno Cheese",
							"is_intensity": 0

						},
						{
							"value": "Chipotle",
							"is_intensity": 0
						},
						{
							"value": "Sour cream and onion",
							"is_intensity": 0

						},
						{
							"value": "Salsa",
							"is_intensity": 0
						},
						{
							"value": "Pudina chutney",
							"is_intensity": 0

						},
						{
							"value": "Creamy truffle",
							"is_intensity": 0
						},
						{
							"value": "Any other",
							"is_intensity": 0
						}

					]

				}

			]

		},

		{

			"title": "Overall preference",

			"subtitle": "Share your overall preference for the Flavor (considering Aromatics and Taste together)",

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

		}

	],

	"TEXTURE": [

		{

			"title": "Please put the product in your mouth and assess. Remember not to eat or chew at this stage.",

			"select_type": 2,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [

				{
					"value": "Moist",
					"is_intensity": 0

				},
				{
					"value": "Dry",
					"is_intensity": 0
				},
				{
					"value": "Creamy",
					"is_intensity": 0

				},
				{
					"value": "Spongy",
					"is_intensity": 0
				},
				{
					"value": "Runny liquid",
					"is_intensity": 0

				}
			]
		},

		{

			"title": "Sound of the product (Concentrate on the sound it produces after the first bite and subsequent bites)",

			"select_type": 1,

			"is_nested_question": 0,
			"is_intensity": 1,
			"is_mandatory": 1,
			"option": [

				{
					"value": "Crispy",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely detectable,Identifiable but not very intense ,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense",
					"is_nested_question": 0,
					"is_nested_option": 0
				},
				{
					"value": "Crunchy",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely detectable,Identifiable but not very intense ,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense",
					"is_nested_question": 0,
					"is_nested_option": 0

				},
				{
					"value": "Crackly",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Acidic,Mildly Acidic,Moderately Acidic,Strongly Acidic,Intensely Acidic,Very Intensely Acidic,Extremely Acidic",
					"is_nested_question": 0,
					"is_nested_option": 0

				}

			]

		},

		{

			"title": "Please put the product again in your mouth, chew 3-4 times, pause and assess.",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,
			"option": [

				{
					"value": "Soft",
					"is_intensity": 0

				},
				{
					"value": "Firm",
					"is_intensity": 0
				},
				{
					"value": "Hard",
					"is_intensity": 0

				}
			]

		},

		{

			"title": "Chew down",

			"subtitle": "Take a bite again, chew it for 8-10 times to make a pulp. Now assess the time taken to make a pulp.",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [

				{
					"value": "Shorter time to chew",
					"is_intensity": 0

				},
				{
					"value": "Moderate time to chew",
					"is_intensity": 0
				},
				{
					"value": "Longer time to chew",
					"is_intensity": 0

				}
			]

		},

		{

			"title": "After swallowing, how do you feel inside the mouth?",

			"select_type": 2,

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
					"value": "None",
					"is_intensity": 0

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

		}

	],

	"OVERALL PRODUCT EXPERIENCE": [

		{

			"title": "Are all the attributes (appearance, aroma, taste, aromatics to flavor and texture) in balance with each other?",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [

				{
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

			"title": "If not, what is/are out of balance?",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 0,

			"option": [

				{
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
					"value": "Aromatics to Flavor",
					"is_intensity": 0
				},
				{
					"value": "Texture",
					"is_intensity": 0
				}
			]
		},

		{

			"title": "Is the product sample acceptable?",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [

				{
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

			"title": "Overall Product Experience",

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

        $data = ['name'=>'try - 1','keywords'=>"Masala/ Seasoning",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];

        \DB::table('public_review_global_questions')->insert($data);

        $globalQuestion = \DB::table('public_review_global_questions')->orderBy('id', 'desc')->first();

        $headerData = [];
        // header_selection_type
        // for instruction = 0  , overall preferance = 2 others = 1
        foreach ($headerInfo2 as $item)
        {
            $headerData[] = ['header_type'=>$item['header_name'],'is_active'=>1,
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
                                    'is_active'=>1, 'global_question_id'=>$globalQuestion->id,'header_id'=>$headerId,'description'=>$description];
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
                                    'description'=>$description];
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
                    \Log::info($data);
                    Questions::create($data);

                }
            }
        }

    }
}
