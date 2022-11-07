<?php

use Illuminate\Database\Seeder;

class QuestionnaireToolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        echo "seeder start";

        $this->call(QuestionnaireFoodShotPlaceholderSeeder::class);
        $this->call(QuestionnaireHeaderTypeSeeder::class);
        $this->call(QuestionnaireIntensityListSeeder::class);
        $this->call(QuestionnaireOptionTypeSeeder::class);
        $this->call(QuestionnaireQuestionTypeSeeder::class);

        echo "seeder done";
    }
}
