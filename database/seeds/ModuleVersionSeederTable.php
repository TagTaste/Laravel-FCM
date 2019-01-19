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
        $data[] = ['module_name'=>'Home','compatible_version'=>'40','latest_version'=>'52','platform'=>'android','title'=>"New version available","description"=>"Please update the app to the latest version to perform this action."];
        $data[] = ['module_name'=>'Explore','compatible_version'=>'40','latest_version'=>'52','platform'=>'android','title'=>"New version available","description"=>"Please update the app to the latest version to perform this action."];
        $data[] = ['module_name'=>'Reviews','compatible_version'=>'40','latest_version'=>'52','platform'=>'android','title'=>"New version available","description"=>"Please update the app to the latest version to perform this action."];
        $data[] = ['module_name'=>'Chat','compatible_version'=>'40','latest_version'=>'52','platform'=>'android','title'=>"New version available","description"=>"Please update the app to the latest version to perform this action."];
        $data[] = ['module_name'=>'Settings','compatible_version'=>'40','latest_version'=>'52','platform'=>'android','title'=>"New version available","description"=>"Please update the app to the latest version to perform this action."];
        $data[] = ['module_name'=>'Profile','compatible_version'=>'40','latest_version'=>'52','platform'=>'android','title'=>"New version available","description"=>"Please update the app to the latest version to perform this action."];
        $data[] = ['module_name'=>'Private-Collaborate','compatible_version'=>'52','latest_version'=>'52','platform'=>'android','title'=>"New version available","description"=>"Please update the app to the latest version to perform this action."];
        $data[] = ['module_name'=>'Collaborate','compatible_version'=>'40','latest_version'=>'52','platform'=>'android','title'=>"New version available","description"=>"Please update the app to the latest version to perform this action."];
        $data[] = ['module_name'=>'Notification','compatible_version'=>'40','latest_version'=>'52','platform'=>'android','title'=>"New version available","description"=>"Please update the app to the latest version to perform this action."];
        $data[] = ['module_name'=>'Onboarding','compatible_version'=>'40','latest_version'=>'52','platform'=>'android','title'=>"New version available","description"=>"Please update the app to the latest version to perform this action."];
        $data[] = ['module_name'=>'Company','compatible_version'=>'40','latest_version'=>'52','platform'=>'android','title'=>"New version available","description"=>"Please update the app to the latest version to perform this action."];
        $data[] = ['module_name'=>'Private-Collaborate-Review','compatible_version'=>'40','latest_version'=>'52','platform'=>'android','title'=>"New version available","description"=>"Please update the app to the latest version to perform this action."];
        $data[] = ['module_name'=>'Overall','compatible_version'=>'40','latest_version'=>'52','platform'=>'android','title'=>"New version available","description"=>"Please update the app to the latest version to perform this action."];
        $data[] = ['module_name'=>'Public-Review','compatible_version'=>'40','latest_version'=>'52','platform'=>'android','title'=>"New version available","description"=>"Please update the app to the latest version to perform this action."];
        $data[] = ['module_name'=>'Public-Review-Process','compatible_version'=>'40','latest_version'=>'52','platform'=>'android','title'=>"New version available","description"=>"Please update the app to the latest version to perform this action."];

        //for ios
        $data[] = ['module_name'=>'Home','compatible_version'=>'3.0.1','latest_version'=>'3.0.1','platform'=>'ios','title'=>"New version available","description"=>"Please update the app to the latest version to perform this action."];
        $data[] = ['module_name'=>'Explore','compatible_version'=>'3.0.1','latest_version'=>'3.0.1','platform'=>'ios','title'=>"New version available","description"=>"Please update the app to the latest version to perform this action."];
        $data[] = ['module_name'=>'Reviews','compatible_version'=>'3.0.1','latest_version'=>'3.0.1','platform'=>'ios','title'=>"New version available","description"=>"Please update the app to the latest version to perform this action."];
        $data[] = ['module_name'=>'Chat','compatible_version'=>'3.0.1','latest_version'=>'3.0.1','platform'=>'ios','title'=>"New version available","description"=>"Please update the app to the latest version to perform this action."];
        $data[] = ['module_name'=>'Settings','compatible_version'=>'3.0.1','latest_version'=>'3.0.1','platform'=>'ios','title'=>"New version available","description"=>"Please update the app to the latest version to perform this action."];
        $data[] = ['module_name'=>'Profile','compatible_version'=>'3.0.1','latest_version'=>'3.0.1','platform'=>'ios','title'=>"New version available","description"=>"Please update the app to the latest version to perform this action."];
        $data[] = ['module_name'=>'Private-Collaborate','compatible_version'=>'3.0.1','latest_version'=>'3.0.1','platform'=>'ios','title'=>"New version available","description"=>"Please update the app to the latest version to perform this action."];
        $data[] = ['module_name'=>'Collaborate','compatible_version'=>'3.0.1','latest_version'=>'3.0.1','platform'=>'ios','title'=>"New version available","description"=>"Please update the app to the latest version to perform this action."];
        $data[] = ['module_name'=>'Notification','compatible_version'=>'3.0.1','latest_version'=>'3.0.1','platform'=>'ios','title'=>"New version available","description"=>"Please update the app to the latest version to perform this action."];
        $data[] = ['module_name'=>'Onboarding','compatible_version'=>'3.0.1','latest_version'=>'3.0.1','platform'=>'ios','title'=>"New version available","description"=>"Please update the app to the latest version to perform this action."];
        $data[] = ['module_name'=>'Company','compatible_version'=>'3.0.1','latest_version'=>'3.0.1','platform'=>'ios','title'=>"New version available","description"=>"Please update the app to the latest version to perform this action."];
        $data[] = ['module_name'=>'Private-Collaborate-Review','compatible_version'=>'3.0.1','latest_version'=>'3.0.1','platform'=>'ios','title'=>"New version available","description"=>"Please update the app to the latest version to perform this action."];
        $data[] = ['module_name'=>'Overall','compatible_version'=>'3.0.1','latest_version'=>'3.0.1','platform'=>'ios','title'=>"New version available","description"=>"Please update the app to the latest version to perform this action."];
        $data[] = ['module_name'=>'Public-Review','compatible_version'=>'3.0.1','latest_version'=>'3.0.1','platform'=>'ios','title'=>"New version available","description"=>"Please update the app to the latest version to perform this action."];
        $data[] = ['module_name'=>'Public-Review-Process','compatible_version'=>'3.0.1','latest_version'=>'3.0.1','platform'=>'ios','title'=>"New version available","description"=>"Please update the app to the latest version to perform this action."];

        \DB::table('module_versions')->insert($data);

    }
}
