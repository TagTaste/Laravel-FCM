<?php

use Illuminate\Database\Seeder;

class GlobalNestedOptionUploadTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $extra = array (
        0 =>
            array (
                's_no' => 1.0,
                'parent_id' => 0.0,
                'categories' => 'Vegetal',
            ),
        1 =>
            array (
                's_no' => 2.0,
                'parent_id' => 0.0,
                'categories' => 'Spices',
            ),
        2 =>
            array (
                's_no' => 3.0,
                'parent_id' => 0.0,
                'categories' => 'Fruits',
            ),
        3 =>
            array (
                's_no' => 4.0,
                'parent_id' => 0.0,
                'categories' => 'Nuts',
            ),
        4 =>
            array (
                's_no' => 5.0,
                'parent_id' => 0.0,
                'categories' => 'Floral',
            ),
        5 =>
            array (
                's_no' => 6.0,
                'parent_id' => 0.0,
                'categories' => 'Animal',
            ),
        6 =>
            array (
                's_no' => 7.0,
                'parent_id' => 0.0,
                'categories' => 'Caramel',
            ),
        7 =>
            array (
                's_no' => 8.0,
                'parent_id' => 0.0,
                'categories' => 'Earthy',
            ),
        8 =>
            array (
                's_no' => 9.0,
                'parent_id' => 0.0,
                'categories' => 'Chemical',
            ),
        9 =>
            array (
                's_no' => 10.0,
                'parent_id' => 0.0,
                'categories' => 'Putrid',
            ),
        10 =>
            array (
                's_no' => 11.0,
                'parent_id' => 0.0,
                'categories' => 'Any Other',
            ),
        11 =>
            array (
                's_no' => 12.0,
                'parent_id' => 1.0,
                'categories' => 'Vegetables',
            ),
        12 =>
            array (
                's_no' => 13.0,
                'parent_id' => 1.0,
                'categories' => 'Leaves',
            ),
        13 =>
            array (
                's_no' => 14.0,
                'parent_id' => 1.0,
                'categories' => 'Herbs',
            ),
        14 =>
            array (
                's_no' => 15.0,
                'parent_id' => 12.0,
                'categories' => 'Dry',
            ),
        15 =>
            array (
                's_no' => 16.0,
                'parent_id' => 12.0,
                'categories' => 'Fresh',
            ),
        16 =>
            array (
                's_no' => 17.0,
                'parent_id' => 12.0,
                'categories' => 'Canned / Cooked',
            ),
        17 =>
            array (
                's_no' => 18.0,
                'parent_id' => 15.0,
                'categories' => 'Hay / Straw',
            ),
        18 =>
            array (
                's_no' => 19.0,
                'parent_id' => 15.0,
                'categories' => 'Sun Dried Tomato',
            ),
        19 =>
            array (
                's_no' => 20.0,
                'parent_id' => 16.0,
                'categories' => 'Cut Green Grass',
            ),
        20 =>
            array (
                's_no' => 21.0,
                'parent_id' => 16.0,
                'categories' => 'Bell Peppers',
            ),
        21 =>
            array (
                's_no' => 22.0,
                'parent_id' => 16.0,
                'categories' => 'Horse Radish',
            ),
        22 =>
            array (
                's_no' => 23.0,
                'parent_id' => 16.0,
                'categories' => 'Tomato',
            ),
        23 =>
            array (
                's_no' => 24.0,
                'parent_id' => 16.0,
                'categories' => 'Spinach',
            ),
        24 =>
            array (
                's_no' => 25.0,
                'parent_id' => 16.0,
                'categories' => 'Bottle Gourd',
            ),
        25 =>
            array (
                's_no' => 26.0,
                'parent_id' => 16.0,
                'categories' => 'Pumpkin',
            ),
        26 =>
            array (
                's_no' => 27.0,
                'parent_id' => 16.0,
                'categories' => 'Ash Gourd',
            ),
        27 =>
            array (
                's_no' => 28.0,
                'parent_id' => 16.0,
                'categories' => 'Bitter Gourd',
            ),
        28 =>
            array (
                's_no' => 29.0,
                'parent_id' => 17.0,
                'categories' => 'Green Beans',
            ),
        29 =>
            array (
                's_no' => 30.0,
                'parent_id' => 17.0,
                'categories' => 'Chick Peas',
            ),
        30 =>
            array (
                's_no' => 31.0,
                'parent_id' => 17.0,
                'categories' => 'Green Olive',
            ),
        31 =>
            array (
                's_no' => 32.0,
                'parent_id' => 17.0,
                'categories' => 'Black Olive',
            ),
        32 =>
            array (
                's_no' => 33.0,
                'parent_id' => 17.0,
                'categories' => 'Asparagus',
            ),
        33 =>
            array (
                's_no' => 34.0,
                'parent_id' => 13.0,
                'categories' => 'Dry Leaves',
            ),
        34 =>
            array (
                's_no' => 35.0,
                'parent_id' => 13.0,
                'categories' => 'Fresh Leaves',
            ),
        35 =>
            array (
                's_no' => 36.0,
                'parent_id' => 34.0,
                'categories' => 'Bay',
            ),
        36 =>
            array (
                's_no' => 37.0,
                'parent_id' => 34.0,
                'categories' => 'Tea',
            ),
        37 =>
            array (
                's_no' => 38.0,
                'parent_id' => 34.0,
                'categories' => 'Stevia',
            ),
        38 =>
            array (
                's_no' => 39.0,
                'parent_id' => 35.0,
                'categories' => 'Curry Leaves',
            ),
        39 =>
            array (
                's_no' => 40.0,
                'parent_id' => 36.0,
                'categories' => 'Bay Leaves',
            ),
        40 =>
            array (
                's_no' => 41.0,
                'parent_id' => 14.0,
                'categories' => 'Dry Herbs',
            ),
        41 =>
            array (
                's_no' => 42.0,
                'parent_id' => 14.0,
                'categories' => 'Fresh Herbs',
            ),
        42 =>
            array (
                's_no' => 43.0,
                'parent_id' => 41.0,
                'categories' => 'Herbal Teas',
            ),
        43 =>
            array (
                's_no' => 44.0,
                'parent_id' => 41.0,
                'categories' => 'Thyme',
            ),
        44 =>
            array (
                's_no' => 45.0,
                'parent_id' => 41.0,
                'categories' => 'Rosemary',
            ),
        45 =>
            array (
                's_no' => 46.0,
                'parent_id' => 41.0,
                'categories' => 'Oregas_no',
            ),
        46 =>
            array (
                's_no' => 47.0,
                'parent_id' => 41.0,
                'categories' => 'Basil',
            ),
        47 =>
            array (
                's_no' => 48.0,
                'parent_id' => 41.0,
                'categories' => 'Coriander',
            ),
        48 =>
            array (
                's_no' => 49.0,
                'parent_id' => 41.0,
                'categories' => 'Lemon Grass',
            ),
        49 =>
            array (
                's_no' => 50.0,
                'parent_id' => 41.0,
                'categories' => 'Dill',
            ),
        50 =>
            array (
                's_no' => 51.0,
                'parent_id' => 41.0,
                'categories' => 'Sage',
            ),
        51 =>
            array (
                's_no' => 52.0,
                'parent_id' => 41.0,
                'categories' => 'Tarragon',
            ),
        52 =>
            array (
                's_no' => 53.0,
                'parent_id' => 41.0,
                'categories' => 'Mixed Herbs',
            ),
        53 =>
            array (
                's_no' => 54.0,
                'parent_id' => 41.0,
                'categories' => 'Licorice',
            ),
        54 =>
            array (
                's_no' => 55.0,
                'parent_id' => 42.0,
                'categories' => 'Mint',
            ),
        55 =>
            array (
                's_no' => 56.0,
                'parent_id' => 42.0,
                'categories' => 'Peppermint',
            ),
        56 =>
            array (
                's_no' => 57.0,
                'parent_id' => 42.0,
                'categories' => 'Thyme',
            ),
        57 =>
            array (
                's_no' => 58.0,
                'parent_id' => 42.0,
                'categories' => 'Rosemary',
            ),
        58 =>
            array (
                's_no' => 59.0,
                'parent_id' => 42.0,
                'categories' => 'Oregas_no',
            ),
        59 =>
            array (
                's_no' => 60.0,
                'parent_id' => 42.0,
                'categories' => 'Basil',
            ),
        60 =>
            array (
                's_no' => 61.0,
                'parent_id' => 42.0,
                'categories' => 'Chervil',
            ),
        61 =>
            array (
                's_no' => 62.0,
                'parent_id' => 42.0,
                'categories' => 'Celantro',
            ),
        62 =>
            array (
                's_no' => 63.0,
                'parent_id' => 42.0,
                'categories' => 'Coriander',
            ),
        63 =>
            array (
                's_no' => 64.0,
                'parent_id' => 42.0,
                'categories' => 'Parsley',
            ),
        64 =>
            array (
                's_no' => 65.0,
                'parent_id' => 42.0,
                'categories' => 'Celery',
            ),
        65 =>
            array (
                's_no' => 66.0,
                'parent_id' => 42.0,
                'categories' => 'Lemon Grass',
            ),
        66 =>
            array (
                's_no' => 67.0,
                'parent_id' => 42.0,
                'categories' => 'Dill',
            ),
        67 =>
            array (
                's_no' => 68.0,
                'parent_id' => 42.0,
                'categories' => 'Marjoram',
            ),
        68 =>
            array (
                's_no' => 69.0,
                'parent_id' => 42.0,
                'categories' => 'Patchouli',
            ),
        69 =>
            array (
                's_no' => 70.0,
                'parent_id' => 42.0,
                'categories' => 'Sage',
            ),
        70 =>
            array (
                's_no' => 71.0,
                'parent_id' => 42.0,
                'categories' => 'Tarragon',
            ),
        71 =>
            array (
                's_no' => 72.0,
                'parent_id' => 2.0,
                'categories' => 'Warming',
            ),
        72 =>
            array (
                's_no' => 73.0,
                'parent_id' => 2.0,
                'categories' => 'Pungent',
            ),
        73 =>
            array (
                's_no' => 74.0,
                'parent_id' => 72.0,
                'categories' => 'All Spice',
            ),
        74 =>
            array (
                's_no' => 75.0,
                'parent_id' => 72.0,
                'categories' => 'Anise (Star)',
            ),
        75 =>
            array (
                's_no' => 76.0,
                'parent_id' => 72.0,
                'categories' => 'Aniseed',
            ),
        76 =>
            array (
                's_no' => 77.0,
                'parent_id' => 72.0,
                'categories' => 'Balsamic',
            ),
        77 =>
            array (
                's_no' => 78.0,
                'parent_id' => 72.0,
                'categories' => 'Bitter Sweet',
            ),
        78 =>
            array (
                's_no' => 79.0,
                'parent_id' => 72.0,
                'categories' => 'Cardamom',
            ),
        79 =>
            array (
                's_no' => 80.0,
                'parent_id' => 72.0,
                'categories' => 'Cinnamon',
            ),
        80 =>
            array (
                's_no' => 81.0,
                'parent_id' => 72.0,
                'categories' => 'Cloves',
            ),
        81 =>
            array (
                's_no' => 82.0,
                'parent_id' => 72.0,
                'categories' => 'Cumin',
            ),
        82 =>
            array (
                's_no' => 83.0,
                'parent_id' => 72.0,
                'categories' => 'Asian 5 Spice',
            ),
        83 =>
            array (
                's_no' => 84.0,
                'parent_id' => 72.0,
                'categories' => 'Fresh Spice',
            ),
        84 =>
            array (
                's_no' => 85.0,
                'parent_id' => 72.0,
                'categories' => 'Dry Ginger Powder',
            ),
        85 =>
            array (
                's_no' => 86.0,
                'parent_id' => 72.0,
                'categories' => 'Fennel',
            ),
        86 =>
            array (
                's_no' => 87.0,
                'parent_id' => 72.0,
                'categories' => 'Nutmeg',
            ),
        87 =>
            array (
                's_no' => 88.0,
                'parent_id' => 72.0,
                'categories' => 'Turmeric',
            ),
        88 =>
            array (
                's_no' => 89.0,
                'parent_id' => 72.0,
                'categories' => 'Sweet Spice',
            ),
        89 =>
            array (
                's_no' => 90.0,
                'parent_id' => 72.0,
                'categories' => 'Zatar',
            ),
        90 =>
            array (
                's_no' => 91.0,
                'parent_id' => 72.0,
                'categories' => 'Sumac',
            ),
        91 =>
            array (
                's_no' => 92.0,
                'parent_id' => 72.0,
                'categories' => 'Saffron',
            ),
        92 =>
            array (
                's_no' => 93.0,
                'parent_id' => 72.0,
                'categories' => 'Mustard Seeds',
            ),
        93 =>
            array (
                's_no' => 94.0,
                'parent_id' => 72.0,
                'categories' => 'Black Cardamom',
            ),
        94 =>
            array (
                's_no' => 95.0,
                'parent_id' => 72.0,
                'categories' => 'Ginger Fresh',
            ),
        95 =>
            array (
                's_no' => 96.0,
                'parent_id' => 72.0,
                'categories' => 'Coriander Seeds',
            ),
        96 =>
            array (
                's_no' => 97.0,
                'parent_id' => 72.0,
                'categories' => 'Mace',
            ),
        97 =>
            array (
                's_no' => 98.0,
                'parent_id' => 72.0,
                'categories' => 'Fenugreek Seeds',
            ),
        98 =>
            array (
                's_no' => 99.0,
                'parent_id' => 72.0,
                'categories' => 'Asafoetida',
            ),
        99 =>
            array (
                's_no' => 100.0,
                'parent_id' => 72.0,
                'categories' => 'Dry Mango Powder',
            ),
        100 =>
            array (
                's_no' => 101.0,
                'parent_id' => 72.0,
                'categories' => 'Nigella',
            ),
        101 =>
            array (
                's_no' => 102.0,
                'parent_id' => 72.0,
                'categories' => 'Dry Pomegranate Powder',
            ),
        102 =>
            array (
                's_no' => 103.0,
                'parent_id' => 72.0,
                'categories' => 'Poppy Seeds',
            ),
        103 =>
            array (
                's_no' => 104.0,
                'parent_id' => 72.0,
                'categories' => 'Sesame Black Seeds',
            ),
        104 =>
            array (
                's_no' => 105.0,
                'parent_id' => 72.0,
                'categories' => 'Sesame White',
            ),
        105 =>
            array (
                's_no' => 106.0,
                'parent_id' => 72.0,
                'categories' => 'Black Salt',
            ),
        106 =>
            array (
                's_no' => 107.0,
                'parent_id' => 72.0,
                'categories' => 'Pink Himalayan Salt',
            ),
        107 =>
            array (
                's_no' => 108.0,
                'parent_id' => 72.0,
                'categories' => 'Sea Salt',
            ),
        108 =>
            array (
                's_no' => 109.0,
                'parent_id' => 72.0,
                'categories' => 'Regular Salt',
            ),
        109 =>
            array (
                's_no' => 110.0,
                'parent_id' => 72.0,
                'categories' => 'Kasuri Methi',
            ),
        110 =>
            array (
                's_no' => 111.0,
                'parent_id' => 73.0,
                'categories' => 'Red Pepper Dry Whole',
            ),
        111 =>
            array (
                's_no' => 112.0,
                'parent_id' => 73.0,
                'categories' => 'Red Pepper Dry Powder',
            ),
        112 =>
            array (
                's_no' => 113.0,
                'parent_id' => 73.0,
                'categories' => 'Green Chilli Fresh',
            ),
        113 =>
            array (
                's_no' => 114.0,
                'parent_id' => 73.0,
                'categories' => 'Black Pepper Corns',
            ),
        114 =>
            array (
                's_no' => 115.0,
                'parent_id' => 73.0,
                'categories' => 'White Pepper Corns',
            ),
        115 =>
            array (
                's_no' => 116.0,
                'parent_id' => 73.0,
                'categories' => 'Black Pepper Powder',
            ),
        116 =>
            array (
                's_no' => 117.0,
                'parent_id' => 73.0,
                'categories' => 'Jalapes_no',
            ),
        117 =>
            array (
                's_no' => 118.0,
                'parent_id' => 73.0,
                'categories' => 'Yellow Chilli Powder',
            ),
        118 =>
            array (
                's_no' => 119.0,
                'parent_id' => 3.0,
                'categories' => 'Citrus',
            ),
        119 =>
            array (
                's_no' => 120.0,
                'parent_id' => 3.0,
                'categories' => 'Tree Fruit',
            ),
        120 =>
            array (
                's_no' => 121.0,
                'parent_id' => 3.0,
                'categories' => 'Tropical Fruit',
            ),
        121 =>
            array (
                's_no' => 122.0,
                'parent_id' => 3.0,
                'categories' => 'Red Fruit',
            ),
        122 =>
            array (
                's_no' => 123.0,
                'parent_id' => 3.0,
                'categories' => 'Black Fruit',
            ),
        123 =>
            array (
                's_no' => 124.0,
                'parent_id' => 3.0,
                'categories' => 'Green Fruit',
            ),
        124 =>
            array (
                's_no' => 125.0,
                'parent_id' => 3.0,
                'categories' => 'Brown Fruit',
            ),
        125 =>
            array (
                's_no' => 126.0,
                'parent_id' => 3.0,
                'categories' => 'Jams / Chutneys',
            ),
        126 =>
            array (
                's_no' => 127.0,
                'parent_id' => 3.0,
                'categories' => 'Dried Fruits',
            ),
        127 =>
            array (
                's_no' => 128.0,
                'parent_id' => 3.0,
                'categories' => 'Artificial Flavor / Candy',
            ),
        128 =>
            array (
                's_no' => 129.0,
                'parent_id' => 119.0,
                'categories' => 'Lime',
            ),
        129 =>
            array (
                's_no' => 130.0,
                'parent_id' => 119.0,
                'categories' => 'Sweet Lime',
            ),
        130 =>
            array (
                's_no' => 131.0,
                'parent_id' => 119.0,
                'categories' => 'Grapefruit',
            ),
        131 =>
            array (
                's_no' => 132.0,
                'parent_id' => 119.0,
                'categories' => 'Orange',
            ),
        132 =>
            array (
                's_no' => 133.0,
                'parent_id' => 120.0,
                'categories' => 'Quince',
            ),
        133 =>
            array (
                's_no' => 134.0,
                'parent_id' => 120.0,
                'categories' => 'Apple',
            ),
        134 =>
            array (
                's_no' => 135.0,
                'parent_id' => 120.0,
                'categories' => 'Pear',
            ),
        135 =>
            array (
                's_no' => 136.0,
                'parent_id' => 120.0,
                'categories' => 'Nectarine',
            ),
        136 =>
            array (
                's_no' => 137.0,
                'parent_id' => 120.0,
                'categories' => 'Peach',
            ),
        137 =>
            array (
                's_no' => 138.0,
                'parent_id' => 120.0,
                'categories' => 'Tangarine',
            ),
        138 =>
            array (
                's_no' => 139.0,
                'parent_id' => 120.0,
                'categories' => 'Apricot',
            ),
        139 =>
            array (
                's_no' => 140.0,
                'parent_id' => 120.0,
                'categories' => 'Plum',
            ),
        140 =>
            array (
                's_no' => 141.0,
                'parent_id' => 120.0,
                'categories' => 'Persimmon',
            ),
        141 =>
            array (
                's_no' => 142.0,
                'parent_id' => 121.0,
                'categories' => 'Pineapple',
            ),
        142 =>
            array (
                's_no' => 143.0,
                'parent_id' => 121.0,
                'categories' => 'Mango',
            ),
        143 =>
            array (
                's_no' => 144.0,
                'parent_id' => 121.0,
                'categories' => 'Papaya',
            ),
        144 =>
            array (
                's_no' => 145.0,
                'parent_id' => 121.0,
                'categories' => 'Guava',
            ),
        145 =>
            array (
                's_no' => 146.0,
                'parent_id' => 121.0,
                'categories' => 'Kiwi',
            ),
        146 =>
            array (
                's_no' => 147.0,
                'parent_id' => 121.0,
                'categories' => 'Litchee',
            ),
        147 =>
            array (
                's_no' => 148.0,
                'parent_id' => 121.0,
                'categories' => 'Jujube',
            ),
        148 =>
            array (
                's_no' => 149.0,
                'parent_id' => 121.0,
                'categories' => 'Cape Gooseberry',
            ),
        149 =>
            array (
                's_no' => 150.0,
                'parent_id' => 121.0,
                'categories' => 'Tamarind',
            ),
        150 =>
            array (
                's_no' => 151.0,
                'parent_id' => 122.0,
                'categories' => 'Cranberry',
            ),
        151 =>
            array (
                's_no' => 152.0,
                'parent_id' => 122.0,
                'categories' => 'Red Plum',
            ),
        152 =>
            array (
                's_no' => 153.0,
                'parent_id' => 122.0,
                'categories' => 'Pomegranate',
            ),
        153 =>
            array (
                's_no' => 154.0,
                'parent_id' => 122.0,
                'categories' => 'Sour Cherry',
            ),
        154 =>
            array (
                's_no' => 155.0,
                'parent_id' => 122.0,
                'categories' => 'Strawberry',
            ),
        155 =>
            array (
                's_no' => 156.0,
                'parent_id' => 122.0,
                'categories' => 'Cherry',
            ),
        156 =>
            array (
                's_no' => 157.0,
                'parent_id' => 122.0,
                'categories' => 'Raspberry',
            ),
        157 =>
            array (
                's_no' => 158.0,
                'parent_id' => 122.0,
                'categories' => 'Bubbleberry',
            ),
        158 =>
            array (
                's_no' => 159.0,
                'parent_id' => 123.0,
                'categories' => 'Black Current',
            ),
        159 =>
            array (
                's_no' => 160.0,
                'parent_id' => 123.0,
                'categories' => 'Black Cherry',
            ),
        160 =>
            array (
                's_no' => 161.0,
                'parent_id' => 123.0,
                'categories' => 'Black Berry',
            ),
        161 =>
            array (
                's_no' => 162.0,
                'parent_id' => 123.0,
                'categories' => 'Black Olive',
            ),
        162 =>
            array (
                's_no' => 163.0,
                'parent_id' => 123.0,
                'categories' => 'Indian Black Berry (Jamun)',
            ),
        163 =>
            array (
                's_no' => 164.0,
                'parent_id' => 123.0,
                'categories' => 'Vanilla Pods',
            ),
        164 =>
            array (
                's_no' => 165.0,
                'parent_id' => 124.0,
                'categories' => 'Custard Apple',
            ),
        165 =>
            array (
                's_no' => 166.0,
                'parent_id' => 124.0,
                'categories' => 'Green Olive',
            ),
        166 =>
            array (
                's_no' => 167.0,
                'parent_id' => 125.0,
                'categories' => 'Dates',
            ),
        167 =>
            array (
                's_no' => 168.0,
                'parent_id' => 125.0,
                'categories' => 'Tamarind Fresh',
            ),
        168 =>
            array (
                's_no' => 169.0,
                'parent_id' => 126.0,
                'categories' => 'Marmalades',
            ),
        169 =>
            array (
                's_no' => 170.0,
                'parent_id' => 126.0,
                'categories' => 'Mango Chutneys',
            ),
        170 =>
            array (
                's_no' => 171.0,
                'parent_id' => 127.0,
                'categories' => 'Tamarind Pulp Dried',
            ),
        171 =>
            array (
                's_no' => 172.0,
                'parent_id' => 127.0,
                'categories' => 'Dry Figs',
            ),
        172 =>
            array (
                's_no' => 173.0,
                'parent_id' => 127.0,
                'categories' => 'Raisins',
            ),
        173 =>
            array (
                's_no' => 174.0,
                'parent_id' => 127.0,
                'categories' => 'Prunes',
            ),
        174 =>
            array (
                's_no' => 175.0,
                'parent_id' => 127.0,
                'categories' => 'Dry Apricots',
            ),
        175 =>
            array (
                's_no' => 176.0,
                'parent_id' => 127.0,
                'categories' => 'Dehydrated Cut Apples',
            ),
        176 =>
            array (
                's_no' => 177.0,
                'parent_id' => 127.0,
                'categories' => 'Other Fruits',
            ),
        177 =>
            array (
                's_no' => 178.0,
                'parent_id' => 128.0,
                'categories' => 'Vanilla Essence',
            ),
        178 =>
            array (
                's_no' => 179.0,
                'parent_id' => 128.0,
                'categories' => 'Lemon Essence',
            ),
        179 =>
            array (
                's_no' => 180.0,
                'parent_id' => 128.0,
                'categories' => 'Mango Essence',
            ),
        180 =>
            array (
                's_no' => 181.0,
                'parent_id' => 128.0,
                'categories' => 'Black Current Essence',
            ),
        181 =>
            array (
                's_no' => 182.0,
                'parent_id' => 4.0,
                'categories' => 'Almonds',
            ),
        182 =>
            array (
                's_no' => 183.0,
                'parent_id' => 4.0,
                'categories' => 'Walnuts',
            ),
        183 =>
            array (
                's_no' => 184.0,
                'parent_id' => 4.0,
                'categories' => 'Hazelnuts',
            ),
        184 =>
            array (
                's_no' => 185.0,
                'parent_id' => 4.0,
                'categories' => 'Pinenuts',
            ),
        185 =>
            array (
                's_no' => 186.0,
                'parent_id' => 4.0,
                'categories' => 'Cashewnuts',
            ),
        186 =>
            array (
                's_no' => 187.0,
                'parent_id' => 4.0,
                'categories' => 'Dried Coconut',
            ),
        187 =>
            array (
                's_no' => 188.0,
                'parent_id' => 4.0,
                'categories' => 'Fresh Coconut',
            ),
        188 =>
            array (
                's_no' => 189.0,
                'parent_id' => 5.0,
                'categories' => 'Dry Flowers',
            ),
        189 =>
            array (
                's_no' => 190.0,
                'parent_id' => 5.0,
                'categories' => 'Fresh Flowers',
            ),
        190 =>
            array (
                's_no' => 191.0,
                'parent_id' => 5.0,
                'categories' => 'Essence',
            ),
        191 =>
            array (
                's_no' => 192.0,
                'parent_id' => 189.0,
                'categories' => 'Jasmine',
            ),
        192 =>
            array (
                's_no' => 193.0,
                'parent_id' => 189.0,
                'categories' => 'Camomile',
            ),
        193 =>
            array (
                's_no' => 194.0,
                'parent_id' => 189.0,
                'categories' => 'Lavender',
            ),
        194 =>
            array (
                's_no' => 195.0,
                'parent_id' => 189.0,
                'categories' => 'Honeysuckle',
            ),
        195 =>
            array (
                's_no' => 196.0,
                'parent_id' => 189.0,
                'categories' => 'Orange Blossom',
            ),
        196 =>
            array (
                's_no' => 197.0,
                'parent_id' => 189.0,
                'categories' => 'Rose',
            ),
        197 =>
            array (
                's_no' => 198.0,
                'parent_id' => 189.0,
                'categories' => 'Kewra',
            ),
        198 =>
            array (
                's_no' => 199.0,
                'parent_id' => 189.0,
                'categories' => 'Acacia',
            ),
        199 =>
            array (
                's_no' => 200.0,
                'parent_id' => 189.0,
                'categories' => 'Violet',
            ),
        200 =>
            array (
                's_no' => 201.0,
                'parent_id' => 191.0,
                'categories' => 'Jasmine',
            ),
        201 =>
            array (
                's_no' => 202.0,
                'parent_id' => 191.0,
                'categories' => 'Rose',
            ),
        202 =>
            array (
                's_no' => 203.0,
                'parent_id' => 191.0,
                'categories' => 'Kewra',
            ),
        203 =>
            array (
                's_no' => 204.0,
                'parent_id' => 6.0,
                'categories' => 'Animal',
            ),
        204 =>
            array (
                's_no' => 205.0,
                'parent_id' => 6.0,
                'categories' => 'Meat',
            ),
        205 =>
            array (
                's_no' => 206.0,
                'parent_id' => 6.0,
                'categories' => 'Acquatic',
            ),
        206 =>
            array (
                's_no' => 207.0,
                'parent_id' => 6.0,
                'categories' => 'Poultry',
            ),
        207 =>
            array (
                's_no' => 208.0,
                'parent_id' => 6.0,
                'categories' => 'Dairy',
            ),
        208 =>
            array (
                's_no' => 209.0,
                'parent_id' => 204.0,
                'categories' => 'Wet Dog',
            ),
        209 =>
            array (
                's_no' => 210.0,
                'parent_id' => 204.0,
                'categories' => 'Urine',
            ),
        210 =>
            array (
                's_no' => 211.0,
                'parent_id' => 204.0,
                'categories' => 'Fecal',
            ),
        211 =>
            array (
                's_no' => 212.0,
                'parent_id' => 204.0,
                'categories' => 'Barnyard',
            ),
        212 =>
            array (
                's_no' => 213.0,
                'parent_id' => 204.0,
                'categories' => 'Horse',
            ),
        213 =>
            array (
                's_no' => 214.0,
                'parent_id' => 204.0,
                'categories' => 'Leather',
            ),
        214 =>
            array (
                's_no' => 215.0,
                'parent_id' => 204.0,
                'categories' => 'Cow',
            ),
        215 =>
            array (
                's_no' => 216.0,
                'parent_id' => 205.0,
                'categories' => 'Raw',
            ),
        216 =>
            array (
                's_no' => 217.0,
                'parent_id' => 205.0,
                'categories' => 'Dry',
            ),
        217 =>
            array (
                's_no' => 218.0,
                'parent_id' => 205.0,
                'categories' => 'Cooked',
            ),
        218 =>
            array (
                's_no' => 219.0,
                'parent_id' => 205.0,
                'categories' => 'Cured',
            ),
        219 =>
            array (
                's_no' => 220.0,
                'parent_id' => 216.0,
                'categories' => 'Mutton',
            ),
        220 =>
            array (
                's_no' => 221.0,
                'parent_id' => 216.0,
                'categories' => 'Lamb',
            ),
        221 =>
            array (
                's_no' => 222.0,
                'parent_id' => 217.0,
                'categories' => 'Bacon',
            ),
        222 =>
            array (
                's_no' => 223.0,
                'parent_id' => 218.0,
                'categories' => 'Broth',
            ),
        223 =>
            array (
                's_no' => 224.0,
                'parent_id' => 218.0,
                'categories' => 'Lamb',
            ),
        224 =>
            array (
                's_no' => 225.0,
                'parent_id' => 218.0,
                'categories' => 'Mutton',
            ),
        225 =>
            array (
                's_no' => 226.0,
                'parent_id' => 218.0,
                'categories' => 'Grilled',
            ),
        226 =>
            array (
                's_no' => 227.0,
                'parent_id' => 218.0,
                'categories' => 'Smoked',
            ),
        227 =>
            array (
                's_no' => 228.0,
                'parent_id' => 219.0,
                'categories' => 'Salami',
            ),
        228 =>
            array (
                's_no' => 229.0,
                'parent_id' => 219.0,
                'categories' => 'Sausages',
            ),
        229 =>
            array (
                's_no' => 230.0,
                'parent_id' => 206.0,
                'categories' => 'Fish',
            ),
        230 =>
            array (
                's_no' => 231.0,
                'parent_id' => 206.0,
                'categories' => 'Prawns',
            ),
        231 =>
            array (
                's_no' => 232.0,
                'parent_id' => 207.0,
                'categories' => 'Chicken',
            ),
        232 =>
            array (
                's_no' => 233.0,
                'parent_id' => 207.0,
                'categories' => 'Eggs',
            ),
        233 =>
            array (
                's_no' => 234.0,
                'parent_id' => 208.0,
                'categories' => 'Milk',
            ),
        234 =>
            array (
                's_no' => 235.0,
                'parent_id' => 208.0,
                'categories' => 'Curds',
            ),
        235 =>
            array (
                's_no' => 236.0,
                'parent_id' => 208.0,
                'categories' => 'Butter',
            ),
        236 =>
            array (
                's_no' => 237.0,
                'parent_id' => 208.0,
                'categories' => 'Whey',
            ),
        237 =>
            array (
                's_no' => 238.0,
                'parent_id' => 208.0,
                'categories' => 'Cream',
            ),
        238 =>
            array (
                's_no' => 239.0,
                'parent_id' => 208.0,
                'categories' => 'Cheese',
            ),
        239 =>
            array (
                's_no' => 240.0,
                'parent_id' => 234.0,
                'categories' => 'Fresh Milk',
            ),
        240 =>
            array (
                's_no' => 241.0,
                'parent_id' => 234.0,
                'categories' => 'Sour Milk',
            ),
        241 =>
            array (
                's_no' => 242.0,
                'parent_id' => 234.0,
                'categories' => 'Boiled Milk',
            ),
        242 =>
            array (
                's_no' => 243.0,
                'parent_id' => 234.0,
                'categories' => 'Caramelised / Condensed Milk',
            ),
        243 =>
            array (
                's_no' => 244.0,
                'parent_id' => 234.0,
                'categories' => 'Butter Milk',
            ),
        244 =>
            array (
                's_no' => 245.0,
                'parent_id' => 235.0,
                'categories' => 'Lassi',
            ),
        245 =>
            array (
                's_no' => 246.0,
                'parent_id' => 235.0,
                'categories' => 'Curds',
            ),
        246 =>
            array (
                's_no' => 247.0,
                'parent_id' => 235.0,
                'categories' => 'Acidified Curd',
            ),
        247 =>
            array (
                's_no' => 248.0,
                'parent_id' => 235.0,
                'categories' => 'Yogurt',
            ),
        248 =>
            array (
                's_no' => 249.0,
                'parent_id' => 236.0,
                'categories' => 'Fresh Butter',
            ),
        249 =>
            array (
                's_no' => 250.0,
                'parent_id' => 236.0,
                'categories' => 'Melted Butter',
            ),
        250 =>
            array (
                's_no' => 251.0,
                'parent_id' => 236.0,
                'categories' => 'Rancid Butter',
            ),
        251 =>
            array (
                's_no' => 252.0,
                'parent_id' => 236.0,
                'categories' => 'Clarified Butter',
            ),
        252 =>
            array (
                's_no' => 253.0,
                'parent_id' => 238.0,
                'categories' => 'Fresh Cream',
            ),
        253 =>
            array (
                's_no' => 254.0,
                'parent_id' => 238.0,
                'categories' => 'Clotted Cream',
            ),
        254 =>
            array (
                's_no' => 255.0,
                'parent_id' => 238.0,
                'categories' => 'Cultured Cream',
            ),
        255 =>
            array (
                's_no' => 256.0,
                'parent_id' => 239.0,
                'categories' => 'Cottage Cheese',
            ),
        256 =>
            array (
                's_no' => 257.0,
                'parent_id' => 239.0,
                'categories' => 'Mozzarella',
            ),
        257 =>
            array (
                's_no' => 258.0,
                'parent_id' => 239.0,
                'categories' => 'Blue Cheese',
            ),
        258 =>
            array (
                's_no' => 259.0,
                'parent_id' => 239.0,
                'categories' => 'Cheddar',
            ),
        259 =>
            array (
                's_no' => 260.0,
                'parent_id' => 239.0,
                'categories' => 'Cheese Rind',
            ),
        260 =>
            array (
                's_no' => 261.0,
                'parent_id' => 239.0,
                'categories' => 'Cancoillotte',
            ),
    )  ;

        $data = [];

        foreach ($extra as $item)
        {
            $data[] = ['type'=>'Aroma','s_no'=>$item['s_no'],'parent_id'=>$item['parent_id'],'value'=>$item['categories']];
        }
        \DB::table('global_nested_option')->insert($data);

    }
}
