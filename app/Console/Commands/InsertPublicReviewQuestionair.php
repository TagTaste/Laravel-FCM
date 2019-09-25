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


            ['header_name' => "Your Food Shot", 'header_selection_type' => "3"],



            ['header_name'=>"APPEARANCE","header_info"=> ["text" => "Biryanis are closely related to Pulaos. Authentic biryanis can be differentiated from pulaos based on the following factors:\n1. Biryanis are usually multi layered. A layer of the main ingredient is generally sandwiched between the bottom and top layers of rice.\n2. Whole spices cannot be seen or felt in the mouth and yet they can be sensed.\n\nOn the other hand in pulaos, all the ingredients are cooked together and thus it has a homogenous color and the whole spices are clearly visible.\n\nNow examine the product visually and answer the questions outlined below."],'header_selection_type'=>"1"],



            ['header_name'=>"AROMA","header_info"=> ["text" => "At this stage, we are assessing only aromas (odors) through the nose, so please don't eat yet. Now bring the product closer to your nose and take a deep breath; you may also take 3-4 short, quick and strong sniffs. Aromas arising from the product can be traced to the ingredients and the processes (like baking, cooking, fermentation etc.) which the product might have undergone."],'header_selection_type'=>"1"],






            ['header_name'=>"TASTE","header_info"=> ["text" => "Eat normally (please include all the components present in the product) and assess the tastes.\n\nAll the tastes except Umami are self-explanatory. Umami taste is felt when you get a continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth; has a long lasting aftertaste and some people crave for more."],'header_selection_type'=>"1"],




            ['header_name'=>"AROMATICS TO FLAVORS","header_info"=> ["text" => "Eat normally with your MOUTH CLOSED and EXHALE THROUGH THE NOSE. Identify the odors that come from inside the mouth; these observed odors are called Aromatics."],'header_selection_type'=>"1"],




            ['header_name'=>"TEXTURE","header_info"=> ["text" => "Let's experience the Texture (Feel) now. ‘Feel’ starts when the product comes in contact with the mouth and the ‘Feel’ may even last after the product has been swallowed. Texture (Feel) is all about the joy we get from what we eat."],'header_selection_type'=>"1"],


            ['header_name'=>"PRODUCT EXPERIENCE","header_info"=> ["text" => "Consider all the attributes - Appearance, Aroma, Taste, Aromatics to flavor and Texture; rate the overall experience of the product on all the parameters taken together."],'header_selection_type'=>"2"]




        ];

        $questions2 = '{


	"INSTRUCTIONS": [


		{


			"title": "Instruction",


			"subtitle": "<b>Welcome to the Product Review!</b>\n\nTo review, follow the questionnaire and select the answers that match your observations.\n\nAny attribute that stands out as either too good or too bad, may please be highlighted in the <b>comment box</b> at the end of the questionnaire.\n\nPlease note that you are reviewing the product and NOT the package. Please click (i) on every screen / page for guidance related to questions.\n\nRemember, there are no right or wrong answers. Let\'s start by opening the package.",


			"select_type": 4


		}


	],

	"Your Food Shot": [


		{


			"title": "Take a selfie with the product",


			"subtitle": "Reviews look more authentic when you post them with a photograph.",


			"select_type": 6


		}


	],





	"APPEARANCE": [


		{


			"title": "How is this product being served to you?",

			"select_type": 1,


			"is_intensity": 0,


			"is_nested_question": 0,


			"is_mandatory": 1,


			"option": [





				{





					"value": "Traditional dum (pot)",


					"is_intensity": 0


				},


				{


					"value": "Platter",


					"is_intensity": 0





				},


				{


					"value": "Delivery box",


					"is_intensity": 0


				},


				{


					"value": "Bowl",


					"is_intensity": 0


				}

			]


		},


		{


			"title": "What is the serving temperature of the product?",
			"subtitle": "You may also touch the product to assess the serving temperature.",

			"select_type": 1,


			"is_intensity": 0,


			"is_nested_question": 0,


			"is_mandatory": 1,


			"option": [





				{





					"value": "Frozen",


					"is_intensity": 0


				},


				{


					"value": "Chilled",


					"is_intensity": 0





				},


				{


					"value": "Cold",


					"is_intensity": 0


				},
				{





					"value": "Room temperature",


					"is_intensity": 0


				},


				{


					"value": "Warm",


					"is_intensity": 0





				},


				{


					"value": "Hot",


					"is_intensity": 0


				},
				{


					"value": "Burning hot",


					"is_intensity": 0

				}


			]


		},
		{


			"title": "Please serve the product in a plate. Does the product (Biryani) served meet the following criteria?",
			"subtitle": "1. Biryanis are usually multi layered. A layer of the main ingredient is generally sandwiched between the bottom and top layers of rice.\n2. Whole spices cannot be seen or felt in the mouth and yet they can be sensed.",

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





				},
				{


					"value": "Doesn\'t matter",


					"is_intensity": 0





				}

			]


		},
		{


			"title": "From the list of ingredients mentioned below, select the ones that are present in your Biryani and tell the proportion of its quantity in the product.",


			"select_type": 2,


			"is_intensity": 0,

			"is_mandatory": 1,

			"is_nested_question": 0,

			"is_nested_option": 0,


			"option": [





				{


					"value": "Rice",


					"is_intensity": 1,


					"intensity_type": 2,


					"intensity_value": "Barely Any,Very Less,Less,Sufficient,Little Extra,Extra,Excess"


				},

				{


					"value": "Veggies",


					"is_intensity": 1,


					"intensity_type": 2,


					"intensity_value": "Barely Any,Very Less,Less,Sufficient,Little Extra,Extra,Excess"






				},
        {


					"value": "Paneer",


					"is_intensity": 1,


					"intensity_type": 2,


					"intensity_value": "Barely Any,Very Less,Less,Sufficient,Little Extra,Extra,Excess"






				},

				{


					"value": "Meat / Poultry",


					"is_intensity": 1,


					"intensity_type": 2,


					"intensity_value": "Barely Any,Very Less,Less,Sufficient,Little Extra,Extra,Excess"



				}
			]


		},
		{


			"title": "How is the visual impression (color and sheen) of the product?",


			"select_type": 2,


			"is_intensity": 0,


			"is_nested_question": 0,


			"is_mandatory": 1,


			"option": [





				{





					"value": "Bright",


					"is_intensity": 0


				},


				{


					"value": "Dull",


					"is_intensity": 0





				},


				{


					"value": "Shiny",


					"is_intensity": 0


				},
				{


					"value": "Oily",


					"is_intensity": 0


				},
				{





					"value": "Light",


					"is_intensity": 0


				},
				{


					"value": "Dark",


					"is_intensity": 0





				},


				{


					"value": "Natural",


					"is_intensity": 0


				},
				{


					"value": "Artificial",


					"is_intensity": 0


				}
			]


		},
		{


			"title": "How is the color of rice in the product?",


			"select_type": 1,


			"is_intensity": 0,


			"is_nested_question": 0,


			"is_mandatory": 1,


			"option": [





				{





					"value": "Same color",


					"is_intensity": 0


				},


				{


					"value": "Multi color",


					"is_intensity": 0





				}
			]


		},
		{


			"title": "Can you identify the type of rice in this product?",
			"subtitle": "Sella rice is parboiled rice i.e it is partly cooked by boiling.",

			"select_type": 1,


			"is_intensity": 0,


			"is_nested_question": 0,


			"is_mandatory": 1,


			"option": [





				{





					"value": "Basmati",


					"is_intensity": 0


				},


				{


					"value": "Non - Basmati",


					"is_intensity": 0





				},
				{


					"value": "Sella Basmati",


					"is_intensity": 0





				},
				{


					"value": "Sella Non - Basmati",


					"is_intensity": 0





				},
				{


					"value": "Can\'t Say",


					"is_intensity": 0





				}

			]


		},
		{


			"title": "Which of these garnishes on the product are appealing to you?",

			"select_type": 2,


			"is_intensity": 0,


			"is_nested_question": 0,


			"is_mandatory": 1,


			"option": [





				{





					"value": "Caramelised onions",


					"is_intensity": 0


				},


				{


					"value": "Herbs",


					"is_intensity": 0





				},
				{


					"value": "Green chilli",


					"is_intensity": 0





				},
				{


					"value": "Nuts",


					"is_intensity": 0





				},
				{


					"value": "Any other",


					"is_intensity": 0

				},
				{


					"value": "None",


					"is_intensity": 0

				}

			]


		},
		{


			"title": "How does the rice appear to you in the product?",

			"select_type": 1,


			"is_intensity": 0,


			"is_nested_question": 0,


			"is_mandatory": 1,


			"option": [





				{





					"value": "Fluffy & separated",


					"is_intensity": 0


				},


				{


					"value": "Not fluffy & separated",


					"is_intensity": 0





				},
				{


					"value": "Sticky & lumpy",


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


			"title": "What all aromas have you sensed?",


			"subtitle": "Directly use the search box to select the aromas that you have identified or follow the category based aroma list. In case you can\'t find the identified aromas, select <b>Any other</b> and if unable to sense any aroma at all, then select <b>Absent.</b>",


			"select_type": 2,


			"is_intensity": 1,


			"intensity_type": 2,


			"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense",


			"is_nested_question": 0,


			"is_mandatory": 1,


			"is_nested_option": 1,


			"nested_option_title": "AROMAS",


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


			"title": "Which Basic tastes have you sensed?",


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





			"title": "Which Ayurvedic tastes have you sensed?",


			"select_type": 2,


			"is_intensity": 0,


			"is_mandatory": 1,





			"is_nested_question": 0,





			"is_nested_option": 0,





			"option": [





				{


					"value": "Astringent (Puckery - Raw banana)",


					"is_intensity": 1,


					"intensity_type": 2,


					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"


				},


				{


					"value": "Pungent (Spices / Garlic)",


					"is_intensity": 1,


					"intensity_type": 2,


					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"





				},


				{


					"value": "Pungent Cool Sensation (Mint)",


					"is_intensity": 1,


					"intensity_type": 2,


					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"





				},


				{


					"value": "Pungent Chilli",


					"is_intensity": 1,


					"intensity_type": 2,


					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"


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


			"title": "What all aromatics have you sensed?",


			"subtitle": "Directly use the search box to select the aromatics that you have identified or follow the category based aromatics list. In case you can\'t find the identified aromatics, select <b>Any other</b> and if unable to sense any aromatics at all, then select <b>Absent</b>.",


			"select_type": 2,


			"is_intensity": 1,


			"intensity_type": 2,


			"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense",


			"is_nested_question": 0,


			"is_mandatory": 1,


			"is_nested_option": 1,


			"nested_option_title": "AROMATICS",


			"nested_option_list": "AROMA"


		},
		{


			"title": "Please swallow the product and pause. How is the aftertaste?",


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


			"title": "What is the length of the aftertaste?",


			"select_type": 1,


			"is_intensity": 0,


			"is_nested_question": 0,


			"is_mandatory": 1,


			"option": [





				{





					"value": "Long",


					"is_intensity": 0


				},


				{


					"value": "Sufficient",


					"is_intensity": 0





				},


				{


					"value": "Short",


					"is_intensity": 0


				},
				{


					"value": "None",


					"is_intensity": 0


				}
			]


		},
		{


			"title": "How is the flavor experience?",
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


			"title": "Which components in the product are contributing more towards enhancing the flavor experience?",
			"subtitle": "Please select top 3 options.",

			"select_type": 2,


			"is_intensity": 0,


			"is_nested_question": 0,


			"is_mandatory": 1,


			"option": [





				{





					"value": "Rice",


					"is_intensity": 0


				},


				{


					"value": "Main ingredient (Meat / Poultry / Veggies)",


					"is_intensity": 0





				},


				{


					"value": "Spices",


					"is_intensity": 0


				},
				{


					"value": "Garnish (Caramlised onion etc)",


					"is_intensity": 0





				},


				{


					"value": "Raita",


					"is_intensity": 0


				},
				{


					"value": "Salan",


					"is_intensity": 0


				},
				{


					"value": "Garnish (Biryani masala)",


					"is_intensity": 0


				},
				{


					"value": "None",


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
			"title": "What are you feeling prominently about the solids (rice) inside the mouth?",
			"subtitle": "Take a spoonful of rice and chew for 3-4 times and pause.",
			"select_type": 1,
			"is_nested_question": 0,
			"is_nested_option": 0,
			"is_mandatory": 1,
			"is_intensity": 0,
			"option": [

				{
					"value": "Chewy",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"


				},
				{
					"value": "Gritty (Hard to chew)",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"


				},
				{
					"value": "Pasty",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"


				},
				{
					"value": "Mushy",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
				},
				{
					"value": "Coarse",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
				},
				{
					"value": "Firm",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
				}

			]
		},


		{


			"title": "How much force is needed to chew the main ingredient (meat / poultry / veggies / paneer)?",
			"subtitle": "Try to take approximately a spoonful of main ingredient only, chew for 3- 4 times and pause.",

			"select_type": 1,


			"is_intensity": 0,


			"is_nested_question": 0,


			"is_mandatory": 1,


			"option": [





				{





					"value": "Barely Any",


					"is_intensity": 0


				},


				{


					"value": "Less",


					"is_intensity": 0





				},


				{


					"value": "Normal",


					"is_intensity": 0



				},
				{


					"value": "Little Extra",


					"is_intensity": 0


				},
				{


					"value": "Excess",


					"is_intensity": 0


				}

			]


		},
		{
			"title": "What are you feeling prominently about the main ingredient (meat / poultry / veggies / paneer) inside your mouth?",
			"subtitle": "Select a maximum 2 options.",
			"select_type": 2,
			"is_nested_question": 0,
			"is_nested_option": 0,
			"is_mandatory": 1,
			"is_intensity": 0,
			"option": [

				{
					"value": "Juicy",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"


				},
				{
					"value": "Tender",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"


				},
				{
					"value": "Dry",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"


				},
				{
					"value": "Fibrous",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
				},
				{
					"value": "Springy",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
				},
				{
					"value": "Mushy",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
				},
				{
					"value": "Rubbery",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
				},
        {
					"value": "Leathery",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
				},
        {
					"value": "Firm",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
				},
        {
					"value": "Hard",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
				}


			]
		},
		{


			"title": "How oily is the product?",
			"subtitle": "Take a spoonful of the product comprising all the ingredients, chew it for minimum 8-10 times and pause.",

			"select_type": 1,


			"is_intensity": 0,


			"is_nested_question": 0,


			"is_mandatory": 1,


			"option": [





				{





					"value": "Oil free",


					"is_intensity": 0


				},


				{


					"value": "Less",


					"is_intensity": 0





				},


				{


					"value": "Moderate",


					"is_intensity": 0



				},
				{


					"value": "Excess",


					"is_intensity": 0


				}

			]


		},
		{


			"title": "What kind of the mass is being formed inside your mouth?",

			"select_type": 1,


			"is_intensity": 0,


			"is_nested_question": 0,


			"is_mandatory": 1,


			"option": [





				{





					"value": "Tight mass",


					"is_intensity": 0


				},


				{


					"value": "Pulpy mass",


					"is_intensity": 0





				},


				{


					"value": "Barely any mass",


					"is_intensity": 0


				},
				{


					"value": "No mass",


					"is_intensity": 0





				}
			]


		},

		{


			"title": "Is this product difficult to swallow?",
			"subtitle": "Assuming you have chewed it at least 8-10 times",

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


			"title": "After swallowing the product, do you feel anything left inside the mouth?",

			"select_type": 2,


			"is_intensity": 0,


			"is_nested_question": 0,


			"is_mandatory": 1,


			"option": [





				{





					"value": "Loose particles",


					"is_intensity": 0


				},


				{


					"value": "Sticking on tooth/ palate",


					"is_intensity": 0





				},
				{





					"value": "Stuck between teeth",


					"is_intensity": 0


				},


				{


					"value": "Chalky",


					"is_intensity": 0





				},
				{





					"value": "Oily film",


					"is_intensity": 0


				},


				{


					"value": "Any other",


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


			"title": "What do you feel about the sides (raita, salan, salad, pickle etc.) served with the product (Biryani)?",



			"select_type": 1,


			"is_intensity": 0,


			"is_nested_question": 0,


			"is_mandatory": 1,


			"option": [





				{





					"value": "Exceeds Expectation",


					"is_intensity": 0


				},


				{


					"value": "Meets Expectation",


					"is_intensity": 0





				},
				{





					"value": "Below Expectation",


					"is_intensity": 0


				},
				{





					"value": "Not applicable",


					"is_intensity": 0


				}
			]


		},
		{


			"title": "How would you describe the serving size of this product?",

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


					"value": "Modest",


					"is_intensity": 0





				},
				{





					"value": "Limited",


					"is_intensity": 0


				}
			]


		},

		{


			"title": "Did this product (Biryani) succeed in satisfying your basic senses?",


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


			"title": "Which attributes can be improved further?",


			"select_type": 2,


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


					"value": "Aromatics to flavor",


					"is_intensity": 0


				},


				{


					"value": "Texture",


					"is_intensity": 0





				},
				{


					"value": "Balanced product",


					"is_intensity": 0


				}





			]


		},


		{





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


			"placeholder": "Share feedback in your own words…",


			"select_type": 3,


			"is_intensity": 0,


			"is_mandatory": 0,


			"is_nested_question": 0


		}


	]


}';

        $data = ['name'=>'Generic Biryani_20th Sep_V2','keywords'=>"Generic Biryani_20th Sep_V2",'description'=>null,
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
                            $extra = \Db::table('public_review_global_nested_option')->where('is_active',1)->where('type','like',$nestedOption->nested_option_list)->get();
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