<?php

use Illuminate\Database\Seeder;

class InsertImageAllTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = \DB::table('specializations')->get();

        foreach ($data as $datum)
        {
            $imageUrl = "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/specializations/specializations_".$datum->id."_".$datum->name.".png";
            \DB::table('specializations')->where('id',$datum->id)->update(['image'=>$imageUrl]);
        }

        $data = \DB::table('occupations')->get();

        foreach ($data as $datum)
        {
            $imageUrl = "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/occupations/occupations_".$datum->id."_".$datum->name.".png";
            \DB::table('occupations')->where('id',$datum->id)->update(['image'=>$imageUrl]);
        }

        $data = \DB::table('allergens')->get();

        foreach ($data as $datum)
        {
            $imageUrl = "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/allergens/allergens_".$datum->id."_".$datum->name.".png";
            \DB::table('allergens')->where('id',$datum->id)->update(['image'=>$imageUrl]);
        }
    }
}
