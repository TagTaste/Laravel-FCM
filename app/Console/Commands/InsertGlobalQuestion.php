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



            ['header_name'=>"INSTRUCTIONS"],


            ['header_name'=>"APPEARANCE","header_info"=> ["text" => "Examine the product visually and answer the questions outlined below."]],



            ['header_name'=>"AROMA","header_info"=> ["text" => "At this stage, we are assessing only aromas (odors) through the nose, so please don't take a bite yet. Now bring the product closer to your nose and take a deep breath; you may also take 3-4 short, quick and strong sniffs. Aromas arising from the product can be traced to the ingredients and the processes (like baking, cooking, fermentation etc) which the product might have undergone."]],



            ['header_name'=>"TASTE","header_info"=> ["text" => "Eat sufficient quantity of the product and assess the taste/s.\n\nAll the tastes except Umami are self-explanatory. Umami taste is felt when you get a continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth; has a long lasting aftertaste and some people crave for more.\n\nAnything that stands out as either too good or too bad, may please be highlighted in the Comment Box."]],



            ['header_name'=>"AROMATICS TO FLAVORS","header_info"=> ["text" => "Unlike aromas, aromatics are the odors that reach the sensors of the nose from inside the mouth (reverse action).\nReverse Action - As we eat with our mouth closed, food releases odors. These odors are sensed by us as they travel to the back of the throat and then turn up towards the sensors of the nose.\nPlease take a bite again, eat normally, keeping your MOUTH CLOSED and EXHALE THROUGH THE NOSE. Identify the odors that come from inside the mouth."]],


            ['header_name'=>"TEXTURE","header_info"=> ["text" => "Let's experience the Texture (Feel) now. FEEL starts when the product is put inside the mouth; FEEL changes when the product is chewed, and it may even last after the product is swallowed. Product may make sound (chips), may give us joy (creamy foods) and may even cause pain or disgust (sticky/slimy foods).\n\nAnything that stands out as either too good or too bad, may please be highlighted in the Comment Box."]],



            ['header_name'=>"PRODUCT EXPERIENCE","header_info"=> ["text" => "Rate the overall experience of the product on the preference scale."]]


        ];

        $questions2 = '{


	"INSTRUCTIONS": [


		{


			"title": "Instruction",


			"subtitle": "To review, follow the questionnaire and select the answers that match with your observations.\n\nPlease click (!) on every screen/page for guidance related to questions.\n\nRemember, there are no right or wrong answers.\nAnything that stands out as either too good or too bad, may please be highlighted in the Comment Box.",


			"select_type": 4


		}


	],





	"APPEARANCE": [


		{


			"title": "At what temperature has the product been served? You may also touch and confirm the temperature.",


			"select_type": 1,


			"is_intensity": 0,


			"is_nested_question": 0,


			"is_mandatory": 1,


			"option": [





				{





					"value": "Below room temperature",


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


					"value": "Very hot",


					"is_intensity": 0


				},


				{


					"value": "Burning hot",


					"is_intensity": 0


				}





			]


		},


		{


			"title": "What is the color of the crust? If you select \"Any other\" , then please mention it in the Comment Box.",


			"select_type": 1,


			"is_intensity": 0,


			"is_nested_question": 0,


			"is_mandatory": 1,


			"option": [





				{





					"value": "Hay",


					"is_intensity": 0


				},


				{


					"value": "Straw",


					"is_intensity": 0





				},


				{


					"value": "Golden",


					"is_intensity": 0


				},


				{


					"value": "Yellow",


					"is_intensity": 0


				},


				{


					"value": "Copper",


					"is_intensity": 0


				},


				{


					"value": "Bronze",


					"is_intensity": 0


				},


				{


					"value": "Light brown",


					"is_intensity": 0


				},


				{


					"value": "Brown",


					"is_intensity": 0


				},

				{


					"value": "Whitish",


					"is_intensity": 0


				},


				{


					"value": "Any other",


					"is_intensity": 0


				}





			]


		},



		{


			"title": "How does the product appear to you?",


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





				}, {





					"value": "Shiny",


					"is_intensity": 0


				},


				{


					"value": "Dehydrated",


					"is_intensity": 0





				},


				{


					"value": "Oily",


					"is_intensity": 0


				},


				{


					"value": "Soggy",


					"is_intensity": 0





				}, {





					"value": "Limp",


					"is_intensity": 0


				},


				{


					"value": "Firm",


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





					"value": "Clear",


					"is_intensity": 0


				},


				{


					"value": "Spots",


					"is_intensity": 0





				},

				{


					"value": "Peels",


					"is_intensity": 0





				},

				{


					"value": "Ruptured crust",


					"is_intensity": 0





				}


			]


		},


		{


			"title": "How is the color, size and shape of the served product pieces?",


			"select_type": 2,


			"is_intensity": 0,


			"is_nested_question": 0,


			"is_mandatory": 1,


			"option": [





				{





					"value": "Even color",


					"is_intensity": 0


				},


				{


					"value": "Uneven color",


					"is_intensity": 0





				},



				{





					"value": "Same size",


					"is_intensity": 0


				},


				{


					"value": "Different sizes",


					"is_intensity": 0





				},


				{





					"value": "Uniform shape",


					"is_intensity": 0


				},

				{





					"value": "Non uniform shape",


					"is_intensity": 0


				}





			]


		},


		{


			"title": "Press a single piece of the product between index finger and thumb with moderate force. How springy is the product?",


			"select_type": 1,


			"is_intensity": 0,


			"is_nested_question": 0,


			"is_mandatory": 1,


			"option": [





				{





					"value": "Deforms and bounces back",


					"is_intensity": 0


				},


				{


					"value": "Collapses",


					"is_intensity": 0





				},


				{





					"value": "Tears",


					"is_intensity": 0


				},

				{





					"value": "Hard",


					"is_intensity": 0


				}


			]


		},


		{


			"title": "How does the centre of the product appear from inside?  If you select \"Any other\", then please mention it in the Comment Box.",



			"subtitle": "Break the product into two.",


			"select_type": 2,


			"is_intensity": 0,


			"is_nested_question": 0,


			"is_mandatory": 1,


			"option": [




				{



					"value": "Hollow",


					"is_intensity": 0


				},


				{


					"value": "Raw",


					"is_intensity": 0





				},

				{





					"value": "Cooked",


					"is_intensity": 0


				},


				{


					"value": "Dense",


					"is_intensity": 0





				},

				{


					"value": "Dry",


					"is_intensity": 0





				},

				{


					"value": "Fluffy",


					"is_intensity": 0





				},

				{


					"value": "Moist",


					"is_intensity": 0





				},

				{


					"value": "Mushy",


					"is_intensity": 0





				},

				{


					"value": "Any other",


					"is_intensity": 0



				}





			]


		},


		{





			"title": "Overall Preference",





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


	],








	"AROMA": [


		{


			"title": "What all aromas have you sensed?",


			"subtitle": "Directly use the search box to select the aromas that you observed or follow the category based aroma list. In case you can\'t find the observed aromas, select \"Any other\" and if unable to sense any aroma at all, then select \"Absent\". If you select \"Any other\", then please mention it in the Comment Box.",


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


			"title": "If you experience any Off (bad)- aroma, then please identify it from the list.",


			"select_type": 2,


			"is_intensity": 1,


			"intensity_type": 2,


			"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense",


			"is_nested_question": 0,


			"is_mandatory": 0,


			"is_nested_option": 1,


			"nested_option_title": "OFF-AROMA",


			"nested_option_list": "OFFAROMA"


		},


		{





			"title": "Overall Preference",





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


	],





	"TASTE": [


		{


			"title": "Which Basic Taste/s have you sensed?",


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





			"title": "Which Ayurvedic Taste/s have you sensed?",




			"select_type": 2,


			"is_intensity": 0,


			"is_mandatory": 1,





			"is_nested_question": 0,





			"is_nested_option": 0,





			"option": [





				{


					"value": "Astringent (Dryness)",


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





			"title": "Overall Preference",





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


	],








	"AROMATICS TO FLAVORS": [


		{


			"title": "What all aromatics have you sensed?",


			"subtitle": "Directly use the search box to select the aromatics that you have observed or follow the category based aromatics list. In case you can\'t find the observed aromatics, select \"Any other\" and if unable to sense any aromatics at all, then select \"Absent\". If you select \"Any other\", then please mention it in the Comment Box.",


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


			"title": "If you experienced any off (bad)- aromatics, then please identify it from the list.",


			"select_type": 2,


			"is_intensity": 1,


			"intensity_type": 2,


			"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense",


			"is_nested_question": 0,


			"is_mandatory": 0,


			"is_nested_option": 1,


			"nested_option_title": "OFF-AROMATICS",


			"nested_option_list": "OFFAROMA"


		},


		{


			"title": "Aftertaste",


			"subtitle": "Please chew and swallow the product. Assess the sensation inside your mouth.",


			"is_nested_question": 1,


			"question": [


				{


					"title": "How is the aftertaste? ",


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


							"value": "None",


							"is_intensity": 0





						},


						{


							"value": "Short",


							"is_intensity": 0


						},


						{


							"value": "Sufficient",


							"is_intensity": 0


						},


						{


							"value": "Long",


							"is_intensity": 0


						}


					]


				}


			]


		},


		{


			"title": "Flavor",


			"subtitle": "Flavor is experienced only inside the mouth when the taste and aromatics (odor through the mouth) work together. Usually, taste has a lesser contribution and aromatics on the other hand has a greater contribution towards the development of the flavor.",


			"is_nested_question": 1,


			"question": [


				{


					"title": "How is the flavor experience?",


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


					"title": "Which prominent flavor/s are you able to identify?",


					"subtitle": "If you didn\'t experience any flavor, want any change in the intensity of the flavor or any other flavor factor, then please mention it in the Comment Box.",


					"select_type": 1,


					"is_intensity": 0,


					"is_nested_question": 0,


					"is_mandatory": 1,


					"option": [





						{


							"value": "Cooked",


							"is_intensity": 0





						},


						{


							"value": "Boiled",


							"is_intensity": 0


						},


						{


							"value": "Roasted",


							"is_intensity": 0


						},


						{


							"value": "Raw",


							"is_intensity": 0


						},


						{


							"value": "Baked",


							"is_intensity": 0


						},


						{


							"value": "Freshly Fried",


							"is_intensity": 0


						},


						{


							"value": "Fried",


							"is_intensity": 0


						},


						{


							"value": "None",


							"is_intensity": 0


						}


					]


				}


			]


		},


		{





			"title": "Overall Preference",


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


	],





	"TEXTURE": [



		{


			"title": "Take sufficient quantity of the product, bite the product just once then identify the sound and its intensity. How is the sound like?",


			"subtitle": "Crispy- one sound event which is sharp, clean, fast and high pitched, e.g., Chips.\nCrunchy (Crushing sound) - multiple low pitched sounds perceived as a series of small events,e.g., Rusks.\nCrackly- bite only once without grinding, it is one sudden low pitched sound event that brittles the product,e.g., puffed rice.",


			"select_type": 2,


			"is_nested_question": 0,


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


			"title": "How does the product break during the first bite?",


			"select_type": 1,


			"is_intensity": 0,


			"is_nested_question": 0,


			"is_mandatory": 1,


			"option": [





				{


					"value": "Cuts clearly",


					"is_intensity": 0





				},


				{


					"value": "Crumbles",


					"is_intensity": 0


				},


				{


					"value": "Ruptures",


					"is_intensity": 0


				},


				{


					"value": "Like rubber",


					"is_intensity": 0


				},

				{


					"value": "Hard",


					"is_intensity": 0


				}


			]


		},


		{


			"title": "First Chew",


			"subtitle": "Take sufficient quantity of the product, chew for 3-4 times and pause.",


			"is_nested_question": 1,




			"question": [


				{


					"title": "Assess the solids inside the mouth. What do you feel?",


					"select_type": 1,


					"is_intensity": 0,


					"is_nested_question": 0,


					"is_mandatory": 1,


					"option": [


						{


							"value": "Pasty",


							"is_intensity": 0


						},


						{


							"value": "Mushy",


							"is_intensity": 0


						},


						{


							"value": "Fluffy",


							"is_intensity": 1,


							"intensity_type": 2,


							"intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"


						},

						{


							"value": "Chewy",


							"is_intensity": 1,


							"intensity_type": 2,


							"intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"


						},


						{


							"value": "Coarse",


							"is_intensity": 0


						},


						{


							"value": "Hard",


							"is_intensity": 0


						}

					]


				},


				{


					"title": "While chewing, how full do you feel inside the mouth?",


					"select_type": 1,


					"is_intensity": 0,


					"is_nested_question": 0,


					"is_mandatory": 1,


					"option": [


						{


							"value": "Fullness",


							"is_intensity": 1,


							"intensity_type": 2,


							"intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"


						}

					]


				}


			]


		},


		{


			"title": "Chewdown",


			"subtitle": "Chew sufficient quantity of the product again for 8-10 times to make a pulp and pause.",


			"is_nested_question": 1,


			"question": [


				{


					"title": "Is the product sticking on the palate or teeth?",


					"select_type": 1,


					"is_intensity": 0,


					"is_nested_question": 0,


					"is_mandatory": 1,


					"option": [





						{


							"value": "Yes",


							"is_intensity": 1,


							"intensity_type": 2,


							"intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"



						},


						{


							"value": "No",


							"is_intensity": 0


						}


					]


				}




			]


		},


		{


			"title": "Residual",


			"is_nested_question": 1,


			"question": [


				{


					"title": "Do you feel anything left inside the mouth after swallowing the product?",


					"select_type": 2,


					"is_intensity": 0,


					"is_nested_question": 0,


					"is_mandatory": 1,


					"option": [





						{


							"value": "Oily Film",


							"is_intensity": 0





						},


						{


							"value": "Loose Particles",


							"is_intensity": 0


						},

						{


							"value": "Sticking on Tooth",


							"is_intensity": 0





						},


						{


							"value": "Chalky",


							"is_intensity": 0


						},

						{


							"value": "No Residue",


							"is_intensity": 0


						}


					]


				}




			]


		},


		{





			"title": "Overall Preference",


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


	],




	"PRODUCT EXPERIENCE": [


		{


			"title": "Which part of the product did you enjoy the most? If you select \"Any other\", then please mention it in the Comment Box.",


			"select_type": 1,


			"is_intensity": 0,


			"is_nested_question": 0,


			"is_mandatory": 1,


			"option": [



				{


					"value": "Outer texture",


					"is_intensity": 0


				},


				{


					"value": "Inner cooked part",


					"is_intensity": 0


				},


				{


					"value": "Seasoning",


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


			"title": "If no, which attribute/s needs improvement?",


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

        $data = ['name'=>'Cooked Starters and Sides','keywords'=>"Cooked Starters and Sides",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];
        \DB::table('global_questions')->insert($data);






    }
}
