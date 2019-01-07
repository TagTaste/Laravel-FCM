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
        $data[] = ['name'=>'Home','compatible_version'=>'42','latest_version'=>'42','platform'=>'android'];
        $data[] = ['name'=>'Explore','compatible_version'=>'42','latest_version'=>'42','platform'=>'android'];
        $data[] = ['name'=>'Reviews','compatible_version'=>'42','latest_version'=>'42','platform'=>'android'];
        $data[] = ['name'=>'Chat','compatible_version'=>'42','latest_version'=>'42','platform'=>'android'];
        $data[] = ['name'=>'Settings','compatible_version'=>'42','latest_version'=>'42','platform'=>'android'];
        $data[] = ['name'=>'Overall','compatible_version'=>'42','latest_version'=>'42','platform'=>'android'];

        //for ios
        $data[] = ['name'=>'Home','compatible_version'=>'42','latest_version'=>'42','platform'=>'ios'];
        $data[] = ['name'=>'Explore','compatible_version'=>'42','latest_version'=>'42','platform'=>'ios'];
        $data[] = ['name'=>'Reviews','compatible_version'=>'42','latest_version'=>'42','platform'=>'ios'];
        $data[] = ['name'=>'Chat','compatible_version'=>'42','latest_version'=>'42','platform'=>'ios'];
        $data[] = ['name'=>'Settings','compatible_version'=>'42','latest_version'=>'42','platform'=>'ios'];
        $data[] = ['name'=>'Overall','compatible_version'=>'42','latest_version'=>'42','platform'=>'ios'];

    }
}
