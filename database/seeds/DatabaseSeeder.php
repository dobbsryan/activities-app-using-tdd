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
        $this->call(UsersTableSeeder::class);
        $this->call(ScheduledActivitiesTableSeeder::class);
        $this->call(ResidentsTableSeeder::class);
        $this->call(ActivitiesTableSeeder::class);
        $this->call(IndividualAttendanceRecordsTableSeeder::class);
        $this->call(CommentsTableSeeder::class);
        $this->call(LocationsTableSeeder::class);
    }
}
