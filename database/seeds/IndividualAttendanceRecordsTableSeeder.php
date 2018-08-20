<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class IndividualAttendanceRecordsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //factory(App\IndividualAttendanceRecord::class)->create();

        DB::table('individual_attendance_records')->insert([
            [
                'resident_id' => 1,
                'activity_id' => 1,
                'date' => Carbon::now('America/Denver')->format('Y-m-d'),
            ],
            // [
            //     'resident_id' => 2,
            //     'activity_id' => 2,
            //     'date' => Carbon::now('America/Denver')->format('Y-m-d'),
            // ],
            // [
            //     'resident_id' => 3,
            //     'activity_id' => 3,
            //     'date' => Carbon::now('America/Denver')->format('Y-m-d'),
            // ],
        ]);
    }
}
