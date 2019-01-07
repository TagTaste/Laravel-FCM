<?php

use Illuminate\Database\Seeder;

class ModuleVersionSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];

        //for android
        $data[] = ['module_name'=>'Home','compatible_version'=>'42','latest_version'=>'42','platform'=>'android'];
        $data[] = ['module_name'=>'Explore','compatible_version'=>'42','latest_version'=>'42','platform'=>'android'];
        $data[] = ['module_name'=>'Reviews','compatible_version'=>'42','latest_version'=>'42','platform'=>'android'];
        $data[] = ['module_name'=>'Chat','compatible_version'=>'42','latest_version'=>'42','platform'=>'android'];
        $data[] = ['module_name'=>'Settings','compatible_version'=>'42','latest_version'=>'42','platform'=>'android'];
        $data[] = ['module_name'=>'Overall','compatible_version'=>'42','latest_version'=>'42','platform'=>'android'];

        //for ios
        $data[] = ['module_name'=>'Home','compatible_version'=>'3.0.1','latest_version'=>'3.0.1','platform'=>'ios'];
        $data[] = ['module_name'=>'Explore','compatible_version'=>'3.0.1','latest_version'=>'3.0.1','platform'=>'ios'];
        $data[] = ['module_name'=>'Reviews','compatible_version'=>'3.0.1','latest_version'=>'3.0.1','platform'=>'ios'];
        $data[] = ['module_name'=>'Chat','compatible_version'=>'3.0.1','latest_version'=>'3.0.1','platform'=>'ios'];
        $data[] = ['module_name'=>'Settings','compatible_version'=>'3.0.1','latest_version'=>'3.0.1','platform'=>'ios'];
        $data[] = ['module_name'=>'Overall','compatible_version'=>'3.0.1','latest_version'=>'3.0.1','platform'=>'ios'];

        \DB::table('module_versions')->insert($data);

    }
}
