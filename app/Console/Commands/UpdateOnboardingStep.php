<?php

namespace App\Console\Commands;

use function foo\func;
use Illuminate\Console\Command;

class UpdateOnboardingStep extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UpdateOnboardingStep';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update onboarding step on present code';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \DB::table('profiles')->whereNull('onboarding_step')->orderBy('id')->chunk(100, function ($models) {
            foreach ($models as $model) {
                \DB::table('profiles')->where('id', $model->id)->update(['onboarding_step'=>5]);
            }
        });
    }
}
