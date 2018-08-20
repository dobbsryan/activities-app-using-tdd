<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocationIdColumnToIndividualAttendanceRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // note mainly just for seeding and because
         // it shouldn't be nullable
        $value = 1;
        Schema::table('individual_attendance_records', function (Blueprint $table) use ($value) {
            $table->integer('location_id')->default($value);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('individual_attendance_records', function (Blueprint $table) {
            $table->dropColumn('location_id');
        });
    }
}
