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
        $headerInfo2 = $headerInfo2 = $headerInfo2 = [

            ['header_name'=>"INSTRUCTIONS"],


            ['header_name'=>"APPEARANCE","header_info"=>" Examine the product and assess the questions outlined below.Any attribute that stands out as either too good or too bad, may please be highlighted in the comments box at the end of the section."],


            ['header_name'=>"AROMA","header_info"=>"Aroma/s arising from the product can be traced to the ingredients and the process/es (like baking, cooking, fermentation etc.), which the product has undergone. Now bring the product closer to your nose and take a deep breath. You may also try taking 3-4 short, quick and strong sniffs, like how a dog sniffs. At this stage, we are only assessing the aroma/s (odor/s through the nose), so please don't take a bite yet. Any attribute that stands out as either too good or too bad, may please be highlighted in the comments box at the end of the section."],



            ['header_name'=>"TASTE","header_info"=>"Take a bite, eat normally and assess the taste/s and its intensity as mentioned in the section. 

What is Umami?
When the taste causes continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth and has a long lasting aftertaste.
Any attribute that stands out as either too good or too bad, may please be highlighted in the comments box at the end of the section."],



            ['header_name'=>"AROMATICS TO FLAVORS","header_info"=>"Flavor is experienced only inside the mouth when the taste and aromatics (odor through the mouth) work together. Usually, taste has a lesser contribution and aromatics on the other hand has greater contribution towards development of flavor. 
You have already identified the taste and now in this section you will be identifing aromatics.
Unlike aromas, aromatics are the odors that reach the sensors of the nose from inside the mouth ( reverse action).
Reverse Action - As we eat with our mouth closed, food releases odors. These odors are sensed by us as they travel to the back of the throat and then turn up towards the sensors of the nose.
Please take a bite again, eat normally, keeping your MOUTH CLOSED and EXHALE THROUGH THE NOSE. Identify the Odor/s inside the mouth using the same aroma list that you have already used to identify odor/s under the aroma section."],


            ['header_name'=>"ORAL TEXTURE","header_info"=>"We have covered taste and odor/s (inside and outside the mouth). Now it is the turn of ‘feel’ inside the mouth. ‘Feel’ starts when the food comes in contact with the mouth ; the ‘feel’ changes as the food is processed inside the mouth because of chewing (Applied Pressure) and the ‘feel’ may even last after the food has been swallowed. Foods when chewed may make SOUND (like chips), give us joy (like creamy foods), pain (like sticky foods) or even disgust for some (like rubbery foods -mushroom). Texture (mouthfeel) is all about the joy we get from what we eat.

Any attribute that stands out as either too good or too bad, may please be highlighted in the comments box at the end of the section."],

            ['header_name'=>"OVERALL PRODUCT EXPERIENCE","header_info"=>"RATE the overall experience of the product on the preference scale."]


        ];



        $questions2 = '{

	"INSTRUCTIONS": [

		{

			"title": "Instruction",

			"subtitle": "Please follow the questionnaire and click answers that match with your observation/s. Remember, there are no right or wrong answers. Anything that stands out as either too good or too bad, may please be highlighted in the comments box.",

			"select_type": 4

		}

	],

	"APPEARANCE": [{

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
					"value": "Slightly Hot",
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

			"title": "What is the color of the crust? If you select \"anyother\" option then please mention it in the comment box.",

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
					"value": "Light Brown",
					"is_intensity": 0

				},
				{
					"value": "Brown",
					"is_intensity": 0
				},
				{
					"value": "Any other",
					"is_intensity": 0
				}

			]

		},

		{

			"title": "Considering the product served, is there any variation in the color?",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [

				{

					"value": "Uniform",
					"is_intensity": 0
				},
				{
					"value": "Non Uniform",
					"is_intensity": 0

				}
			]
		},
		{

			"title": "How does the color appear?",

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

			"title": "Considering the product served, what is your perception about its length?",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [

				{

					"value": "Too long",
					"is_intensity": 0
				},
				{
					"value": "Too short",
					"is_intensity": 0

				},
				{
					"value": "Approriate",
					"is_intensity": 0

				}
			]
		},
		{

			"title": "Considering the product served, what is your perception about its thickness ?",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [

				{

					"value": "Too thin",
					"is_intensity": 0
				},
				{
					"value": "Too thick",
					"is_intensity": 0

				},
				{
					"value": "Approriate",
					"is_intensity": 0

				}
			]
		},
		{

			"title": "Did you experience any off appearance? If yes, then please answer the questions given below.",

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

			"title": "Did you find any of these irregularities on the surface of the served product?",

			"select_type": 2,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 0,

			"option": [

				{

					"value": "Blisters",
					"is_intensity": 0
				},
				{
					"value": "Blemishes",
					"is_intensity": 0

				},
				{

					"value": "Dark ends",
					"is_intensity": 0
				},
				{
					"value": "Black spots",
					"is_intensity": 0

				},
				{

					"value": "Peels",
					"is_intensity": 0
				},
				{
					"value": "Ruptured crust",
					"is_intensity": 0

				},
				{
					"value": "None",
					"is_intensity": 0

				}
			]
		},
		{

			"title": "Which of these irregular sizes did you spot? \nSlivers - Short and narrow \nNubbins - Lumps",

			"select_type": 2,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 0,

			"option": [

				{

					"value": "Slivers",
					"is_intensity": 0
				},
				{
					"value": "Nubbins",
					"is_intensity": 0

				},
				{

					"value": "Off cut",
					"is_intensity": 0
				},
				{
					"value": "Feathered edges",
					"is_intensity": 0

				},
				{

					"value": "Shattered",
					"is_intensity": 0
				},
				{
					"value": "Broken",
					"is_intensity": 0

				},
				{
					"value": "Ragged cuts",
					"is_intensity": 0

				},
				{
					"value": "Side cuts",
					"is_intensity": 0

				}
			]
		},
		{

			"title": "Press a single french fries between index finger and thumb with moderate force. How springy is the product?",

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

			"title": "How does the centre of the product appear?",

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

	"AROMA": [

		{

			"title": "Which all aromas did you observe? It is normal to experience multiple aromas.",

			"subtitle": "Some aromas are easy to identify. Use the search box to locate such aromas. If you can\'t find the aroma/s identified by you through the search box, then please select \"äny other\" option and mention it the comment box. Mostly however, aromas seem to be familiar but sometimes it is difficult to recall their name. In such a case, you can explore the global list of the aromas. In this list the aromas are grouped under various heads.",

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

			"title": "If you experienced any Off (bad)- aroma, please indicate the intensity. ",


			"select_type": 2,

			"is_intensity": 1,

			"intensity_type": 2,

			"intensity_value": "Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming",

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

			"is_mandatory": 0,

			"is_nested_question": 0

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
					"option": [

						{

							"value": "None",
							"is_intensity": 0
						},
						{
							"value": "Barely detectable",
							"is_intensity": 0

						},
						{

							"value": "Identifiable but not very intense",
							"is_intensity": 0
						},
						{
							"value": "Slightly intense",
							"is_intensity": 0

						},
						{

							"value": "Moderately intense",
							"is_intensity": 0
						},
						{
							"value": "Intense",
							"is_intensity": 0

						},
						{
							"value": "Very intense",
							"is_intensity": 0

						},
						{
							"value": "Extremely intense",
							"is_intensity": 0

						}
					]

				},
				{
					"title": "Salt",
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
							"value": "Barely detectable",
							"is_intensity": 0

						},
						{

							"value": "Identifiable but not very intense",
							"is_intensity": 0
						},
						{
							"value": "Slightly intense",
							"is_intensity": 0

						},
						{

							"value": "Moderately intense",
							"is_intensity": 0
						},
						{
							"value": "Intense",
							"is_intensity": 0

						},
						{
							"value": "Very intense",
							"is_intensity": 0

						},
						{
							"value": "Extremely intense",
							"is_intensity": 0

						}
					]

				},
				{
					"title": "Sour",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": [

						{

							"value": "Neutral",
							"is_intensity": 0
						},
						{
							"value": "Barely Acidic",
							"is_intensity": 0

						},
						{

							"value": "Mildly Acidic",
							"is_intensity": 0
						},
						{
							"value": "Moderately Acidic",
							"is_intensity": 0

						},
						{

							"value": "Strongly Acidic",
							"is_intensity": 0
						},
						{
							"value": "Intensely Acidic",
							"is_intensity": 0

						},
						{
							"value": "Very Intensely Acidic",
							"is_intensity": 0

						},
						{
							"value": "Extremely Acidic",
							"is_intensity": 0

						}
					]

				},
				{
					"title": "Bitter",
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
							"value": "Barely detectable",
							"is_intensity": 0

						},
						{

							"value": "Identifiable but not very intense",
							"is_intensity": 0
						},
						{
							"value": "Slightly intense",
							"is_intensity": 0

						},
						{

							"value": "Moderately intense",
							"is_intensity": 0
						},
						{
							"value": "Intense",
							"is_intensity": 0

						},
						{
							"value": "Very intense",
							"is_intensity": 0

						},
						{
							"value": "Extremely intense",
							"is_intensity": 0

						}
					]

				},
				{
					"title": "Umami",
					"subtitle": "When the taste causes continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth and has a long lasting aftertaste.",
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
							"value": "Barely detectable",
							"is_intensity": 0

						},
						{

							"value": "Identifiable but not very intense",
							"is_intensity": 0
						},
						{
							"value": "Slightly intense",
							"is_intensity": 0

						},
						{

							"value": "Moderately intense",
							"is_intensity": 0
						},
						{
							"value": "Intense",
							"is_intensity": 0

						},
						{
							"value": "Very intense",
							"is_intensity": 0

						},
						{
							"value": "Extremely intense",
							"is_intensity": 0

						}
					]

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
					"intensity_value": "None,Barely detectable,Identifiable but not very intense ,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"value": "Pungent (Spices/ Garlic)",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "None,Barely detectable,Identifiable but not very intense ,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"

				},
				{
					"value": "Pungent Cool Sensation (Mint)",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "None,Barely detectable,Identifiable but not very intense ,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"

				},
				{
					"value": "Pungent Chilli",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "None,Barely detectable,Identifiable but not very intense ,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
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

	"AROMATICS TO FLAVORS": [

		{

			"title": "Which all aromatics did you observe? It is normal to experience multiple aromatics.",

			"subtitle": "Some aromaticss are easy to identify. Use the search box to locate such aromatics. If you can\'t find the aromatic/s identified by you through the search box, then please select \"äny other\" option and mention it the comment box. Mostly however, aromatics seem to be familiar but sometimes it is difficult to recall their name. In such a case, you can explore the global list of the aromatics. In this list aromatics are grouped under various heads.",

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
			"title": "If you experienced any off (bad) aroma, please indicate the intensity.",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "Very Mild,Mild,Distinct mild,Distinct,Distinct strong,Strong,Overwhelming",
			"is_nested_question": 0,
			"is_mandatory": 0,
			"is_nested_option": 1,
			"nested_option_list": "OFFAROMA"
		},

		{

			"title": "Aftertaste",
			"subtitle": "Please chew and swallow the product. Assess the sensation inside your mouth.",
			"is_nested_question": 1,

			"question": [


				{
					"title": "How was the aftertaste? ",
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
					"title": "Length of the aftertaste?",
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
			"is_nested_question": 1,

			"question": [


				{
					"title": "Did you experience any Flavor/s.If you didn\'t experience any flavor, want any change in the intensity of the flavor or any other flavor factor, then please mention it in the comment box.",
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
							"value": "Can\'t Say",
							"is_intensity": 0

						},
						{
							"value": "Desirable flavor",
							"is_intensity": 0
						},
						{
							"value": "Undesirable flavor",
							"is_intensity": 0
						}

					]

				},
				{
					"title": "Specific to Potato which prominent flavor did you identify? ",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": [

						{

							"value": "Cooked ",
							"is_intensity": 0
						},
						{
							"value": "Boiled ",
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

			"title": "Place 3 french fries between the lips. How would you describe the surface texture?",

			"select_type": 2,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [

				{
					"value": "Dry",
					"is_intensity": 0

				},
				{
					"value": "Moist",
					"is_intensity": 0
				},
				{
					"value": "Oily",
					"is_intensity": 0

				},
				{
					"value": "Rough",
					"is_intensity": 0
				},
				{
					"value": "Smooth",
					"is_intensity": 0

				},
				{
					"value": "Crystals",
					"is_intensity": 0
				},
				{
					"value": "Sticky",
					"is_intensity": 0

				}
			]
		},

		{

			"title": "Go ahead and please bite (only once) into 3 french fries at the same time. Assess the sound and its intensity. What was the sound like? \nCrispy- one sound event- sharp, clean, fast and high pitched,e.g., Potato chips \nCrunchy - multiple low pitched sounds perceived as a series of small events (Grinding),e.g., Rusks \nCrackly- bite only once without grinding, it is one sudden low pitched sound event that brittles the product, e.g., cracker biscuits",

			"select_type": 2,

			"is_intensity": 1,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [

				{
					"value": "Crispy",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "None,Barely detectable,Identifiable but not very intense ,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"

				},
				{
					"value": "Crunchy",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "None,Barely detectable,Identifiable but not very intense ,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"value": "Crackly",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "None,Barely detectable,Identifiable but not very intense ,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"

				}
			]


		},
		{

			"title": "How did the french fries break into pieces during the first bite (fracturability)?",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [{
					"value": "Cut instantly",
					"is_intensity": 0

				},
				{
					"value": "Cut reluctantly",
					"is_intensity": 0
				},
				{
					"value": "Ruptured",
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
			"subtitle": "Chew the product for 3-4 times and pause.",
			"is_nested_question": 1,

			"question": [

				{
					"title": "Assess the moistness (moisture release) inside your mouth. What did you feel?",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": [

						{

							"value": "Dehydrated",
							"is_intensity": 0
						},
						{
							"value": "Dry",
							"is_intensity": 0

						},
						{
							"value": "Slightly Moist",
							"is_intensity": 0
						},
						{
							"value": "Moist",
							"is_intensity": 0
						},
						{
							"value": "Succulent",
							"is_intensity": 0
						}

					]

				},
				{
					"title": "Assess the solids inside the mouth? What did you feel?",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": [

						{

							"value": "Pasty ",
							"is_intensity": 0
						},
						{
							"value": "Mushy ",
							"is_intensity": 0

						},
						{

							"value": "Fluffy",
							"is_intensity": 0
						},
						{
							"value": "Chewy",
							"is_intensity": 0

						},
						{

							"value": "Coarse",
							"is_intensity": 0
						},
						{
							"value": "Hard",
							"is_intensity": 0

						},
						{
							"value": "Fried",
							"is_intensity": 0

						}
					]

				}

			]
		},
		{
			"title": "Chew down",
			"subtitle": "Chew the product again for 8-10 times to make a pulp and pause.",
			"is_nested_question": 1,

			"question": [

				{
					"title": "Was there any variation in the force required, to chew the product?",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": [

						{

							"value": "Uniform force",
							"is_intensity": 0
						},
						{
							"value": "Non uniform force",
							"is_intensity": 0

						}

					]

				},
				{
					"title": "While chewing, how much saliva was absorbed by the product?",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": [

						{

							"value": "None ",
							"is_intensity": 0
						},
						{
							"value": "Barely",
							"is_intensity": 0

						},
						{

							"value": "Very Slight",
							"is_intensity": 0
						},
						{
							"value": "Slight",
							"is_intensity": 0

						},
						{

							"value": "Moderate",
							"is_intensity": 0
						},
						{
							"value": "Plenty",
							"is_intensity": 0

						},
						{
							"value": "High",
							"is_intensity": 0

						},
						{
							"value": "Extremely High ",
							"is_intensity": 0

						}
					]

				},
				{
					"title": "During chewing, was the pulp formed or did the mass scatter?",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": [

						{

							"value": "Dissolved",
							"is_intensity": 0
						},
						{
							"value": "Scattered",
							"is_intensity": 0

						},
						{

							"value": "Pulpy mass",
							"is_intensity": 0
						},
						{
							"value": "Tightly bonded",
							"is_intensity": 0

						}
					]

				},
				{
					"title": "Is the product sticking to teeth or palate?",
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
					"title": "If yes, then how much force was needed to remove the product from the palate? ",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 0,
					"option": [

						{

							"value": "None ",
							"is_intensity": 0
						},
						{
							"value": "Barely",
							"is_intensity": 0

						},
						{

							"value": "Very Slight",
							"is_intensity": 0
						},
						{
							"value": "Slight",
							"is_intensity": 0

						},
						{

							"value": "Moderate",
							"is_intensity": 0
						},
						{
							"value": "Moderately Strong",
							"is_intensity": 0

						},
						{
							"value": "Strong",
							"is_intensity": 0

						},
						{
							"value": "Extremely Strong ",
							"is_intensity": 0

						}
					]

				}

			]
		},
		{
			"title": "Residual",
			"subtitle": "After swallowing what is the feeling inside the mouth.",
			"is_nested_question": 1,

			"question": [

				{
					"title": "Do you feel anything left in the mouth?",
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
					"title": "If yes",
					"select_type": 2,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 0,
					"option": [

						{

							"value": "Oily film ",
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

							"value": "Toothpack",
							"is_intensity": 0
						}
					]

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


	"OVERALL PRODUCT EXPERIENCE": [{

			"title": "Which part of the french fries did you enjoy the most?",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,
			"option": [

				{

					"value": "Outer crispy layer",
					"is_intensity": 0
				},
				{
					"value": "Inner cooked potato",
					"is_intensity": 0

				},
				{
					"value": "Salty crystals",
					"is_intensity": 0

				},
				{
					"value": "Seasoning",
					"is_intensity": 0

				},
				{
					"value": "None",
					"is_intensity": 0

				}

			]

		},
		{

			"title": "Does any attribute/s need/s any improvement?",

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

			"title": "If yes, which attribute/s needs improvement? Elaborate in the comment box.",

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
        
        $data = ['name'=>'french fries','keywords'=>"french fries",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];
        \DB::table('global_questions')->insert($data);






    }
}
