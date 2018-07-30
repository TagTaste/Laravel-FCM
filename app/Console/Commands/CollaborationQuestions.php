<?php

namespace App\Console\Commands;
use App\Collaborate;
use App\Company;
use App\Events\NewFeedable;
use App\Job;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class CollaborationQuestions extends Command implements ShouldQueue
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $questions = '{
	"INSTRUCTION": [{
		"title": "INSTRUCTION",
		"subtitle": "I don\'t need introduction Follow my simple instruction Wine to the left, sway to the right Drop it down low and take it back high ",
		"select_type": 4
	}],
	"APPEARANCE": [{
		"title": "Visual Observation",
		"select_type": 1,
		"is_intensity": 0,
		"is_nested": 0,
		"is_mandatory" : 1,
		"option": "Broken,Cracked,Uniform Shape"
	}, {
		"title": "Color of the mass and crust",
		"select_type": 1,
		"is_intensity": 0,
		"is_nested": 0,
		"is_mandatory": 1,
		"option": "Pale,Medium,Deep"
	}, {
		"title": "Sponginess on touching",
		"select_type": 1,
		"is_intensity": 0,
		"is_nested": 0,
		"is_mandatory": 1,
		"option": "Low,Medium,High"
	}, {
		"title": "Overall Preference (Appearance)",
		"select_type": 5,
		"is_intensity": 0,
		"is_nested": 0,
		"is_mandatory": 1,
		"option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
	}, {
		"title": "Any comments?",
		"select_type": 3,
		"is_intensity": 0,
		"is_nested": 0,
		"is_mandatory": 1
	}],
	"AROMA": [{
			"title": "Please select the Aroma that you identified",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "Low,Medium,High",
			"is_nested": 0,
			"is_mandatory": 1,
			"option": "Milky,Buttery,Fruity,Sour,Chocolate,Caramelized,Cheesy,Nutty,Vanilla,Any Other"
		},
		{
			"title": "If you felt fruity aroma, please tick",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 1,
			"is_nested": 0,
			"is_mandatory": 1,
			"intensity_value":"15",
			"nested_option" : 1,
			"option": "Vegetal,Spices,Fruits,Nuts,Floral,Animal,Caramel,Earthy,Chemical,Putrid"
		},
		{
			"title": "Overall Preference (Aroma)",
			"select_type": 5,
			"is_intensity": 0,
			"is_nested": 0,
			"is_mandatory": 1,
			"option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
		},
		{
			"title": "Any comments?",
			"select_type": 3,
			"is_intensity": 0,
			"is_nested": 0,
			"is_mandatory": 1
		}
	],
	"TASTE": [{
			"title": "What was the basic taste?",
			"select_type": 1,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "Low,Medium,High",
			"is_nested": 0,
			"is_mandatory": 1,
			"option": "Sweet,Salt,Sour,Bitter,Umami"
		},
		{
			"title": "Chemical Feeling Factor Observed?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		},
		{
			"title": "Overall Preference (Taste)",
			"select_type": 5,
			"is_intensity": 0,
			"is_nested": 0,
			"is_mandatory": 1,
			"option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
		},
		{
			"title": "Any comments?",
			"select_type": 3,
			"is_intensity": 0,
			"is_nested": 0,
			"is_mandatory": 1
		}
	],
	"AROMATICS": [{
			"title": "Feel of baked flour",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		},
		{
			"title": "Please select the Aromatics that you identified",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "Weak,Sufficient,Strong,Overwhelming",
			"is_nested": 0,
			"is_mandatory": 1,
			"option": "Eggy,Raisin,Caramelized,Vanilla,Citrus,Blueberry,Strawberry,Banana,Almond,Walnut"
		},
		{
			"title": "Overall Preference (Aromatics)",
			"select_type": 5,
			"is_intensity": 0,
			"is_nested": 0,
			"is_mandatory": 1,
			"option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
		},
		{
			"title": "Any comments?",
			"select_type": 3,
			"is_intensity": 0,
			"is_mandatory": 1,
			"is_nested": 0
		}
	],
	"TEXTURE": [{
			"title": "Surface/Mass",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested": 0,
			"is_mandatory": 1,
			"option": "Rough,Smooth,Loose Particles,Oily Lips,Moist,Wet"
		},
		{
			"title": "First Chew",
			"is_nested": 1,
			"question": [{
					"title": "Uniformity",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested": 0,
					"is_mandatory": 1,
					"option": "Low,Medium,High"
				},
				{
					"title": "Compactness",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested": 0,
					"is_mandatory": 1,
					"option": "Airy,Dense"
				},
				{
					"title": "Burst of flavour",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested": 0,
					"is_mandatory": 1,
					"option": "Low,Medium,High"
				}
			]
		},
		{

			"title": "Chewdown experience",
			"is_nested": 1,
			"question": [{
					"title": "Moisture absorption",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested": 0,
					"is_mandatory": 1,
					"option": "Low,Medium,High"
				},
				{
					"title": "Cohesiveness",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested": 0,
					"is_mandatory": 1,
					"option": "Low,Medium,High"
				}
			]
		},
		{
			"title": "Residual/After-taste (Swallow)",
			"is_nested": 1,
			"is_mandatory": 0,
			"question": [{
					"title": "Loose Particles",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested": 0,
					"is_mandatory": 1,
					"option": "Yes,No"
				},
				{
					"title": "Mouthcoating-oily/chalky, Toothstick",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested": 0,
					"is_mandatory": 1,
					"option": "Yes,No"
				}
			]
		},
		{
			"title": "Overall Preference (Appearance)",
			"select_type": 5,
			"is_intensity": 0,
			"is_nested": 0,
			"is_mandatory": 1,
			"option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
		}, {
			"title": "Any comments?",
			"select_type": 3,
			"is_intensity": 0,
			"is_mandatory": 1,
			"is_nested": 0
		}
	],
	"OVERALL PREFERENCE": [{
		"title": "Overall Product Preference",
		"select_type": 5,
		"is_intensity": 0,
		"is_nested": 0,
		"is_mandatory": 1,
		"option": "Don\'t like,Can\'t Say,Somewhat Like,Clearly Like,Love It"
	}, {
		"title": "Any comments?",
		"select_type": 3,
		"is_intensity": 0,
		"is_mandatory": 1,
		"is_nested": 0
	}]

}';
    protected $extra = array (
        0 =>
            array (
                'no' => 1.0,
                'parent_id' => 0.0,
                'categories' => 'Vegetal',
            ),
        1 =>
            array (
                'no' => 2.0,
                'parent_id' => 0.0,
                'categories' => 'Spices',
            ),
        2 =>
            array (
                'no' => 3.0,
                'parent_id' => 0.0,
                'categories' => 'Fruits',
            ),
        3 =>
            array (
                'no' => 4.0,
                'parent_id' => 0.0,
                'categories' => 'Nuts',
            ),
        4 =>
            array (
                'no' => 5.0,
                'parent_id' => 0.0,
                'categories' => 'Floral',
            ),
        5 =>
            array (
                'no' => 6.0,
                'parent_id' => 0.0,
                'categories' => 'Animal',
            ),
        6 =>
            array (
                'no' => 7.0,
                'parent_id' => 0.0,
                'categories' => 'Caramel',
            ),
        7 =>
            array (
                'no' => 8.0,
                'parent_id' => 0.0,
                'categories' => 'Earthy',
            ),
        8 =>
            array (
                'no' => 9.0,
                'parent_id' => 0.0,
                'categories' => 'Chemical',
            ),
        9 =>
            array (
                'no' => 10.0,
                'parent_id' => 0.0,
                'categories' => 'Putrid',
            ),
        10 =>
            array (
                'no' => 11.0,
                'parent_id' => 0.0,
                'categories' => 'Any Other',
            ),
        11 =>
            array (
                'no' => 12.0,
                'parent_id' => 1.0,
                'categories' => 'Vegetables',
            ),
        12 =>
            array (
                'no' => 13.0,
                'parent_id' => 1.0,
                'categories' => 'Leaves',
            ),
        13 =>
            array (
                'no' => 14.0,
                'parent_id' => 1.0,
                'categories' => 'Herbs',
            ),
        14 =>
            array (
                'no' => 15.0,
                'parent_id' => 12.0,
                'categories' => 'Dry',
            ),
        15 =>
            array (
                'no' => 16.0,
                'parent_id' => 12.0,
                'categories' => 'Fresh',
            ),
        16 =>
            array (
                'no' => 17.0,
                'parent_id' => 12.0,
                'categories' => 'Canned / Cooked',
            ),
        17 =>
            array (
                'no' => 18.0,
                'parent_id' => 15.0,
                'categories' => 'Hay / Straw',
            ),
        18 =>
            array (
                'no' => 19.0,
                'parent_id' => 15.0,
                'categories' => 'Sun Dried Tomato',
            ),
        19 =>
            array (
                'no' => 20.0,
                'parent_id' => 16.0,
                'categories' => 'Cut Green Grass',
            ),
        20 =>
            array (
                'no' => 21.0,
                'parent_id' => 16.0,
                'categories' => 'Bell Peppers',
            ),
        21 =>
            array (
                'no' => 22.0,
                'parent_id' => 16.0,
                'categories' => 'Horse Radish',
            ),
        22 =>
            array (
                'no' => 23.0,
                'parent_id' => 16.0,
                'categories' => 'Tomato',
            ),
        23 =>
            array (
                'no' => 24.0,
                'parent_id' => 16.0,
                'categories' => 'Spinach',
            ),
        24 =>
            array (
                'no' => 25.0,
                'parent_id' => 16.0,
                'categories' => 'Bottle Gourd',
            ),
        25 =>
            array (
                'no' => 26.0,
                'parent_id' => 16.0,
                'categories' => 'Pumpkin',
            ),
        26 =>
            array (
                'no' => 27.0,
                'parent_id' => 16.0,
                'categories' => 'Ash Gourd',
            ),
        27 =>
            array (
                'no' => 28.0,
                'parent_id' => 16.0,
                'categories' => 'Bitter Gourd',
            ),
        28 =>
            array (
                'no' => 29.0,
                'parent_id' => 17.0,
                'categories' => 'Green Beans',
            ),
        29 =>
            array (
                'no' => 30.0,
                'parent_id' => 17.0,
                'categories' => 'Chick Peas',
            ),
        30 =>
            array (
                'no' => 31.0,
                'parent_id' => 17.0,
                'categories' => 'Green Olive',
            ),
        31 =>
            array (
                'no' => 32.0,
                'parent_id' => 17.0,
                'categories' => 'Black Olive',
            ),
        32 =>
            array (
                'no' => 33.0,
                'parent_id' => 17.0,
                'categories' => 'Asparagus',
            ),
        33 =>
            array (
                'no' => 34.0,
                'parent_id' => 13.0,
                'categories' => 'Dry Leaves',
            ),
        34 =>
            array (
                'no' => 35.0,
                'parent_id' => 13.0,
                'categories' => 'Fresh Leaves',
            ),
        35 =>
            array (
                'no' => 36.0,
                'parent_id' => 34.0,
                'categories' => 'Bay',
            ),
        36 =>
            array (
                'no' => 37.0,
                'parent_id' => 34.0,
                'categories' => 'Tea',
            ),
        37 =>
            array (
                'no' => 38.0,
                'parent_id' => 34.0,
                'categories' => 'Stevia',
            ),
        38 =>
            array (
                'no' => 39.0,
                'parent_id' => 35.0,
                'categories' => 'Curry Leaves',
            ),
        39 =>
            array (
                'no' => 40.0,
                'parent_id' => 36.0,
                'categories' => 'Bay Leaves',
            ),
        40 =>
            array (
                'no' => 41.0,
                'parent_id' => 14.0,
                'categories' => 'Dry Herbs',
            ),
        41 =>
            array (
                'no' => 42.0,
                'parent_id' => 14.0,
                'categories' => 'Fresh Herbs',
            ),
        42 =>
            array (
                'no' => 43.0,
                'parent_id' => 41.0,
                'categories' => 'Herbal Teas',
            ),
        43 =>
            array (
                'no' => 44.0,
                'parent_id' => 41.0,
                'categories' => 'Thyme',
            ),
        44 =>
            array (
                'no' => 45.0,
                'parent_id' => 41.0,
                'categories' => 'Rosemary',
            ),
        45 =>
            array (
                'no' => 46.0,
                'parent_id' => 41.0,
                'categories' => 'Oregano',
            ),
        46 =>
            array (
                'no' => 47.0,
                'parent_id' => 41.0,
                'categories' => 'Basil',
            ),
        47 =>
            array (
                'no' => 48.0,
                'parent_id' => 41.0,
                'categories' => 'Coriander',
            ),
        48 =>
            array (
                'no' => 49.0,
                'parent_id' => 41.0,
                'categories' => 'Lemon Grass',
            ),
        49 =>
            array (
                'no' => 50.0,
                'parent_id' => 41.0,
                'categories' => 'Dill',
            ),
        50 =>
            array (
                'no' => 51.0,
                'parent_id' => 41.0,
                'categories' => 'Sage',
            ),
        51 =>
            array (
                'no' => 52.0,
                'parent_id' => 41.0,
                'categories' => 'Tarragon',
            ),
        52 =>
            array (
                'no' => 53.0,
                'parent_id' => 41.0,
                'categories' => 'Mixed Herbs',
            ),
        53 =>
            array (
                'no' => 54.0,
                'parent_id' => 41.0,
                'categories' => 'Licorice',
            ),
        54 =>
            array (
                'no' => 55.0,
                'parent_id' => 42.0,
                'categories' => 'Mint',
            ),
        55 =>
            array (
                'no' => 56.0,
                'parent_id' => 42.0,
                'categories' => 'Peppermint',
            ),
        56 =>
            array (
                'no' => 57.0,
                'parent_id' => 42.0,
                'categories' => 'Thyme',
            ),
        57 =>
            array (
                'no' => 58.0,
                'parent_id' => 42.0,
                'categories' => 'Rosemary',
            ),
        58 =>
            array (
                'no' => 59.0,
                'parent_id' => 42.0,
                'categories' => 'Oregano',
            ),
        59 =>
            array (
                'no' => 60.0,
                'parent_id' => 42.0,
                'categories' => 'Basil',
            ),
        60 =>
            array (
                'no' => 61.0,
                'parent_id' => 42.0,
                'categories' => 'Chervil',
            ),
        61 =>
            array (
                'no' => 62.0,
                'parent_id' => 42.0,
                'categories' => 'Celantro',
            ),
        62 =>
            array (
                'no' => 63.0,
                'parent_id' => 42.0,
                'categories' => 'Coriander',
            ),
        63 =>
            array (
                'no' => 64.0,
                'parent_id' => 42.0,
                'categories' => 'Parsley',
            ),
        64 =>
            array (
                'no' => 65.0,
                'parent_id' => 42.0,
                'categories' => 'Celery',
            ),
        65 =>
            array (
                'no' => 66.0,
                'parent_id' => 42.0,
                'categories' => 'Lemon Grass',
            ),
        66 =>
            array (
                'no' => 67.0,
                'parent_id' => 42.0,
                'categories' => 'Dill',
            ),
        67 =>
            array (
                'no' => 68.0,
                'parent_id' => 42.0,
                'categories' => 'Marjoram',
            ),
        68 =>
            array (
                'no' => 69.0,
                'parent_id' => 42.0,
                'categories' => 'Patchouli',
            ),
        69 =>
            array (
                'no' => 70.0,
                'parent_id' => 42.0,
                'categories' => 'Sage',
            ),
        70 =>
            array (
                'no' => 71.0,
                'parent_id' => 42.0,
                'categories' => 'Tarragon',
            ),
        71 =>
            array (
                'no' => 72.0,
                'parent_id' => 2.0,
                'categories' => 'Warming',
            ),
        72 =>
            array (
                'no' => 73.0,
                'parent_id' => 2.0,
                'categories' => 'Pungent',
            ),
        73 =>
            array (
                'no' => 74.0,
                'parent_id' => 72.0,
                'categories' => 'All Spice',
            ),
        74 =>
            array (
                'no' => 75.0,
                'parent_id' => 72.0,
                'categories' => 'Anise (Star)',
            ),
        75 =>
            array (
                'no' => 76.0,
                'parent_id' => 72.0,
                'categories' => 'Aniseed',
            ),
        76 =>
            array (
                'no' => 77.0,
                'parent_id' => 72.0,
                'categories' => 'Balsamic',
            ),
        77 =>
            array (
                'no' => 78.0,
                'parent_id' => 72.0,
                'categories' => 'Bitter Sweet',
            ),
        78 =>
            array (
                'no' => 79.0,
                'parent_id' => 72.0,
                'categories' => 'Cardamom',
            ),
        79 =>
            array (
                'no' => 80.0,
                'parent_id' => 72.0,
                'categories' => 'Cinnamon',
            ),
        80 =>
            array (
                'no' => 81.0,
                'parent_id' => 72.0,
                'categories' => 'Cloves',
            ),
        81 =>
            array (
                'no' => 82.0,
                'parent_id' => 72.0,
                'categories' => 'Cumin',
            ),
        82 =>
            array (
                'no' => 83.0,
                'parent_id' => 72.0,
                'categories' => 'Asian 5 Spice',
            ),
        83 =>
            array (
                'no' => 84.0,
                'parent_id' => 72.0,
                'categories' => 'Fresh Spice',
            ),
        84 =>
            array (
                'no' => 85.0,
                'parent_id' => 72.0,
                'categories' => 'Dry Ginger Powder',
            ),
        85 =>
            array (
                'no' => 86.0,
                'parent_id' => 72.0,
                'categories' => 'Fennel',
            ),
        86 =>
            array (
                'no' => 87.0,
                'parent_id' => 72.0,
                'categories' => 'Nutmeg',
            ),
        87 =>
            array (
                'no' => 88.0,
                'parent_id' => 72.0,
                'categories' => 'Turmeric',
            ),
        88 =>
            array (
                'no' => 89.0,
                'parent_id' => 72.0,
                'categories' => 'Sweet Spice',
            ),
        89 =>
            array (
                'no' => 90.0,
                'parent_id' => 72.0,
                'categories' => 'Zatar',
            ),
        90 =>
            array (
                'no' => 91.0,
                'parent_id' => 72.0,
                'categories' => 'Sumac',
            ),
        91 =>
            array (
                'no' => 92.0,
                'parent_id' => 72.0,
                'categories' => 'Saffron',
            ),
        92 =>
            array (
                'no' => 93.0,
                'parent_id' => 72.0,
                'categories' => 'Mustard Seeds',
            ),
        93 =>
            array (
                'no' => 94.0,
                'parent_id' => 72.0,
                'categories' => 'Black Cardamom',
            ),
        94 =>
            array (
                'no' => 95.0,
                'parent_id' => 72.0,
                'categories' => 'Ginger Fresh',
            ),
        95 =>
            array (
                'no' => 96.0,
                'parent_id' => 72.0,
                'categories' => 'Coriander Seeds',
            ),
        96 =>
            array (
                'no' => 97.0,
                'parent_id' => 72.0,
                'categories' => 'Mace',
            ),
        97 =>
            array (
                'no' => 98.0,
                'parent_id' => 72.0,
                'categories' => 'Fenugreek Seeds',
            ),
        98 =>
            array (
                'no' => 99.0,
                'parent_id' => 72.0,
                'categories' => 'Asafoetida',
            ),
        99 =>
            array (
                'no' => 100.0,
                'parent_id' => 72.0,
                'categories' => 'Dry Mango Powder',
            ),
        100 =>
            array (
                'no' => 101.0,
                'parent_id' => 72.0,
                'categories' => 'Nigella',
            ),
        101 =>
            array (
                'no' => 102.0,
                'parent_id' => 72.0,
                'categories' => 'Dry Pomegranate Powder',
            ),
        102 =>
            array (
                'no' => 103.0,
                'parent_id' => 72.0,
                'categories' => 'Poppy Seeds',
            ),
        103 =>
            array (
                'no' => 104.0,
                'parent_id' => 72.0,
                'categories' => 'Sesame Black Seeds',
            ),
        104 =>
            array (
                'no' => 105.0,
                'parent_id' => 72.0,
                'categories' => 'Sesame White',
            ),
        105 =>
            array (
                'no' => 106.0,
                'parent_id' => 72.0,
                'categories' => 'Black Salt',
            ),
        106 =>
            array (
                'no' => 107.0,
                'parent_id' => 72.0,
                'categories' => 'Pink Himalayan Salt',
            ),
        107 =>
            array (
                'no' => 108.0,
                'parent_id' => 72.0,
                'categories' => 'Sea Salt',
            ),
        108 =>
            array (
                'no' => 109.0,
                'parent_id' => 72.0,
                'categories' => 'Regular Salt',
            ),
        109 =>
            array (
                'no' => 110.0,
                'parent_id' => 72.0,
                'categories' => 'Kasuri Methi',
            ),
        110 =>
            array (
                'no' => 111.0,
                'parent_id' => 73.0,
                'categories' => 'Red Pepper Dry Whole',
            ),
        111 =>
            array (
                'no' => 112.0,
                'parent_id' => 73.0,
                'categories' => 'Red Pepper Dry Powder',
            ),
        112 =>
            array (
                'no' => 113.0,
                'parent_id' => 73.0,
                'categories' => 'Green Chilli Fresh',
            ),
        113 =>
            array (
                'no' => 114.0,
                'parent_id' => 73.0,
                'categories' => 'Black Pepper Corns',
            ),
        114 =>
            array (
                'no' => 115.0,
                'parent_id' => 73.0,
                'categories' => 'White Pepper Corns',
            ),
        115 =>
            array (
                'no' => 116.0,
                'parent_id' => 73.0,
                'categories' => 'Black Pepper Powder',
            ),
        116 =>
            array (
                'no' => 117.0,
                'parent_id' => 73.0,
                'categories' => 'Jalapeno',
            ),
        117 =>
            array (
                'no' => 118.0,
                'parent_id' => 73.0,
                'categories' => 'Yellow Chilli Powder',
            ),
        118 =>
            array (
                'no' => 119.0,
                'parent_id' => 3.0,
                'categories' => 'Citrus',
            ),
        119 =>
            array (
                'no' => 120.0,
                'parent_id' => 3.0,
                'categories' => 'Tree Fruit',
            ),
        120 =>
            array (
                'no' => 121.0,
                'parent_id' => 3.0,
                'categories' => 'Tropical Fruit',
            ),
        121 =>
            array (
                'no' => 122.0,
                'parent_id' => 3.0,
                'categories' => 'Red Fruit',
            ),
        122 =>
            array (
                'no' => 123.0,
                'parent_id' => 3.0,
                'categories' => 'Black Fruit',
            ),
        123 =>
            array (
                'no' => 124.0,
                'parent_id' => 3.0,
                'categories' => 'Green Fruit',
            ),
        124 =>
            array (
                'no' => 125.0,
                'parent_id' => 3.0,
                'categories' => 'Brown Fruit',
            ),
        125 =>
            array (
                'no' => 126.0,
                'parent_id' => 3.0,
                'categories' => 'Jams / Chutneys',
            ),
        126 =>
            array (
                'no' => 127.0,
                'parent_id' => 3.0,
                'categories' => 'Dried Fruits',
            ),
        127 =>
            array (
                'no' => 128.0,
                'parent_id' => 3.0,
                'categories' => 'Artificial Flavor / Candy',
            ),
        128 =>
            array (
                'no' => 129.0,
                'parent_id' => 119.0,
                'categories' => 'Lime',
            ),
        129 =>
            array (
                'no' => 130.0,
                'parent_id' => 119.0,
                'categories' => 'Sweet Lime',
            ),
        130 =>
            array (
                'no' => 131.0,
                'parent_id' => 119.0,
                'categories' => 'Grapefruit',
            ),
        131 =>
            array (
                'no' => 132.0,
                'parent_id' => 119.0,
                'categories' => 'Orange',
            ),
        132 =>
            array (
                'no' => 133.0,
                'parent_id' => 120.0,
                'categories' => 'Quince',
            ),
        133 =>
            array (
                'no' => 134.0,
                'parent_id' => 120.0,
                'categories' => 'Apple',
            ),
        134 =>
            array (
                'no' => 135.0,
                'parent_id' => 120.0,
                'categories' => 'Pear',
            ),
        135 =>
            array (
                'no' => 136.0,
                'parent_id' => 120.0,
                'categories' => 'Nectarine',
            ),
        136 =>
            array (
                'no' => 137.0,
                'parent_id' => 120.0,
                'categories' => 'Peach',
            ),
        137 =>
            array (
                'no' => 138.0,
                'parent_id' => 120.0,
                'categories' => 'Tangarine',
            ),
        138 =>
            array (
                'no' => 139.0,
                'parent_id' => 120.0,
                'categories' => 'Apricot',
            ),
        139 =>
            array (
                'no' => 140.0,
                'parent_id' => 120.0,
                'categories' => 'Plum',
            ),
        140 =>
            array (
                'no' => 141.0,
                'parent_id' => 120.0,
                'categories' => 'Persimmon',
            ),
        141 =>
            array (
                'no' => 142.0,
                'parent_id' => 121.0,
                'categories' => 'Pineapple',
            ),
        142 =>
            array (
                'no' => 143.0,
                'parent_id' => 121.0,
                'categories' => 'Mango',
            ),
        143 =>
            array (
                'no' => 144.0,
                'parent_id' => 121.0,
                'categories' => 'Papaya',
            ),
        144 =>
            array (
                'no' => 145.0,
                'parent_id' => 121.0,
                'categories' => 'Guava',
            ),
        145 =>
            array (
                'no' => 146.0,
                'parent_id' => 121.0,
                'categories' => 'Kiwi',
            ),
        146 =>
            array (
                'no' => 147.0,
                'parent_id' => 121.0,
                'categories' => 'Litchee',
            ),
        147 =>
            array (
                'no' => 148.0,
                'parent_id' => 121.0,
                'categories' => 'Jujube',
            ),
        148 =>
            array (
                'no' => 149.0,
                'parent_id' => 121.0,
                'categories' => 'Cape Gooseberry',
            ),
        149 =>
            array (
                'no' => 150.0,
                'parent_id' => 121.0,
                'categories' => 'Tamarind',
            ),
        150 =>
            array (
                'no' => 151.0,
                'parent_id' => 122.0,
                'categories' => 'Cranberry',
            ),
        151 =>
            array (
                'no' => 152.0,
                'parent_id' => 122.0,
                'categories' => 'Red Plum',
            ),
        152 =>
            array (
                'no' => 153.0,
                'parent_id' => 122.0,
                'categories' => 'Pomegranate',
            ),
        153 =>
            array (
                'no' => 154.0,
                'parent_id' => 122.0,
                'categories' => 'Sour Cherry',
            ),
        154 =>
            array (
                'no' => 155.0,
                'parent_id' => 122.0,
                'categories' => 'Strawberry',
            ),
        155 =>
            array (
                'no' => 156.0,
                'parent_id' => 122.0,
                'categories' => 'Cherry',
            ),
        156 =>
            array (
                'no' => 157.0,
                'parent_id' => 122.0,
                'categories' => 'Raspberry',
            ),
        157 =>
            array (
                'no' => 158.0,
                'parent_id' => 122.0,
                'categories' => 'Bubbleberry',
            ),
        158 =>
            array (
                'no' => 159.0,
                'parent_id' => 123.0,
                'categories' => 'Black Current',
            ),
        159 =>
            array (
                'no' => 160.0,
                'parent_id' => 123.0,
                'categories' => 'Black Cherry',
            ),
        160 =>
            array (
                'no' => 161.0,
                'parent_id' => 123.0,
                'categories' => 'Black Berry',
            ),
        161 =>
            array (
                'no' => 162.0,
                'parent_id' => 123.0,
                'categories' => 'Black Olive',
            ),
        162 =>
            array (
                'no' => 163.0,
                'parent_id' => 123.0,
                'categories' => 'Indian Black Berry (Jamun)',
            ),
        163 =>
            array (
                'no' => 164.0,
                'parent_id' => 123.0,
                'categories' => 'Vanilla Pods',
            ),
        164 =>
            array (
                'no' => 165.0,
                'parent_id' => 124.0,
                'categories' => 'Custard Apple',
            ),
        165 =>
            array (
                'no' => 166.0,
                'parent_id' => 124.0,
                'categories' => 'Green Olive',
            ),
        166 =>
            array (
                'no' => 167.0,
                'parent_id' => 125.0,
                'categories' => 'Dates',
            ),
        167 =>
            array (
                'no' => 168.0,
                'parent_id' => 125.0,
                'categories' => 'Tamarind Fresh',
            ),
        168 =>
            array (
                'no' => 169.0,
                'parent_id' => 126.0,
                'categories' => 'Marmalades',
            ),
        169 =>
            array (
                'no' => 170.0,
                'parent_id' => 126.0,
                'categories' => 'Mango Chutneys',
            ),
        170 =>
            array (
                'no' => 171.0,
                'parent_id' => 127.0,
                'categories' => 'Tamarind Pulp Dried',
            ),
        171 =>
            array (
                'no' => 172.0,
                'parent_id' => 127.0,
                'categories' => 'Dry Figs',
            ),
        172 =>
            array (
                'no' => 173.0,
                'parent_id' => 127.0,
                'categories' => 'Raisins',
            ),
        173 =>
            array (
                'no' => 174.0,
                'parent_id' => 127.0,
                'categories' => 'Prunes',
            ),
        174 =>
            array (
                'no' => 175.0,
                'parent_id' => 127.0,
                'categories' => 'Dry Apricots',
            ),
        175 =>
            array (
                'no' => 176.0,
                'parent_id' => 127.0,
                'categories' => 'Dehydrated Cut Apples',
            ),
        176 =>
            array (
                'no' => 177.0,
                'parent_id' => 127.0,
                'categories' => 'Other Fruits',
            ),
        177 =>
            array (
                'no' => 178.0,
                'parent_id' => 128.0,
                'categories' => 'Vanilla Essence',
            ),
        178 =>
            array (
                'no' => 179.0,
                'parent_id' => 128.0,
                'categories' => 'Lemon Essence',
            ),
        179 =>
            array (
                'no' => 180.0,
                'parent_id' => 128.0,
                'categories' => 'Mango Essence',
            ),
        180 =>
            array (
                'no' => 181.0,
                'parent_id' => 128.0,
                'categories' => 'Black Current Essence',
            ),
        181 =>
            array (
                'no' => 182.0,
                'parent_id' => 4.0,
                'categories' => 'Almonds',
            ),
        182 =>
            array (
                'no' => 183.0,
                'parent_id' => 4.0,
                'categories' => 'Walnuts',
            ),
        183 =>
            array (
                'no' => 184.0,
                'parent_id' => 4.0,
                'categories' => 'Hazelnuts',
            ),
        184 =>
            array (
                'no' => 185.0,
                'parent_id' => 4.0,
                'categories' => 'Pinenuts',
            ),
        185 =>
            array (
                'no' => 186.0,
                'parent_id' => 4.0,
                'categories' => 'Cashewnuts',
            ),
        186 =>
            array (
                'no' => 187.0,
                'parent_id' => 4.0,
                'categories' => 'Dried Coconut',
            ),
        187 =>
            array (
                'no' => 188.0,
                'parent_id' => 4.0,
                'categories' => 'Fresh Coconut',
            ),
        188 =>
            array (
                'no' => 189.0,
                'parent_id' => 5.0,
                'categories' => 'Dry Flowers',
            ),
        189 =>
            array (
                'no' => 190.0,
                'parent_id' => 5.0,
                'categories' => 'Fresh Flowers',
            ),
        190 =>
            array (
                'no' => 191.0,
                'parent_id' => 5.0,
                'categories' => 'Essence',
            ),
        191 =>
            array (
                'no' => 192.0,
                'parent_id' => 189.0,
                'categories' => 'Jasmine',
            ),
        192 =>
            array (
                'no' => 193.0,
                'parent_id' => 189.0,
                'categories' => 'Camomile',
            ),
        193 =>
            array (
                'no' => 194.0,
                'parent_id' => 189.0,
                'categories' => 'Lavender',
            ),
        194 =>
            array (
                'no' => 195.0,
                'parent_id' => 189.0,
                'categories' => 'Honeysuckle',
            ),
        195 =>
            array (
                'no' => 196.0,
                'parent_id' => 189.0,
                'categories' => 'Orange Blossom',
            ),
        196 =>
            array (
                'no' => 197.0,
                'parent_id' => 189.0,
                'categories' => 'Rose',
            ),
        197 =>
            array (
                'no' => 198.0,
                'parent_id' => 189.0,
                'categories' => 'Kewra',
            ),
        198 =>
            array (
                'no' => 199.0,
                'parent_id' => 189.0,
                'categories' => 'Acacia',
            ),
        199 =>
            array (
                'no' => 200.0,
                'parent_id' => 189.0,
                'categories' => 'Violet',
            ),
        200 =>
            array (
                'no' => 201.0,
                'parent_id' => 191.0,
                'categories' => 'Jasmine',
            ),
        201 =>
            array (
                'no' => 202.0,
                'parent_id' => 191.0,
                'categories' => 'Rose',
            ),
        202 =>
            array (
                'no' => 203.0,
                'parent_id' => 191.0,
                'categories' => 'Kewra',
            ),
        203 =>
            array (
                'no' => 204.0,
                'parent_id' => 6.0,
                'categories' => 'Animal',
            ),
        204 =>
            array (
                'no' => 205.0,
                'parent_id' => 6.0,
                'categories' => 'Meat',
            ),
        205 =>
            array (
                'no' => 206.0,
                'parent_id' => 6.0,
                'categories' => 'Acquatic',
            ),
        206 =>
            array (
                'no' => 207.0,
                'parent_id' => 6.0,
                'categories' => 'Poultry',
            ),
        207 =>
            array (
                'no' => 208.0,
                'parent_id' => 6.0,
                'categories' => 'Dairy',
            ),
        208 =>
            array (
                'no' => 209.0,
                'parent_id' => 204.0,
                'categories' => 'Wet Dog',
            ),
        209 =>
            array (
                'no' => 210.0,
                'parent_id' => 204.0,
                'categories' => 'Urine',
            ),
        210 =>
            array (
                'no' => 211.0,
                'parent_id' => 204.0,
                'categories' => 'Fecal',
            ),
        211 =>
            array (
                'no' => 212.0,
                'parent_id' => 204.0,
                'categories' => 'Barnyard',
            ),
        212 =>
            array (
                'no' => 213.0,
                'parent_id' => 204.0,
                'categories' => 'Horse',
            ),
        213 =>
            array (
                'no' => 214.0,
                'parent_id' => 204.0,
                'categories' => 'Leather',
            ),
        214 =>
            array (
                'no' => 215.0,
                'parent_id' => 204.0,
                'categories' => 'Cow',
            ),
        215 =>
            array (
                'no' => 216.0,
                'parent_id' => 205.0,
                'categories' => 'Raw',
            ),
        216 =>
            array (
                'no' => 217.0,
                'parent_id' => 205.0,
                'categories' => 'Dry',
            ),
        217 =>
            array (
                'no' => 218.0,
                'parent_id' => 205.0,
                'categories' => 'Cooked',
            ),
        218 =>
            array (
                'no' => 219.0,
                'parent_id' => 205.0,
                'categories' => 'Cured',
            ),
        219 =>
            array (
                'no' => 220.0,
                'parent_id' => 216.0,
                'categories' => 'Mutton',
            ),
        220 =>
            array (
                'no' => 221.0,
                'parent_id' => 216.0,
                'categories' => 'Lamb',
            ),
        221 =>
            array (
                'no' => 222.0,
                'parent_id' => 217.0,
                'categories' => 'Bacon',
            ),
        222 =>
            array (
                'no' => 223.0,
                'parent_id' => 218.0,
                'categories' => 'Broth',
            ),
        223 =>
            array (
                'no' => 224.0,
                'parent_id' => 218.0,
                'categories' => 'Lamb',
            ),
        224 =>
            array (
                'no' => 225.0,
                'parent_id' => 218.0,
                'categories' => 'Mutton',
            ),
        225 =>
            array (
                'no' => 226.0,
                'parent_id' => 218.0,
                'categories' => 'Grilled',
            ),
        226 =>
            array (
                'no' => 227.0,
                'parent_id' => 218.0,
                'categories' => 'Smoked',
            ),
        227 =>
            array (
                'no' => 228.0,
                'parent_id' => 219.0,
                'categories' => 'Salami',
            ),
        228 =>
            array (
                'no' => 229.0,
                'parent_id' => 219.0,
                'categories' => 'Sausages',
            ),
        229 =>
            array (
                'no' => 230.0,
                'parent_id' => 206.0,
                'categories' => 'Fish',
            ),
        230 =>
            array (
                'no' => 231.0,
                'parent_id' => 206.0,
                'categories' => 'Prawns',
            ),
        231 =>
            array (
                'no' => 232.0,
                'parent_id' => 207.0,
                'categories' => 'Chicken',
            ),
        232 =>
            array (
                'no' => 233.0,
                'parent_id' => 207.0,
                'categories' => 'Eggs',
            ),
        233 =>
            array (
                'no' => 234.0,
                'parent_id' => 208.0,
                'categories' => 'Milk',
            ),
        234 =>
            array (
                'no' => 235.0,
                'parent_id' => 208.0,
                'categories' => 'Curds',
            ),
        235 =>
            array (
                'no' => 236.0,
                'parent_id' => 208.0,
                'categories' => 'Butter',
            ),
        236 =>
            array (
                'no' => 237.0,
                'parent_id' => 208.0,
                'categories' => 'Whey',
            ),
        237 =>
            array (
                'no' => 238.0,
                'parent_id' => 208.0,
                'categories' => 'Cream',
            ),
        238 =>
            array (
                'no' => 239.0,
                'parent_id' => 208.0,
                'categories' => 'Cheese',
            ),
        239 =>
            array (
                'no' => 240.0,
                'parent_id' => 234.0,
                'categories' => 'Fresh Milk',
            ),
        240 =>
            array (
                'no' => 241.0,
                'parent_id' => 234.0,
                'categories' => 'Sour Milk',
            ),
        241 =>
            array (
                'no' => 242.0,
                'parent_id' => 234.0,
                'categories' => 'Boiled Milk',
            ),
        242 =>
            array (
                'no' => 243.0,
                'parent_id' => 234.0,
                'categories' => 'Caramelised / Condensed Milk',
            ),
        243 =>
            array (
                'no' => 244.0,
                'parent_id' => 234.0,
                'categories' => 'Butter Milk',
            ),
        244 =>
            array (
                'no' => 245.0,
                'parent_id' => 235.0,
                'categories' => 'Lassi',
            ),
        245 =>
            array (
                'no' => 246.0,
                'parent_id' => 235.0,
                'categories' => 'Curds',
            ),
        246 =>
            array (
                'no' => 247.0,
                'parent_id' => 235.0,
                'categories' => 'Acidified Curd',
            ),
        247 =>
            array (
                'no' => 248.0,
                'parent_id' => 235.0,
                'categories' => 'Yogurt',
            ),
        248 =>
            array (
                'no' => 249.0,
                'parent_id' => 236.0,
                'categories' => 'Fresh Butter',
            ),
        249 =>
            array (
                'no' => 250.0,
                'parent_id' => 236.0,
                'categories' => 'Melted Butter',
            ),
        250 =>
            array (
                'no' => 251.0,
                'parent_id' => 236.0,
                'categories' => 'Rancid Butter',
            ),
        251 =>
            array (
                'no' => 252.0,
                'parent_id' => 236.0,
                'categories' => 'Clarified Butter',
            ),
        252 =>
            array (
                'no' => 253.0,
                'parent_id' => 238.0,
                'categories' => 'Fresh Cream',
            ),
        253 =>
            array (
                'no' => 254.0,
                'parent_id' => 238.0,
                'categories' => 'Clotted Cream',
            ),
        254 =>
            array (
                'no' => 255.0,
                'parent_id' => 238.0,
                'categories' => 'Cultured Cream',
            ),
        255 =>
            array (
                'no' => 256.0,
                'parent_id' => 239.0,
                'categories' => 'Cottage Cheese',
            ),
        256 =>
            array (
                'no' => 257.0,
                'parent_id' => 239.0,
                'categories' => 'Mozzarella',
            ),
        257 =>
            array (
                'no' => 258.0,
                'parent_id' => 239.0,
                'categories' => 'Blue Cheese',
            ),
        258 =>
            array (
                'no' => 259.0,
                'parent_id' => 239.0,
                'categories' => 'Cheddar',
            ),
        259 =>
            array (
                'no' => 260.0,
                'parent_id' => 239.0,
                'categories' => 'Cheese Rind',
            ),
        260 =>
            array (
                'no' => 261.0,
                'parent_id' => 239.0,
                'categories' => 'Cancoillotte',
            ),
    )  ;


    protected $header = [];
    protected $signature = 'Collaboration:Question {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'insert question in collaborate_tasting_questions';

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
        $id = $this->argument('id');
        $questions = $this->questions;
        $questions = json_decode($questions,true);
        $data = [];
        $data[] = ['header_type'=>'INSTRUCTION','is_active'=>1,'collaborate_id'=>$id,'header_info'=>'In information technology, header refers to supplemental data placed at the '];
        $data[] = ['header_type'=>'APPEARANCE','is_active'=>1,'collaborate_id'=>$id,'header_info'=>'In information technology, header refers to supplemental data placed at the '];
        $data[] = ['header_type'=>'AROMA','is_active'=>1,'collaborate_id'=>$id,'header_info'=>'In information technology, header refers to supplemental data placed at the '];
        $data[] = ['header_type'=>'TASTE','is_active'=>1,'collaborate_id'=>$id,'header_info'=>'In information technology, header refers to supplemental data placed at the '];
        $data[] = ['header_type'=>'AROMATICS','is_active'=>1,'collaborate_id'=>$id,'header_info'=>'In information technology, header refers to supplemental data placed at the '];
        $data[] = ['header_type'=>'TEXTURE','is_active'=>1,'collaborate_id'=>$id,'header_info'=>'In information technology, header refers to supplemental data placed at the beginning of a block of data being stored or transmitted. In data transmission, the data following the header are sometimes called the payload or body.'];
        $data[] = ['header_type'=>'OVERALL PREFERENCE','is_active'=>1,'collaborate_id'=>$id,'header_info'=>'In information technology, header refers to supplemental data placed at the beginning of a block of data being stored or transmitted. In data transmission, the data following the header are sometimes called the payload or body.'];


        $this->model = Collaborate\ReviewHeader::insert($data);
        $collaborateId = $id;
        foreach ($questions as $key=>$question)
        {
            $data = [];
            $header = \DB::table('collaborate_tasting_header')->select('id')->where('header_type','=',$key)
                ->where('collaborate_id',$collaborateId)->first();
            $headerId = $header->id;
            foreach ($question as $item)
            {
                $subtitle = isset($item['subtitle']) ? $item['subtitle'] : null;
                $subquestions = isset($item['question']) ? $item['question'] : [];
                $isNested = isset($item['is_nested']) && $item['is_nested'] == 1 ? 1 : 0;
                $isMandatory = isset($item['is_mandatory']) && $item['is_mandatory'] == 1 ? 1 : 0;
                unset($item['question']);
                $data = ['title'=>$item['title'],'subtitle'=>$subtitle,'is_nested'=>$isNested,'questions'=>json_encode($item,true),'parent_question_id'=>null,
                        'header_type_id'=>$headerId,'is_mandatory'=>$isMandatory,'is_active','collaborate_id'=>$collaborateId];

                $x = Collaborate\Questions::create($data);
                \Log::info("here x");
                \Log::info($x);
                $nestedOption = json_decode($x->questions);
                $extraQuestion = [];
                if(isset($nestedOption->nested_option))
                {
                    if($nestedOption->nested_option)
                    {
                        \Log::info("here extra");
                        \Log::info($this->extra);
                        foreach ($this->extra as $nested)
                        {
                            $parentId = $nested['parent_id'] == 0 ? null : $nested['parent_id'];
                            $extraQuestion[] = ["sequence_id"=>$nested['no'],'parent_id'=>$parentId,'value'=>$nested['categories'],'question_id'=>$x->id,'is_active'=>1,
                                'collaborate_id'=>$collaborateId,'header_type_id'=>$headerId];
                        }
                        $this->model = \DB::table('collaborate_tasting_nested_question')->insert($extraQuestion);

                        $questions = \DB::table('collaborate_tasting_nested_question')->where('question_id',$x->id)->where('collaborate_id',$collaborateId)->get();

                        foreach ($questions as $question)
                        {
                            $checknested = \DB::table('collaborate_tasting_nested_question')->where('question_id',$x->id)->where('collaborate_id',$collaborateId)
                                ->where('parent_id',$question->sequence_id)->exists();
                            if($checknested)
                            {
                                \DB::table('collaborate_tasting_nested_question')->where('question_id',$x->id)->where('collaborate_id',$collaborateId)
                                    ->where('id',$question->id)->update(['nested_option'=>1]);
                            }

                        }
                    }
                }

                foreach ($subquestions as $subquestion)
                {
                    $subtitle = isset($subquestion['subtitle']) ? $subquestion['subtitle'] : null;
                    $isNested = isset($subquestion['is_nested']) && $subquestion['is_nested'] == 1 ? 1 : 0;
                    $isMandatory = isset($subquestion['is_mandatory']) && $subquestion['is_mandatory'] == 1 ? 1 : 0;
                    unset($subquestion['question']);
                    $subData = ['title'=>$subquestion['title'],'subtitle'=>$subtitle,'is_nested'=>$isNested,'questions'=>json_encode($subquestion,true),'parent_question_id'=>$x->id,
                        'header_type_id'=>$headerId,'is_mandatory'=>$isMandatory,'is_active','collaborate_id'=>$collaborateId];
                    Collaborate\Questions::create($subData);

                }
            }
        }
    }
}

