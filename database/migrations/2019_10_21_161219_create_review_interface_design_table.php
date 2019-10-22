 <?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewInterfaceDesignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review_interface_design', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('postion')->nullable()->default(0);
            $table->unsignedInteger('ui_type')->nullable()->default(0);
            $table->text('ui_style')->nullable()->default(null);
            $table->unsignedInteger('collection_id')->unsigned()->nullable()->default(null);
            $table->string('collection_type', 50);
            $table->boolean('is_active')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('review_interface_design');
    }
}
