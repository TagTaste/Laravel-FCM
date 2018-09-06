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
        $extra =  array (
            0 =>
                array (
                    's_no' => 1.0,
                    'parent_id' => NULL,
                    'value' => 'Vegetal',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            1 =>
                array (
                    's_no' => 2.0,
                    'parent_id' => NULL,
                    'value' => 'Spices',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            2 =>
                array (
                    's_no' => 3.0,
                    'parent_id' => NULL,
                    'value' => 'Fruits',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            3 =>
                array (
                    's_no' => 4.0,
                    'parent_id' => NULL,
                    'value' => 'Nuts',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            4 =>
                array (
                    's_no' => 5.0,
                    'parent_id' => NULL,
                    'value' => 'Floral',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            5 =>
                array (
                    's_no' => 6.0,
                    'parent_id' => NULL,
                    'value' => 'Animal',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            6 =>
                array (
                    's_no' => 7.0,
                    'parent_id' => NULL,
                    'value' => 'Caramel',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            7 =>
                array (
                    's_no' => 8.0,
                    'parent_id' => NULL,
                    'value' => 'Earthy',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            8 =>
                array (
                    's_no' => 9.0,
                    'parent_id' => NULL,
                    'value' => 'Chemical',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            9 =>
                array (
                    's_no' => 10.0,
                    'parent_id' => NULL,
                    'value' => 'Putrid',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            10 =>
                array (
                    's_no' => 11.0,
                    'parent_id' => NULL,
                    'value' => 'Any Other',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            11 =>
                array (
                    's_no' => 12.0,
                    'parent_id' => 1.0,
                    'value' => 'Vegetables',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            12 =>
                array (
                    's_no' => 13.0,
                    'parent_id' => 1.0,
                    'value' => 'Leaves',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            13 =>
                array (
                    's_no' => 14.0,
                    'parent_id' => 1.0,
                    'value' => 'Herbs',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            14 =>
                array (
                    's_no' => 15.0,
                    'parent_id' => 12.0,
                    'value' => 'Dry Vegetables',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            15 =>
                array (
                    's_no' => 16.0,
                    'parent_id' => 12.0,
                    'value' => 'Fresh',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            16 =>
                array (
                    's_no' => 17.0,
                    'parent_id' => 12.0,
                    'value' => 'Canned / Cooked',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            17 =>
                array (
                    's_no' => 18.0,
                    'parent_id' => 15.0,
                    'value' => 'Hay / Straw',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            18 =>
                array (
                    's_no' => 19.0,
                    'parent_id' => 15.0,
                    'value' => 'Sun Dried Tomato',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            19 =>
                array (
                    's_no' => 20.0,
                    'parent_id' => 16.0,
                    'value' => 'Cut Green Grass',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            20 =>
                array (
                    's_no' => 21.0,
                    'parent_id' => 16.0,
                    'value' => 'Bell Peppers',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            21 =>
                array (
                    's_no' => 22.0,
                    'parent_id' => 16.0,
                    'value' => 'Horse Radish',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            22 =>
                array (
                    's_no' => 23.0,
                    'parent_id' => 16.0,
                    'value' => 'Tomato',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            23 =>
                array (
                    's_no' => 24.0,
                    'parent_id' => 16.0,
                    'value' => 'Spinach',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            24 =>
                array (
                    's_no' => 25.0,
                    'parent_id' => 16.0,
                    'value' => 'Bottle Gourd',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            25 =>
                array (
                    's_no' => 26.0,
                    'parent_id' => 16.0,
                    'value' => 'Pumpkin',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            26 =>
                array (
                    's_no' => 27.0,
                    'parent_id' => 16.0,
                    'value' => 'Ash Gourd',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            27 =>
                array (
                    's_no' => 28.0,
                    'parent_id' => 16.0,
                    'value' => 'Bitter Gourd',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            28 =>
                array (
                    's_no' => 29.0,
                    'parent_id' => 17.0,
                    'value' => 'Green Beans',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            29 =>
                array (
                    's_no' => 30.0,
                    'parent_id' => 17.0,
                    'value' => 'Chick Peas',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            30 =>
                array (
                    's_no' => 31.0,
                    'parent_id' => 17.0,
                    'value' => 'Canned / Cooked Green Olive',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            31 =>
                array (
                    's_no' => 32.0,
                    'parent_id' => 17.0,
                    'value' => 'Canned / Cooked Black Olive',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            32 =>
                array (
                    's_no' => 33.0,
                    'parent_id' => 17.0,
                    'value' => 'Asparagus',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            33 =>
                array (
                    's_no' => 34.0,
                    'parent_id' => 13.0,
                    'value' => 'Dry Leaves',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            34 =>
                array (
                    's_no' => 35.0,
                    'parent_id' => 13.0,
                    'value' => 'Fresh Leaves',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            35 =>
                array (
                    's_no' => 36.0,
                    'parent_id' => 34.0,
                    'value' => 'Bay',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            36 =>
                array (
                    's_no' => 37.0,
                    'parent_id' => 34.0,
                    'value' => 'Tea',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            37 =>
                array (
                    's_no' => 38.0,
                    'parent_id' => 34.0,
                    'value' => 'Stevia',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            38 =>
                array (
                    's_no' => 39.0,
                    'parent_id' => 35.0,
                    'value' => 'Curry Leaves',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            39 =>
                array (
                    's_no' => 40.0,
                    'parent_id' => 36.0,
                    'value' => 'Bay Leaves',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            40 =>
                array (
                    's_no' => 41.0,
                    'parent_id' => 14.0,
                    'value' => 'Dry Herbs',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            41 =>
                array (
                    's_no' => 42.0,
                    'parent_id' => 14.0,
                    'value' => 'Fresh Herbs',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            42 =>
                array (
                    's_no' => 43.0,
                    'parent_id' => 41.0,
                    'value' => 'Herbal Teas',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            43 =>
                array (
                    's_no' => 44.0,
                    'parent_id' => 41.0,
                    'value' => 'Dry Thyme',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            44 =>
                array (
                    's_no' => 45.0,
                    'parent_id' => 41.0,
                    'value' => 'Dry Rosemary',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            45 =>
                array (
                    's_no' => 46.0,
                    'parent_id' => 41.0,
                    'value' => 'Dry Oregano',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            46 =>
                array (
                    's_no' => 47.0,
                    'parent_id' => 41.0,
                    'value' => 'Dry Basil',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            47 =>
                array (
                    's_no' => 48.0,
                    'parent_id' => 41.0,
                    'value' => 'Dry Coriander',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            48 =>
                array (
                    's_no' => 49.0,
                    'parent_id' => 41.0,
                    'value' => 'Dry Lemon Grass',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            49 =>
                array (
                    's_no' => 50.0,
                    'parent_id' => 41.0,
                    'value' => 'Dry Dill',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            50 =>
                array (
                    's_no' => 51.0,
                    'parent_id' => 41.0,
                    'value' => 'Dry Sage',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            51 =>
                array (
                    's_no' => 52.0,
                    'parent_id' => 41.0,
                    'value' => 'Dry Tarragon',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            52 =>
                array (
                    's_no' => 53.0,
                    'parent_id' => 41.0,
                    'value' => 'Mixed Herbs',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            53 =>
                array (
                    's_no' => 54.0,
                    'parent_id' => 41.0,
                    'value' => 'Licorice',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            54 =>
                array (
                    's_no' => 55.0,
                    'parent_id' => 42.0,
                    'value' => 'Mint',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            55 =>
                array (
                    's_no' => 56.0,
                    'parent_id' => 42.0,
                    'value' => 'Peppermint',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            56 =>
                array (
                    's_no' => 57.0,
                    'parent_id' => 42.0,
                    'value' => 'Fresh Thyme',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            57 =>
                array (
                    's_no' => 58.0,
                    'parent_id' => 42.0,
                    'value' => 'Fresh Rosemary',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            58 =>
                array (
                    's_no' => 59.0,
                    'parent_id' => 42.0,
                    'value' => 'Fresh Oregano',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            59 =>
                array (
                    's_no' => 60.0,
                    'parent_id' => 42.0,
                    'value' => 'Fresh Basil',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            60 =>
                array (
                    's_no' => 61.0,
                    'parent_id' => 42.0,
                    'value' => 'Chervil',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            61 =>
                array (
                    's_no' => 62.0,
                    'parent_id' => 42.0,
                    'value' => 'Celantro',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            62 =>
                array (
                    's_no' => 63.0,
                    'parent_id' => 42.0,
                    'value' => 'Fresh Coriander',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            63 =>
                array (
                    's_no' => 64.0,
                    'parent_id' => 42.0,
                    'value' => 'Parsley',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            64 =>
                array (
                    's_no' => 65.0,
                    'parent_id' => 42.0,
                    'value' => 'Celery',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            65 =>
                array (
                    's_no' => 66.0,
                    'parent_id' => 42.0,
                    'value' => 'Fresh Lemon Grass',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            66 =>
                array (
                    's_no' => 67.0,
                    'parent_id' => 42.0,
                    'value' => 'Fresh Dill',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            67 =>
                array (
                    's_no' => 68.0,
                    'parent_id' => 42.0,
                    'value' => 'Marjoram',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            68 =>
                array (
                    's_no' => 69.0,
                    'parent_id' => 42.0,
                    'value' => 'Patchouli',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            69 =>
                array (
                    's_no' => 70.0,
                    'parent_id' => 42.0,
                    'value' => 'Fresh Sage',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            70 =>
                array (
                    's_no' => 71.0,
                    'parent_id' => 42.0,
                    'value' => 'Fresh Tarragon',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            71 =>
                array (
                    's_no' => 72.0,
                    'parent_id' => 2.0,
                    'value' => 'Warming',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            72 =>
                array (
                    's_no' => 73.0,
                    'parent_id' => 2.0,
                    'value' => 'Pungent',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            73 =>
                array (
                    's_no' => 74.0,
                    'parent_id' => 72.0,
                    'value' => 'All Spice',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            74 =>
                array (
                    's_no' => 75.0,
                    'parent_id' => 72.0,
                    'value' => 'Anise (Star)',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            75 =>
                array (
                    's_no' => 76.0,
                    'parent_id' => 72.0,
                    'value' => 'Aniseed',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            76 =>
                array (
                    's_no' => 77.0,
                    'parent_id' => 72.0,
                    'value' => 'Balsamic',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            77 =>
                array (
                    's_no' => 78.0,
                    'parent_id' => 72.0,
                    'value' => 'Bitter Sweet',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            78 =>
                array (
                    's_no' => 79.0,
                    'parent_id' => 72.0,
                    'value' => 'Cardamom',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            79 =>
                array (
                    's_no' => 80.0,
                    'parent_id' => 72.0,
                    'value' => 'Cinnamon',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            80 =>
                array (
                    's_no' => 81.0,
                    'parent_id' => 72.0,
                    'value' => 'Cloves',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            81 =>
                array (
                    's_no' => 82.0,
                    'parent_id' => 72.0,
                    'value' => 'Cumin',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            82 =>
                array (
                    's_no' => 83.0,
                    'parent_id' => 72.0,
                    'value' => 'Asian 5 Spice',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            83 =>
                array (
                    's_no' => 84.0,
                    'parent_id' => 72.0,
                    'value' => 'Fresh Spice',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            84 =>
                array (
                    's_no' => 85.0,
                    'parent_id' => 72.0,
                    'value' => 'Dry Ginger Powder',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            85 =>
                array (
                    's_no' => 86.0,
                    'parent_id' => 72.0,
                    'value' => 'Fennel',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            86 =>
                array (
                    's_no' => 87.0,
                    'parent_id' => 72.0,
                    'value' => 'Nutmeg',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            87 =>
                array (
                    's_no' => 88.0,
                    'parent_id' => 72.0,
                    'value' => 'Turmeric',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            88 =>
                array (
                    's_no' => 89.0,
                    'parent_id' => 72.0,
                    'value' => 'Sweet Spice',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            89 =>
                array (
                    's_no' => 90.0,
                    'parent_id' => 72.0,
                    'value' => 'Zatar',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            90 =>
                array (
                    's_no' => 91.0,
                    'parent_id' => 72.0,
                    'value' => 'Sumac',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            91 =>
                array (
                    's_no' => 92.0,
                    'parent_id' => 72.0,
                    'value' => 'Saffron',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            92 =>
                array (
                    's_no' => 93.0,
                    'parent_id' => 72.0,
                    'value' => 'Mustard Seeds',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            93 =>
                array (
                    's_no' => 94.0,
                    'parent_id' => 72.0,
                    'value' => 'Black Cardamom',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            94 =>
                array (
                    's_no' => 95.0,
                    'parent_id' => 72.0,
                    'value' => 'Ginger Fresh',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            95 =>
                array (
                    's_no' => 96.0,
                    'parent_id' => 72.0,
                    'value' => 'Coriander Seeds',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            96 =>
                array (
                    's_no' => 97.0,
                    'parent_id' => 72.0,
                    'value' => 'Mace',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            97 =>
                array (
                    's_no' => 98.0,
                    'parent_id' => 72.0,
                    'value' => 'Fenugreek Seeds',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            98 =>
                array (
                    's_no' => 99.0,
                    'parent_id' => 72.0,
                    'value' => 'Asafoetida',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            99 =>
                array (
                    's_no' => 100.0,
                    'parent_id' => 72.0,
                    'value' => 'Dry Mango Powder',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            100 =>
                array (
                    's_no' => 101.0,
                    'parent_id' => 72.0,
                    'value' => 'Nigella',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            101 =>
                array (
                    's_no' => 102.0,
                    'parent_id' => 72.0,
                    'value' => 'Dry Pomegranate Powder',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            102 =>
                array (
                    's_no' => 103.0,
                    'parent_id' => 72.0,
                    'value' => 'Poppy Seeds',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            103 =>
                array (
                    's_no' => 104.0,
                    'parent_id' => 72.0,
                    'value' => 'Sesame Black Seeds',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            104 =>
                array (
                    's_no' => 105.0,
                    'parent_id' => 72.0,
                    'value' => 'Sesame White',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            105 =>
                array (
                    's_no' => 106.0,
                    'parent_id' => 72.0,
                    'value' => 'Black Salt',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            106 =>
                array (
                    's_no' => 107.0,
                    'parent_id' => 72.0,
                    'value' => 'Pink Himalayan Salt',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            107 =>
                array (
                    's_no' => 108.0,
                    'parent_id' => 72.0,
                    'value' => 'Sea Salt',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            108 =>
                array (
                    's_no' => 109.0,
                    'parent_id' => 72.0,
                    'value' => 'Regular Salt',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            109 =>
                array (
                    's_no' => 110.0,
                    'parent_id' => 72.0,
                    'value' => 'Kasuri Methi',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            110 =>
                array (
                    's_no' => 111.0,
                    'parent_id' => 73.0,
                    'value' => 'Red Pepper Dry Whole',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            111 =>
                array (
                    's_no' => 112.0,
                    'parent_id' => 73.0,
                    'value' => 'Red Pepper Dry Powder',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            112 =>
                array (
                    's_no' => 113.0,
                    'parent_id' => 73.0,
                    'value' => 'Green Chilli Fresh',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            113 =>
                array (
                    's_no' => 114.0,
                    'parent_id' => 73.0,
                    'value' => 'Black Pepper Corns',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            114 =>
                array (
                    's_no' => 115.0,
                    'parent_id' => 73.0,
                    'value' => 'White Pepper Corns',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            115 =>
                array (
                    's_no' => 116.0,
                    'parent_id' => 73.0,
                    'value' => 'Black Pepper Powder',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            116 =>
                array (
                    's_no' => 117.0,
                    'parent_id' => 73.0,
                    'value' => 'Jalapeno',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            117 =>
                array (
                    's_no' => 118.0,
                    'parent_id' => 73.0,
                    'value' => 'Yellow Chilli Powder',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            118 =>
                array (
                    's_no' => 119.0,
                    'parent_id' => 3.0,
                    'value' => 'Citrus',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            119 =>
                array (
                    's_no' => 120.0,
                    'parent_id' => 3.0,
                    'value' => 'Tree Fruit',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            120 =>
                array (
                    's_no' => 121.0,
                    'parent_id' => 3.0,
                    'value' => 'Tropical Fruit',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            121 =>
                array (
                    's_no' => 122.0,
                    'parent_id' => 3.0,
                    'value' => 'Red Fruit',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            122 =>
                array (
                    's_no' => 123.0,
                    'parent_id' => 3.0,
                    'value' => 'Black Fruit',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            123 =>
                array (
                    's_no' => 124.0,
                    'parent_id' => 3.0,
                    'value' => 'Green Fruit',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            124 =>
                array (
                    's_no' => 125.0,
                    'parent_id' => 3.0,
                    'value' => 'Brown Fruit',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            125 =>
                array (
                    's_no' => 126.0,
                    'parent_id' => 3.0,
                    'value' => 'Jams / Chutneys',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            126 =>
                array (
                    's_no' => 127.0,
                    'parent_id' => 3.0,
                    'value' => 'Dried Fruits',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            127 =>
                array (
                    's_no' => 128.0,
                    'parent_id' => 3.0,
                    'value' => 'Artificial Flavor / Candy',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            128 =>
                array (
                    's_no' => 129.0,
                    'parent_id' => 119.0,
                    'value' => 'Lime',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            129 =>
                array (
                    's_no' => 130.0,
                    'parent_id' => 119.0,
                    'value' => 'Sweet Lime',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            130 =>
                array (
                    's_no' => 131.0,
                    'parent_id' => 119.0,
                    'value' => 'Grapefruit',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            131 =>
                array (
                    's_no' => 132.0,
                    'parent_id' => 119.0,
                    'value' => 'Orange',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            132 =>
                array (
                    's_no' => 133.0,
                    'parent_id' => 120.0,
                    'value' => 'Quince',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            133 =>
                array (
                    's_no' => 134.0,
                    'parent_id' => 120.0,
                    'value' => 'Apple',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            134 =>
                array (
                    's_no' => 135.0,
                    'parent_id' => 120.0,
                    'value' => 'Pear',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            135 =>
                array (
                    's_no' => 136.0,
                    'parent_id' => 120.0,
                    'value' => 'Nectarine',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            136 =>
                array (
                    's_no' => 137.0,
                    'parent_id' => 120.0,
                    'value' => 'Peach',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            137 =>
                array (
                    's_no' => 138.0,
                    'parent_id' => 120.0,
                    'value' => 'Tangarine',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            138 =>
                array (
                    's_no' => 139.0,
                    'parent_id' => 120.0,
                    'value' => 'Apricot',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            139 =>
                array (
                    's_no' => 140.0,
                    'parent_id' => 120.0,
                    'value' => 'Plum',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            140 =>
                array (
                    's_no' => 141.0,
                    'parent_id' => 120.0,
                    'value' => 'Persimmon',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            141 =>
                array (
                    's_no' => 142.0,
                    'parent_id' => 121.0,
                    'value' => 'Pineapple',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            142 =>
                array (
                    's_no' => 143.0,
                    'parent_id' => 121.0,
                    'value' => 'Mango',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            143 =>
                array (
                    's_no' => 144.0,
                    'parent_id' => 121.0,
                    'value' => 'Papaya',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            144 =>
                array (
                    's_no' => 145.0,
                    'parent_id' => 121.0,
                    'value' => 'Guava',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            145 =>
                array (
                    's_no' => 146.0,
                    'parent_id' => 121.0,
                    'value' => 'Kiwi',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            146 =>
                array (
                    's_no' => 147.0,
                    'parent_id' => 121.0,
                    'value' => 'Litchee',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            147 =>
                array (
                    's_no' => 148.0,
                    'parent_id' => 121.0,
                    'value' => 'Jujube',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            148 =>
                array (
                    's_no' => 149.0,
                    'parent_id' => 121.0,
                    'value' => 'Cape Gooseberry',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            149 =>
                array (
                    's_no' => 150.0,
                    'parent_id' => 121.0,
                    'value' => 'Tamarind',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            150 =>
                array (
                    's_no' => 151.0,
                    'parent_id' => 122.0,
                    'value' => 'Cranberry',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            151 =>
                array (
                    's_no' => 152.0,
                    'parent_id' => 122.0,
                    'value' => 'Red Plum',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            152 =>
                array (
                    's_no' => 153.0,
                    'parent_id' => 122.0,
                    'value' => 'Pomegranate',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            153 =>
                array (
                    's_no' => 154.0,
                    'parent_id' => 122.0,
                    'value' => 'Sour Cherry',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            154 =>
                array (
                    's_no' => 155.0,
                    'parent_id' => 122.0,
                    'value' => 'Strawberry',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            155 =>
                array (
                    's_no' => 156.0,
                    'parent_id' => 122.0,
                    'value' => 'Cherry',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            156 =>
                array (
                    's_no' => 157.0,
                    'parent_id' => 122.0,
                    'value' => 'Raspberry',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            157 =>
                array (
                    's_no' => 158.0,
                    'parent_id' => 122.0,
                    'value' => 'Bubbleberry',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            158 =>
                array (
                    's_no' => 159.0,
                    'parent_id' => 123.0,
                    'value' => 'Black Current',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            159 =>
                array (
                    's_no' => 160.0,
                    'parent_id' => 123.0,
                    'value' => 'Black Cherry',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            160 =>
                array (
                    's_no' => 161.0,
                    'parent_id' => 123.0,
                    'value' => 'Black Berry',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            161 =>
                array (
                    's_no' => 162.0,
                    'parent_id' => 123.0,
                    'value' => 'Black Olive',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            162 =>
                array (
                    's_no' => 163.0,
                    'parent_id' => 123.0,
                    'value' => 'Indian Black Berry (Jamun)',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            163 =>
                array (
                    's_no' => 164.0,
                    'parent_id' => 123.0,
                    'value' => 'Vanilla Pods',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            164 =>
                array (
                    's_no' => 165.0,
                    'parent_id' => 124.0,
                    'value' => 'Custard Apple',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            165 =>
                array (
                    's_no' => 166.0,
                    'parent_id' => 124.0,
                    'value' => 'Green Olive',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            166 =>
                array (
                    's_no' => 167.0,
                    'parent_id' => 125.0,
                    'value' => 'Dates',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            167 =>
                array (
                    's_no' => 168.0,
                    'parent_id' => 125.0,
                    'value' => 'Tamarind Fresh',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            168 =>
                array (
                    's_no' => 169.0,
                    'parent_id' => 126.0,
                    'value' => 'Marmalades',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            169 =>
                array (
                    's_no' => 170.0,
                    'parent_id' => 126.0,
                    'value' => 'Mango Chutneys',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            170 =>
                array (
                    's_no' => 171.0,
                    'parent_id' => 127.0,
                    'value' => 'Tamarind Pulp Dried',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            171 =>
                array (
                    's_no' => 172.0,
                    'parent_id' => 127.0,
                    'value' => 'Dry Figs',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            172 =>
                array (
                    's_no' => 173.0,
                    'parent_id' => 127.0,
                    'value' => 'Raisins',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            173 =>
                array (
                    's_no' => 174.0,
                    'parent_id' => 127.0,
                    'value' => 'Prunes',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            174 =>
                array (
                    's_no' => 175.0,
                    'parent_id' => 127.0,
                    'value' => 'Dry Apricots',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            175 =>
                array (
                    's_no' => 176.0,
                    'parent_id' => 127.0,
                    'value' => 'Dehydrated Cut Apples',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            176 =>
                array (
                    's_no' => 177.0,
                    'parent_id' => 127.0,
                    'value' => 'Other Fruits',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            177 =>
                array (
                    's_no' => 178.0,
                    'parent_id' => 128.0,
                    'value' => 'Vanilla Essence',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            178 =>
                array (
                    's_no' => 179.0,
                    'parent_id' => 128.0,
                    'value' => 'Lemon Essence',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            179 =>
                array (
                    's_no' => 180.0,
                    'parent_id' => 128.0,
                    'value' => 'Mango Essence',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            180 =>
                array (
                    's_no' => 181.0,
                    'parent_id' => 128.0,
                    'value' => 'Black Current Essence',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            181 =>
                array (
                    's_no' => 182.0,
                    'parent_id' => 4.0,
                    'value' => 'Almonds',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            182 =>
                array (
                    's_no' => 183.0,
                    'parent_id' => 4.0,
                    'value' => 'Walnuts',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            183 =>
                array (
                    's_no' => 184.0,
                    'parent_id' => 4.0,
                    'value' => 'Hazelnuts',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            184 =>
                array (
                    's_no' => 185.0,
                    'parent_id' => 4.0,
                    'value' => 'Pinenuts',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            185 =>
                array (
                    's_no' => 186.0,
                    'parent_id' => 4.0,
                    'value' => 'Cashewnuts',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            186 =>
                array (
                    's_no' => 187.0,
                    'parent_id' => 4.0,
                    'value' => 'Dried Coconut',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            187 =>
                array (
                    's_no' => 188.0,
                    'parent_id' => 4.0,
                    'value' => 'Fresh Coconut',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            188 =>
                array (
                    's_no' => 189.0,
                    'parent_id' => 5.0,
                    'value' => 'Dry Flowers',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            189 =>
                array (
                    's_no' => 190.0,
                    'parent_id' => 5.0,
                    'value' => 'Fresh Flowers',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            190 =>
                array (
                    's_no' => 191.0,
                    'parent_id' => 5.0,
                    'value' => 'Essence',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            191 =>
                array (
                    's_no' => 192.0,
                    'parent_id' => 189.0,
                    'value' => 'Jasmine',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            192 =>
                array (
                    's_no' => 193.0,
                    'parent_id' => 189.0,
                    'value' => 'Camomile',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            193 =>
                array (
                    's_no' => 194.0,
                    'parent_id' => 189.0,
                    'value' => 'Lavender',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            194 =>
                array (
                    's_no' => 195.0,
                    'parent_id' => 189.0,
                    'value' => 'Honeysuckle',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            195 =>
                array (
                    's_no' => 196.0,
                    'parent_id' => 189.0,
                    'value' => 'Orange Blossom',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            196 =>
                array (
                    's_no' => 197.0,
                    'parent_id' => 189.0,
                    'value' => 'Rose',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            197 =>
                array (
                    's_no' => 198.0,
                    'parent_id' => 189.0,
                    'value' => 'Kewra',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            198 =>
                array (
                    's_no' => 199.0,
                    'parent_id' => 189.0,
                    'value' => 'Acacia',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            199 =>
                array (
                    's_no' => 200.0,
                    'parent_id' => 189.0,
                    'value' => 'Violet',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            200 =>
                array (
                    's_no' => 201.0,
                    'parent_id' => 191.0,
                    'value' => 'Jasmine Essence',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            201 =>
                array (
                    's_no' => 202.0,
                    'parent_id' => 191.0,
                    'value' => 'Rose Essence',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            202 =>
                array (
                    's_no' => 203.0,
                    'parent_id' => 191.0,
                    'value' => 'Kewra Essence',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            203 =>
                array (
                    's_no' => 204.0,
                    'parent_id' => 6.0,
                    'value' => 'Animal',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            204 =>
                array (
                    's_no' => 205.0,
                    'parent_id' => 6.0,
                    'value' => 'Meat',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            205 =>
                array (
                    's_no' => 206.0,
                    'parent_id' => 6.0,
                    'value' => 'Acquatic',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            206 =>
                array (
                    's_no' => 207.0,
                    'parent_id' => 6.0,
                    'value' => 'Poultry',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            207 =>
                array (
                    's_no' => 208.0,
                    'parent_id' => 6.0,
                    'value' => 'Dairy',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            208 =>
                array (
                    's_no' => 209.0,
                    'parent_id' => 204.0,
                    'value' => 'Wet Dog',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            209 =>
                array (
                    's_no' => 210.0,
                    'parent_id' => 204.0,
                    'value' => 'Urine',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            210 =>
                array (
                    's_no' => 211.0,
                    'parent_id' => 204.0,
                    'value' => 'Fecal',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            211 =>
                array (
                    's_no' => 212.0,
                    'parent_id' => 204.0,
                    'value' => 'Barnyard',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            212 =>
                array (
                    's_no' => 213.0,
                    'parent_id' => 204.0,
                    'value' => 'Horse',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            213 =>
                array (
                    's_no' => 214.0,
                    'parent_id' => 204.0,
                    'value' => 'Leather',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            214 =>
                array (
                    's_no' => 215.0,
                    'parent_id' => 204.0,
                    'value' => 'Cow',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            215 =>
                array (
                    's_no' => 216.0,
                    'parent_id' => 205.0,
                    'value' => 'Raw',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            216 =>
                array (
                    's_no' => 217.0,
                    'parent_id' => 205.0,
                    'value' => 'Dry Meat',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            217 =>
                array (
                    's_no' => 218.0,
                    'parent_id' => 205.0,
                    'value' => 'Cooked',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            218 =>
                array (
                    's_no' => 219.0,
                    'parent_id' => 205.0,
                    'value' => 'Cured',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            219 =>
                array (
                    's_no' => 220.0,
                    'parent_id' => 216.0,
                    'value' => 'Raw Mutton',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            220 =>
                array (
                    's_no' => 221.0,
                    'parent_id' => 216.0,
                    'value' => 'Raw Lamb',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            221 =>
                array (
                    's_no' => 222.0,
                    'parent_id' => 217.0,
                    'value' => 'Bacon',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            222 =>
                array (
                    's_no' => 223.0,
                    'parent_id' => 218.0,
                    'value' => 'Cooked Broth',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            223 =>
                array (
                    's_no' => 224.0,
                    'parent_id' => 218.0,
                    'value' => 'Cooked Lamb',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            224 =>
                array (
                    's_no' => 225.0,
                    'parent_id' => 218.0,
                    'value' => 'Cooked Mutton',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            225 =>
                array (
                    's_no' => 226.0,
                    'parent_id' => 218.0,
                    'value' => 'Grilled',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            226 =>
                array (
                    's_no' => 227.0,
                    'parent_id' => 218.0,
                    'value' => 'Smoked',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            227 =>
                array (
                    's_no' => 228.0,
                    'parent_id' => 219.0,
                    'value' => 'Salami',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            228 =>
                array (
                    's_no' => 229.0,
                    'parent_id' => 219.0,
                    'value' => 'Sausages',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            229 =>
                array (
                    's_no' => 230.0,
                    'parent_id' => 206.0,
                    'value' => 'Fish',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            230 =>
                array (
                    's_no' => 231.0,
                    'parent_id' => 206.0,
                    'value' => 'Prawns',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            231 =>
                array (
                    's_no' => 232.0,
                    'parent_id' => 207.0,
                    'value' => 'Chicken',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            232 =>
                array (
                    's_no' => 233.0,
                    'parent_id' => 207.0,
                    'value' => 'Eggs',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            233 =>
                array (
                    's_no' => 234.0,
                    'parent_id' => 208.0,
                    'value' => 'Milk',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            234 =>
                array (
                    's_no' => 235.0,
                    'parent_id' => 208.0,
                    'value' => 'Curds',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            235 =>
                array (
                    's_no' => 236.0,
                    'parent_id' => 208.0,
                    'value' => 'Butter',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            236 =>
                array (
                    's_no' => 237.0,
                    'parent_id' => 208.0,
                    'value' => 'Whey',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            237 =>
                array (
                    's_no' => 238.0,
                    'parent_id' => 208.0,
                    'value' => 'Cream',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            238 =>
                array (
                    's_no' => 239.0,
                    'parent_id' => 208.0,
                    'value' => 'Cheese',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            239 =>
                array (
                    's_no' => 240.0,
                    'parent_id' => 234.0,
                    'value' => 'Fresh Milk',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            240 =>
                array (
                    's_no' => 241.0,
                    'parent_id' => 234.0,
                    'value' => 'Sour Milk',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            241 =>
                array (
                    's_no' => 242.0,
                    'parent_id' => 234.0,
                    'value' => 'Boiled Milk',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            242 =>
                array (
                    's_no' => 243.0,
                    'parent_id' => 234.0,
                    'value' => 'Condensed Milk',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            243 =>
                array (
                    's_no' => 244.0,
                    'parent_id' => 234.0,
                    'value' => 'Butter Milk',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            244 =>
                array (
                    's_no' => 245.0,
                    'parent_id' => 235.0,
                    'value' => 'Lassi',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            245 =>
                array (
                    's_no' => 246.0,
                    'parent_id' => 235.0,
                    'value' => 'Curds',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            246 =>
                array (
                    's_no' => 247.0,
                    'parent_id' => 235.0,
                    'value' => 'Acidified Curd',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            247 =>
                array (
                    's_no' => 248.0,
                    'parent_id' => 235.0,
                    'value' => 'Yogurt',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            248 =>
                array (
                    's_no' => 249.0,
                    'parent_id' => 236.0,
                    'value' => 'Fresh Butter',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            249 =>
                array (
                    's_no' => 250.0,
                    'parent_id' => 236.0,
                    'value' => 'Melted Butter',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            250 =>
                array (
                    's_no' => 251.0,
                    'parent_id' => 236.0,
                    'value' => 'Rancid Butter',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            251 =>
                array (
                    's_no' => 252.0,
                    'parent_id' => 236.0,
                    'value' => 'Clarified Butter',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            252 =>
                array (
                    's_no' => 253.0,
                    'parent_id' => 238.0,
                    'value' => 'Fresh Cream',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            253 =>
                array (
                    's_no' => 254.0,
                    'parent_id' => 238.0,
                    'value' => 'Clotted Cream',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            254 =>
                array (
                    's_no' => 255.0,
                    'parent_id' => 238.0,
                    'value' => 'Cultured Cream',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            255 =>
                array (
                    's_no' => 256.0,
                    'parent_id' => 239.0,
                    'value' => 'Cottage Cheese',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            256 =>
                array (
                    's_no' => 257.0,
                    'parent_id' => 239.0,
                    'value' => 'Mozzarella',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            257 =>
                array (
                    's_no' => 258.0,
                    'parent_id' => 239.0,
                    'value' => 'Blue Cheese',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            258 =>
                array (
                    's_no' => 259.0,
                    'parent_id' => 239.0,
                    'value' => 'Cheddar',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            259 =>
                array (
                    's_no' => 260.0,
                    'parent_id' => 239.0,
                    'value' => 'Cheese Rind',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            260 =>
                array (
                    's_no' => 261.0,
                    'parent_id' => 239.0,
                    'value' => 'Cancoillotte',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            261 =>
                array (
                    's_no' => 262.0,
                    'parent_id' => 6.0,
                    'value' => 'Fermented',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            262 =>
                array (
                    's_no' => 263.0,
                    'parent_id' => 6.0,
                    'value' => 'Savoury',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            263 =>
                array (
                    's_no' => 264.0,
                    'parent_id' => 262.0,
                    'value' => 'Fermented Yeast',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            264 =>
                array (
                    's_no' => 265.0,
                    'parent_id' => 262.0,
                    'value' => 'Lactic',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            265 =>
                array (
                    's_no' => 266.0,
                    'parent_id' => 262.0,
                    'value' => 'Vinegars / Acetic Acid / Kanji\'s',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            266 =>
                array (
                    's_no' => 267.0,
                    'parent_id' => 262.0,
                    'value' => 'Pickles & Relish',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            267 =>
                array (
                    's_no' => 268.0,
                    'parent_id' => 264.0,
                    'value' => 'Baker\'s Yeast',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            268 =>
                array (
                    's_no' => 269.0,
                    'parent_id' => 264.0,
                    'value' => 'Alcohol',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            269 =>
                array (
                    's_no' => 270.0,
                    'parent_id' => 264.0,
                    'value' => 'Sour Dough',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            270 =>
                array (
                    's_no' => 271.0,
                    'parent_id' => 265.0,
                    'value' => 'Saurkrat',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            271 =>
                array (
                    's_no' => 272.0,
                    'parent_id' => 265.0,
                    'value' => 'Lactic Curds',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            272 =>
                array (
                    's_no' => 273.0,
                    'parent_id' => 263.0,
                    'value' => 'Savoury Broth',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            273 =>
                array (
                    's_no' => 274.0,
                    'parent_id' => 263.0,
                    'value' => 'Lard',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            274 =>
                array (
                    's_no' => 275.0,
                    'parent_id' => 7.0,
                    'value' => 'Caramel',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            275 =>
                array (
                    's_no' => 276.0,
                    'parent_id' => 7.0,
                    'value' => 'Toasted',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            276 =>
                array (
                    's_no' => 277.0,
                    'parent_id' => 7.0,
                    'value' => 'Roasted',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            277 =>
                array (
                    's_no' => 278.0,
                    'parent_id' => 275.0,
                    'value' => 'Molasses - Pomegranate, Sugarcane',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            278 =>
                array (
                    's_no' => 279.0,
                    'parent_id' => 275.0,
                    'value' => 'Brown Sugar',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            279 =>
                array (
                    's_no' => 280.0,
                    'parent_id' => 275.0,
                    'value' => 'Maple Syrup',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            280 =>
                array (
                    's_no' => 281.0,
                    'parent_id' => 275.0,
                    'value' => 'Sugar',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            281 =>
                array (
                    's_no' => 282.0,
                    'parent_id' => 275.0,
                    'value' => 'Honey',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            282 =>
                array (
                    's_no' => 283.0,
                    'parent_id' => 275.0,
                    'value' => 'Butter (Heated)',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            283 =>
                array (
                    's_no' => 284.0,
                    'parent_id' => 275.0,
                    'value' => 'Butterscotch',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            284 =>
                array (
                    's_no' => 285.0,
                    'parent_id' => 275.0,
                    'value' => 'Toffee',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            285 =>
                array (
                    's_no' => 286.0,
                    'parent_id' => 275.0,
                    'value' => 'Nuts (Caramelized)',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            286 =>
                array (
                    's_no' => 287.0,
                    'parent_id' => 275.0,
                    'value' => 'Caramelised Milk',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            287 =>
                array (
                    's_no' => 288.0,
                    'parent_id' => 275.0,
                    'value' => 'Chocolate',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            288 =>
                array (
                    's_no' => 289.0,
                    'parent_id' => 276.0,
                    'value' => 'Bread',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            289 =>
                array (
                    's_no' => 290.0,
                    'parent_id' => 276.0,
                    'value' => 'Biscuit / Cookie',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            290 =>
                array (
                    's_no' => 291.0,
                    'parent_id' => 276.0,
                    'value' => 'Cocoa',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            291 =>
                array (
                    's_no' => 292.0,
                    'parent_id' => 276.0,
                    'value' => 'Cocoa Bitter',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            292 =>
                array (
                    's_no' => 293.0,
                    'parent_id' => 276.0,
                    'value' => 'Coffee',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            293 =>
                array (
                    's_no' => 294.0,
                    'parent_id' => 276.0,
                    'value' => 'Malt',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            294 =>
                array (
                    's_no' => 295.0,
                    'parent_id' => 276.0,
                    'value' => 'Roasted Meat',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            295 =>
                array (
                    's_no' => 296.0,
                    'parent_id' => 276.0,
                    'value' => 'Grilled Meat',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            296 =>
                array (
                    's_no' => 297.0,
                    'parent_id' => 276.0,
                    'value' => 'Smoked Meat',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            297 =>
                array (
                    's_no' => 298.0,
                    'parent_id' => 276.0,
                    'value' => 'Toasted Bread',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            298 =>
                array (
                    's_no' => 299.0,
                    'parent_id' => 276.0,
                    'value' => 'Cigar',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            299 =>
                array (
                    's_no' => 300.0,
                    'parent_id' => 276.0,
                    'value' => 'Toasted Tobacco',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            300 =>
                array (
                    's_no' => 301.0,
                    'parent_id' => 277.0,
                    'value' => 'Fresh Baked Crust',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            301 =>
                array (
                    's_no' => 302.0,
                    'parent_id' => 277.0,
                    'value' => 'Brioche',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            302 =>
                array (
                    's_no' => 303.0,
                    'parent_id' => 277.0,
                    'value' => 'Baked MacNCheese',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            303 =>
                array (
                    's_no' => 304.0,
                    'parent_id' => 277.0,
                    'value' => 'Fudge',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            304 =>
                array (
                    's_no' => 305.0,
                    'parent_id' => 277.0,
                    'value' => 'Creatine',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            305 =>
                array (
                    's_no' => 306.0,
                    'parent_id' => 277.0,
                    'value' => 'Roasted Onion / Burnt Onion',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            306 =>
                array (
                    's_no' => 307.0,
                    'parent_id' => 277.0,
                    'value' => 'Roasted Almond',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            307 =>
                array (
                    's_no' => 308.0,
                    'parent_id' => 277.0,
                    'value' => 'Roasted Peanut',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            308 =>
                array (
                    's_no' => 309.0,
                    'parent_id' => 277.0,
                    'value' => 'Roasted Coffee',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            309 =>
                array (
                    's_no' => 310.0,
                    'parent_id' => 277.0,
                    'value' => 'Chicory',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            310 =>
                array (
                    's_no' => 311.0,
                    'parent_id' => 277.0,
                    'value' => 'Hot Chocolate',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            311 =>
                array (
                    's_no' => 312.0,
                    'parent_id' => 277.0,
                    'value' => 'Dark Chocolate',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            312 =>
                array (
                    's_no' => 313.0,
                    'parent_id' => 277.0,
                    'value' => 'Burnt Aromas',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            313 =>
                array (
                    's_no' => 314.0,
                    'parent_id' => 277.0,
                    'value' => 'Smokey',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            314 =>
                array (
                    's_no' => 315.0,
                    'parent_id' => 8.0,
                    'value' => 'Clean Earth',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            315 =>
                array (
                    's_no' => 316.0,
                    'parent_id' => 8.0,
                    'value' => 'Woody',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            316 =>
                array (
                    's_no' => 317.0,
                    'parent_id' => 8.0,
                    'value' => 'Musty',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            317 =>
                array (
                    's_no' => 318.0,
                    'parent_id' => 315.0,
                    'value' => 'Peat',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            318 =>
                array (
                    's_no' => 319.0,
                    'parent_id' => 315.0,
                    'value' => 'Clay Pot',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            319 =>
                array (
                    's_no' => 320.0,
                    'parent_id' => 315.0,
                    'value' => 'Slate',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            320 =>
                array (
                    's_no' => 321.0,
                    'parent_id' => 315.0,
                    'value' => 'Potting Soil',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            321 =>
                array (
                    's_no' => 322.0,
                    'parent_id' => 315.0,
                    'value' => 'Volcanic Rocks',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            322 =>
                array (
                    's_no' => 323.0,
                    'parent_id' => 315.0,
                    'value' => 'Wet Cardboard',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            323 =>
                array (
                    's_no' => 324.0,
                    'parent_id' => 315.0,
                    'value' => 'Coal',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            324 =>
                array (
                    's_no' => 325.0,
                    'parent_id' => 316.0,
                    'value' => 'Woody',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            325 =>
                array (
                    's_no' => 326.0,
                    'parent_id' => 316.0,
                    'value' => 'Phenolic',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            326 =>
                array (
                    's_no' => 327.0,
                    'parent_id' => 316.0,
                    'value' => 'Burned',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            327 =>
                array (
                    's_no' => 328.0,
                    'parent_id' => 316.0,
                    'value' => 'Resinous',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            328 =>
                array (
                    's_no' => 329.0,
                    'parent_id' => 325.0,
                    'value' => 'Cedar',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            329 =>
                array (
                    's_no' => 330.0,
                    'parent_id' => 325.0,
                    'value' => 'Oak',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            330 =>
                array (
                    's_no' => 331.0,
                    'parent_id' => 325.0,
                    'value' => 'Pencil Shavings',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            331 =>
                array (
                    's_no' => 332.0,
                    'parent_id' => 325.0,
                    'value' => 'Woody Tobacco',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            332 =>
                array (
                    's_no' => 333.0,
                    'parent_id' => 325.0,
                    'value' => 'Sandalwood',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            333 =>
                array (
                    's_no' => 334.0,
                    'parent_id' => 325.0,
                    'value' => 'Pine cones',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            334 =>
                array (
                    's_no' => 335.0,
                    'parent_id' => 325.0,
                    'value' => 'Eucalyptus',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            335 =>
                array (
                    's_no' => 336.0,
                    'parent_id' => 326.0,
                    'value' => 'Aromatic crystals (Phenolic)',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            336 =>
                array (
                    's_no' => 337.0,
                    'parent_id' => 327.0,
                    'value' => 'Smoky Wood',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            337 =>
                array (
                    's_no' => 338.0,
                    'parent_id' => 327.0,
                    'value' => 'Ashy Wood',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            338 =>
                array (
                    's_no' => 339.0,
                    'parent_id' => 328.0,
                    'value' => 'Wax Like',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            339 =>
                array (
                    's_no' => 340.0,
                    'parent_id' => 328.0,
                    'value' => 'Pine Sap',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            340 =>
                array (
                    's_no' => 341.0,
                    'parent_id' => 317.0,
                    'value' => 'Moss',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            341 =>
                array (
                    's_no' => 342.0,
                    'parent_id' => 317.0,
                    'value' => 'Humus',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            342 =>
                array (
                    's_no' => 343.0,
                    'parent_id' => 317.0,
                    'value' => 'Mushrooms',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            343 =>
                array (
                    's_no' => 344.0,
                    'parent_id' => 317.0,
                    'value' => 'Fungi',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            344 =>
                array (
                    's_no' => 345.0,
                    'parent_id' => 317.0,
                    'value' => 'Yeast',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            345 =>
                array (
                    's_no' => 346.0,
                    'parent_id' => 317.0,
                    'value' => 'Truffle',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            346 =>
                array (
                    's_no' => 347.0,
                    'parent_id' => 317.0,
                    'value' => 'Undergrowth',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            347 =>
                array (
                    's_no' => 348.0,
                    'parent_id' => 9.0,
                    'value' => 'Petroleum',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            348 =>
                array (
                    's_no' => 349.0,
                    'parent_id' => 9.0,
                    'value' => 'Sulphur',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            349 =>
                array (
                    's_no' => 350.0,
                    'parent_id' => 9.0,
                    'value' => 'Iodine',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            350 =>
                array (
                    's_no' => 351.0,
                    'parent_id' => 9.0,
                    'value' => 'Medicinal',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            351 =>
                array (
                    's_no' => 352.0,
                    'parent_id' => 348.0,
                    'value' => 'Tar',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            352 =>
                array (
                    's_no' => 353.0,
                    'parent_id' => 348.0,
                    'value' => 'Plastic',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            353 =>
                array (
                    's_no' => 354.0,
                    'parent_id' => 348.0,
                    'value' => 'Kerosene',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            354 =>
                array (
                    's_no' => 355.0,
                    'parent_id' => 348.0,
                    'value' => 'Diesel',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            355 =>
                array (
                    's_no' => 356.0,
                    'parent_id' => 348.0,
                    'value' => 'Chlorine',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            356 =>
                array (
                    's_no' => 357.0,
                    'parent_id' => 348.0,
                    'value' => 'Metallic',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            357 =>
                array (
                    's_no' => 358.0,
                    'parent_id' => 349.0,
                    'value' => 'Egg',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            358 =>
                array (
                    's_no' => 359.0,
                    'parent_id' => 349.0,
                    'value' => 'Black Salt (Chemical)',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            359 =>
                array (
                    's_no' => 360.0,
                    'parent_id' => 349.0,
                    'value' => 'Garlic',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            360 =>
                array (
                    's_no' => 361.0,
                    'parent_id' => 349.0,
                    'value' => 'Onions',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            361 =>
                array (
                    's_no' => 362.0,
                    'parent_id' => 349.0,
                    'value' => 'Legumes',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            362 =>
                array (
                    's_no' => 363.0,
                    'parent_id' => 349.0,
                    'value' => 'Cabbage',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            363 =>
                array (
                    's_no' => 364.0,
                    'parent_id' => 349.0,
                    'value' => 'Brocolli',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            364 =>
                array (
                    's_no' => 365.0,
                    'parent_id' => 349.0,
                    'value' => 'Cauliflower',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            365 =>
                array (
                    's_no' => 366.0,
                    'parent_id' => 349.0,
                    'value' => 'Rubbery',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            366 =>
                array (
                    's_no' => 367.0,
                    'parent_id' => 349.0,
                    'value' => 'Natural Gas',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            367 =>
                array (
                    's_no' => 368.0,
                    'parent_id' => 349.0,
                    'value' => 'Burnt Match Stick',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            368 =>
                array (
                    's_no' => 369.0,
                    'parent_id' => 349.0,
                    'value' => 'SO2',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            369 =>
                array (
                    's_no' => 370.0,
                    'parent_id' => 349.0,
                    'value' => 'Mercaptan',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            370 =>
                array (
                    's_no' => 371.0,
                    'parent_id' => 349.0,
                    'value' => 'Skunk',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            371 =>
                array (
                    's_no' => 372.0,
                    'parent_id' => 349.0,
                    'value' => 'Wet Dog (Chemical)',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            372 =>
                array (
                    's_no' => 373.0,
                    'parent_id' => 350.0,
                    'value' => 'Seaweed',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            373 =>
                array (
                    's_no' => 374.0,
                    'parent_id' => 350.0,
                    'value' => 'Skrimps',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            374 =>
                array (
                    's_no' => 375.0,
                    'parent_id' => 350.0,
                    'value' => 'Tuna',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            375 =>
                array (
                    's_no' => 376.0,
                    'parent_id' => 351.0,
                    'value' => 'Band Aid',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            376 =>
                array (
                    's_no' => 377.0,
                    'parent_id' => 10.0,
                    'value' => 'Rotten Eggs',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            377 =>
                array (
                    's_no' => 378.0,
                    'parent_id' => 10.0,
                    'value' => 'Boiled Cabbage',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            378 =>
                array (
                    's_no' => 379.0,
                    'parent_id' => 10.0,
                    'value' => 'Rotting Veges / Marshy',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            379 =>
                array (
                    's_no' => 380.0,
                    'parent_id' => 10.0,
                    'value' => 'Sewer Gas',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            380 =>
                array (
                    's_no' => 381.0,
                    'parent_id' => 10.0,
                    'value' => 'Rotting Fish',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            381 =>
                array (
                    's_no' => 382.0,
                    'parent_id' => 10.0,
                    'value' => 'Rotting Meat',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            382 =>
                array (
                    's_no' => 383.0,
                    'parent_id' => 1.0,
                    'value' => 'Seeds',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            383 =>
                array (
                    's_no' => 384.0,
                    'parent_id' => 383.0,
                    'value' => 'Regular Seeds',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            384 =>
                array (
                    's_no' => 385.0,
                    'parent_id' => 383.0,
                    'value' => 'Popped Seeds',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
            385 =>
                array (
                    's_no' => 386.0,
                    'parent_id' => 383.0,
                    'value' => 'Roasted Seeds',
                    'type' => 'AROMA',
                    'is_active' => 1.0,
                ),
        );

        $data = [];

        foreach ($extra as $item)
        {
            $data[] = ['type'=>'AROMA','s_no'=>$item['s_no'],'parent_id'=>$item['parent_id'],'value'=>$item['value'],'is_active'=>$item['is_active']];
        }
        \DB::table('global_nested_option')->insert($data);

    }
}
