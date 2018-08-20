<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduledActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduled_activities', function (Blueprint $table) {
            $table->increments('id');
            // $table->string('activity');
            $table->string('activity_id');
            $table->datetime('date');
            //$table->primary(['activity_id', 'date']);
            // NOTE: primary key with datetime included does not appear to work and
            // probably is not recommended; however, will need to look into option
            // for when other users/facilities are added into the mix; there will
            // have to be a way to duplicate activity_id and date along with
            // some other field as a primary key of sorts
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scheduled_activities');
    }
}
