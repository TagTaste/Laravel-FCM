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


            ['header_name'=>"APPEARANCE","header_info"=> ["text" => "Empty the package on a white plate. Examine the product and answer the questions outlined below."],'header_selection_type'=>"1"],


            ['header_name'=>"AROMA","header_info"=> ["text" => "At this stage, we are assessing only aromas (odors) through the nose, so please don't take a bite yet. Now bring the product closer to your nose and take a deep breath; you may also take 3-4 short, quick and strong sniffs. Aroma(s) arising from the product can be traced to the ingredients and the processes (like baking, cooking, fermentation etc) which the product might have undergone."],'header_selection_type'=>"1"],




            ['header_name'=>"TASTE","header_info"=> ["text" => "Eat normally and assess the tastes.\n\nAll the tastes except Umami are self-explanatory. Umami taste is felt when you get a continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth; has a long lasting aftertaste and some people crave for more."],'header_selection_type'=>"1"],



            ['header_name'=>"AROMATICS TO FLAVORS","header_info"=> ["text" => "Eat normally with your MOUTH CLOSED and EXHALE THROUGH THE NOSE. Identify the odors that come from inside the mouth; these identified odors are called Aromatics."],'header_selection_type'=>"1"],


            ['header_name'=>"TEXTURE","header_info"=> ["text" => "Let's experience the Texture (Feel) now. ‘Feel’ starts when the product comes in contact with the mouth and the ‘Feel’ may even last after the product has been swallowed. Texture (Feel) is all about the joy we get from what we eat."],'header_selection_type'=>"1"],


            ['header_name'=>"PRODUCT EXPERIENCE","header_info"=> ["text" => "Consider all the attributes - Appearance, Aroma, Taste, Aromatics To Flavors, and Texture; rate the overall experience of the product on all parameters taken together."],'header_selection_type'=>"2"]

        ];

        $questions2 = '{





	"INSTRUCTIONS": [





		{






			"title": "Instruction",



			"subtitle": "<b>Welcome to the Product Review!</b>\n\nIf a product involves cooking (like instant noodles) or mixing (like packaged bhelpuri), the taster must follow the instructions fully as mentioned on the packet.\n\nTo review, follow the questionnaire and select the answers that match your observations. Please click (i) on every screen / page for guidance related to questions.\n\nAny attribute that stands out as either too good or too bad, may please be highlighted in the <b>comment box</b> at the end of the questionnaire.\n\nPlease note that you are reviewing the product and NOT the package. Remember, there are no right or wrong answers. Let\'s start by opening the package.",




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










					"value": "Room Temperature",







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







					"value": "Burning Hot",







					"is_intensity": 0







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










					"value": "Glazed",







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







			"title": "Which prominent coating do you observe on the product?",






			"select_type": 1,







			"is_intensity": 0,







			"is_nested_question": 0,







			"is_mandatory": 1,







			"option": [










				{










					"value": "Spice mix",







					"is_intensity": 0







				},







				{







					"value": "Sugar crystals",







					"is_intensity": 0










				},







				{







					"value": "Salt",







					"is_intensity": 0







				},





				{










					"value": "Herbs",







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







			"title": "How does the visual texture of the product appear to you?",





			"select_type": 2,







			"is_intensity": 0,







			"is_nested_question": 0,







			"is_mandatory": 1,







			"option": [










				{










					"value": "Baked",







					"is_intensity": 0







				},







				{







					"value": "Roasted",







					"is_intensity": 0










				},







				{







					"value": "Fried",







					"is_intensity": 0







				},





				{










					"value": "Sticky",







					"is_intensity": 0







				},







				{







					"value": "Smooth",







					"is_intensity": 0










				},







				{







					"value": "Rough",







					"is_intensity": 0







				},





				{







					"value": "Crisp",







					"is_intensity": 0






				},

				{







					"value": "Limp",







					"is_intensity": 0






				},

				{







					"value": "Firm",







					"is_intensity": 0






				}







			]







		},


		{







			"title": "What is the prominent impression about the coating?",







			"select_type": 1,







			"is_intensity": 0,







			"is_nested_question": 0,







			"is_mandatory": 1,







			"option": [










				{










					"value": "Even coating",







					"is_intensity": 0







				},







				{







					"value": "Partially missed coating",







					"is_intensity": 0










				},







				{







					"value": "Clumpy Coating",







					"is_intensity": 0







				},





				{










					"value": "Loose seasoning",







					"is_intensity": 0







				},







				{







					"value": "No coating",







					"is_intensity": 0










				}







			]







		},



{







			"title": "Using fingers, hold the pieces for few seconds and put them back on the plate. What do you observe on your fingers?",







			"is_nested_question": 0,







			"is_intensity": 0,







			"is_nested_option": 0,







			"is_mandatory": 1,







			"select_type": 2,







			"option": [










				{










					"value": "Oily film",







					"is_intensity": 1,







					"intensity_type": 2,







					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"







				},







				{







					"value": "Sticky film (Non oily)",







					"is_intensity": 1,







					"intensity_type": 2,







					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"










				},







				{







					"value": "Seasoning",







					"is_intensity": 1,







					"intensity_type": 2,







					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"






				},







				{







					"value": "None",







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





			"subtitle": "Directly use the search box to select the aromas that you have identified or follow the category based aroma list. In case you can\'t find the identified aromas, select <b>Any other</b> and if unable to sense any aroma at all, then select <b>Absent</b>.",





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







					"value": "Astringent (Dryness - Raw Banana)",







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







					"value": "Can\'t say",







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


			"title": "Take sufficient quantity of the product, bite the product just once then identify the sound and its intensity. Which prominent sound do you hear?",


			"subtitle": "Crispy - One sharp, clean, fast, and high pitched sound. Eg., Chips.\nCrunchy - Multiple low pitched crushing sounds perceived as a series of small events. Eg., Rusks.\nCrackly - One sudden low pitched sound that brittles the product. Eg., Puffed rice.",


			"select_type": 1,


			"is_nested_question": 0,


			"is_nested_option": 0,


			"is_mandatory": 1,


			"is_intensity": 0,


			"option": [



				{


					"value": "Crispy",


					"is_intensity": 1,


					"intensity_type": 2,


					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"




				},


				{


					"value": "Crunchy",


					"is_intensity": 1,


					"intensity_type": 2,


					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"




				},


				{


					"value": "Crackly",


					"is_intensity": 1,


					"intensity_type": 2,


					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"




				},


				{


					"value": "No Sound",


					"is_intensity": 0


				}



			]


		},

		{







			"title": "How much force is needed to chew the product?",

	    "subtitle": "Please chew the product 3-4 times and pause.",


			"select_type": 1,







			"is_intensity": 0,







			"is_nested_question": 0,







			"is_mandatory": 1,







			"option": [










				{










					"value": "Barely any force",







					"is_intensity": 0








				},







				{







					"value": "Normal force",







					"is_intensity": 0











				},







				{







					"value": "Extra force",







					"is_intensity": 0









				}
				


			]



		},
{


			"title": "As you chew, what is prominently being released from the product?",



			"select_type": 1,


			"is_nested_question": 0,


			"is_nested_option": 0,


			"is_mandatory": 1,


			"is_intensity": 0,


			"option": [



				{


					"value": "Oil",


					"is_intensity": 1,


					"intensity_type": 2,


					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"




				},


				{


					"value": "Moisture",


					"is_intensity": 1,


					"intensity_type": 2,


					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"




				},


				{


					"value": "Dry",


					"is_intensity": 1,


					"intensity_type": 2,


					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"




				}



			]


		},




		{





			"title": "Are you experiencing any melt-in-the-mouth feeling from the product?",



			"select_type": 1,





			"is_nested_question": 0,





			"is_nested_option": 0,





			"is_mandatory": 1,





			"is_intensity": 0,





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





					"value": "Can\'t say",





					"is_intensity": 0





				}






			]





		},





		{







			"title": "While chewing, which textures can you feel inside your mouth?",

	    "subtitle": "Please select a maximum of 4 options.",



			"select_type": 2,







			"is_intensity": 0,







			"is_nested_question": 0,







			"is_mandatory": 1,







			"option": [










				{










					"value": "Soft",







					"is_intensity": 0








				},







				{







					"value": "Tender",







					"is_intensity": 0











				},







				{







					"value": "Spongy",







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







					"value": "Pasty",







					"is_intensity": 0









				},
				{







					"value": "Fibrous",







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







					"value": "Coarse",







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







			"title": "What kind of mass is being formed?",

	    "subtitle": "Take sufficient quantity of the product, chew it for minimum 8-10 times and pause.",



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







			"title": "After swallowing the product, what do you feel is left inside the mouth?",



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







					"value": "Sticking on tooth / palate",







					"is_intensity": 0









				},





				{







					"value": "Stuck between tooth",







					"is_intensity": 0







				},
				{







					"value": "Chalky",







					"is_intensity": 0







				},
				{







					"value": "No residue",







					"is_intensity": 0







				},
				{







					"value": "Any other",







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





	"PRODUCT EXPERIENCE": [




		{







			"title": "Did this product succeed in satisfying your basic senses?",






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







			"is_mandatory": 1,







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







					"value": "Aromatics to flavors",







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










			"title": "Overall Product Preference",







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

        $data = ['name'=>'generic_namkeen_v2','keywords'=>"generic_namkeen_v2",'description'=>null,
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