<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use App\Collaborate\Review;
use App\Profile;
use App\PublicReviewProduct\Review as PublicReviewProductReview;
use Illuminate\Support\Facades\Log;

class makePaidTasters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:make-paidtasters';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Users with specific count is made sensory trained and paid taster';

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
        $users = User::where("profiles.is_paid_taster", "=", 0)->join("profiles", "profiles.user_id", "=", "users.id")->select("profiles.*","users.name")->get();
        foreach ($users as $v) {
            $getPrivateReview = Review::where("profile_id", $v->id)->groupBy("collaborate_id", "batch_id")->where("current_status", 3)->get();

            $getPublicCount = PublicReviewProductReview::where("profile_id", $v->id)->groupBy("product_id")->where("current_status", 2)->get();


            if (($getPrivateReview->count() >= config("constant.MINIMUM_PAID_TASTER_PRIVATE_REVIEWS")) || (($getPublicCount->count() + $getPrivateReview->count()) >= config("constant.MINIMUM_PAID_TASTER_REVIEWS"))) {
                Log::info("Profile Id : ".$v->id." Username :" .$v->name." is paid taster and sensory trained now");
                echo $v->id."<br/>";
                Profile::where("id", $v->id)->update(["is_paid_taster" => 1, "is_sensory_trained" => 1]);
            }
        }
        echo "Done";
        return "completed";
    }
}
