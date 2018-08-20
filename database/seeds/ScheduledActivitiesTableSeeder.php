<?php

use Illuminate\Database\Seeder;

class ScheduledActivitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\ScheduledActivity::class)->create();
    }
}
