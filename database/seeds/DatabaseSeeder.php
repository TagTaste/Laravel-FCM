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
        $this->call(ProfileTypeTableSeeder::class);
        $this->call(AllergensTableSeeder::class);
        $this->call(CuisineTableSeeder::class);
        $this->call(EstablishmentTypeTableSeeder::class);
        $this->call(ProductReviewBatchesColorTableSeeder::class);
        $this->call(CollaborateCategoryTableSeeder::class);
        $this->call(CollaborateTastingMethodologyTableSeeder::class);
        $this->call(CollaborateTypeTableSeeder::class);
        $this->call(Company_statusTableSeeder::class);
        $this->call(companyNewStatusTableSeeder::class);
        $this->call(companyNewTypesTableSeeder::class);
        $this->call(CompanyTypeAddTableSeeder::class);
        $this->call(ConstantVariableTableSeeder::class);
        $this->call(DefaultAdminValues::class);
        $this->call(DefaultRoleUserValues::class);
        //$this->call(AttributeValueTableSeeder::class);//requires cuisine and establishment and ProfileAttribute
        $this->call(DesignationTableSeeder::class);
        // $this->call(FoodieTypeTableSeeder::class);
        $this->call(SpecializationsTableSeeder::class);
        $this->call(SpecializationAddDescriptionTableSeeder::class);
        $this->call(InsertImageAllTableSeeder::class);//requires specializations
        $this->call(JobTypeAddSeeder::class);
        $this->call(JobTypeTableSeeder::class);
        $this->call(MessageTypeTableSeeder::class);
        $this->call(ModuleVersionSeederTable::class);
        $this->call(OccupationsTableSeeder::class);
        $this->call(OnboardingInterestCollectionTableSeeder::class);
        $this->call(OnboardingSkillsTable::class);
        $this->call(PrivacyTableSeeder::class);
        $this->call(ProductCategoryTableSeeder::class);
        $this->call(ProductSubCategoryTableSeeder::class);
        $this->call(ProductCategoryPivotSubCategoryTableSeeder::class);
        $this->call(PublicReviewInsertNestedOptionTableSeeder::class);
        $this->call(PublicReviewKeywordTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(TemplateTypeTableSeeder::class);
        //$this->call(TemplateTableSeeder::class);
        $this->call(UnsubscribeReasonSeeder::class);
//        $this->call(ProfileAttributeIdSeeder::class);
//        $this->call(ProfileAttributeTableSeeder::class);//requires profile type and admin generation
    }
}
