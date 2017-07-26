<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterJobAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("jobs",function(Blueprint $table){
            $table->string('location')->change();
            $table->text('why_us')->nullable();
            $table->float('salary_min')->unsigned()->nullable();
            $table->float('salary_max')->unsigned()->nullable();
            $table->float('experience_min')->unsigned();
            $table->float('experience_max')->unsigned();
            $table->string('joining');
            $table->boolean("resume_required")->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs',function(Blueprint $table){
            $table->string('location')->nullable()->change();
            $table->dropColumn('why_us');
            $table->dropColumn('salary_min');
            $table->dropColumn('salary_max');
            $table->dropColumn('experience_min');
            $table->dropColumn('experience_max');
            $table->dropColumn('joining');
            $table->dropColumn("resume_required");
        });
    }
}
