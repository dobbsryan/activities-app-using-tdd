<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->primary(['resident_id', 'scheduled_activity_id']);
            $table->integer('resident_id')->unsigned()->index();
            $table->integer('scheduled_activity_id')->unsigned()->index();
            $table->timestamps();

            $table->foreign('resident_id')->references('id')->on('residents');
            $table->foreign('scheduled_activity_id')->references('id')->on('scheduled_activities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_records');
    }
}
