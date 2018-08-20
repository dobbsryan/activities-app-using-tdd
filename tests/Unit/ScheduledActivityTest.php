<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\ScheduledActivity;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScheduledActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function can_get_formatted_date()
    {
        // arrange
        // create a scheduled activity with known date time
        $scheduledActivity = factory(ScheduledActivity::class)->create([
            'date' => Carbon::parse('2017-12-01 11:00am'),
        ]);
        
        // act
        // retrieve the formatted date
        $date = $scheduledActivity->formatted_date_time;

        // assert
        // verify the date is formatted as expected
        $this->assertEquals('December 1, 2017 11:00am', $date);

    }
}
