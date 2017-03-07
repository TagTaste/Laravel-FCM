<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(BasicRoles::class);

        $this->call(DefaultAdminValues::class);
        //$this->call(DefaultRoleUserValues::class);

        $this->call(PrivacyTableSeeder::class);
        //$this->call(AttributeValueTableSeeder::class);
        $this->call(TemplateTypeTableSeeder::class);
        $this->call(TemplateTableSeeder::class);
        
        //profile related
        $this->call(DesignationTableSeeder::class);
    
        //company related
        $this->call(CompanyTypeTableSeeder::class);
    }
}
