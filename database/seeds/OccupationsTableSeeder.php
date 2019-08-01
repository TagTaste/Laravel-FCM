<?php

use Illuminate\Database\Seeder;

class OccupationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $data[] = ['name'=>'Service', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/occupations/occupations_1_Service.png' ];
        $data[] = ['name'=>'Government', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/occupations/occupations_2_Government.png' ];
        $data[] = ['name'=>'Manufacturing', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/occupations/occupations_3_Manufacturing.png' ];
        $data[] = ['name'=>'Entrepreneur', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/occupations/occupations_4_Entrepreneur.png' ];
        $data[] = ['name'=>'Academia', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/occupations/occupations_5_Academia.png' ];
        $data[] = ['name'=>'Student', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/occupations/occupations_6_Student.png' ];
        $data[] = ['name'=>'Home Maker', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/occupations/occupations_7_Home Maker.png' ];
        $data[] = ['name'=>'Farmer', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/occupations/occupations_8_Farmer.png' ];
        $data[] = ['name'=>'Any Other', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/occupations/occupations_9_Any Other.png' ];

        foreach ($data as $key => $value) {
            $found = \DB::table('occupations')->where('name',$value['name'])->count();
            if ($found) {
                $name = $value['name'];
                unset($value['name']);
                \DB::table('occupations')->where('name', $name)->update($value);
            } else {
                \DB::table('occupations')->insert($value);
            }
        }
        \DB::table('occupations')->insert($data);
    }
}
