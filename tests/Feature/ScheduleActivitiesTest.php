<?php

namespace Tests\Feature;

use App\Activity;
use Carbon\Carbon;
use Tests\TestCase;
use App\ScheduledActivity;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScheduleActivitiesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_can_view_the_list_of_activities()
    {
        // arrange
        $user = factory('App\User')->create();
        $this->be($user);
        // create a couple of activities
        $activity = Activity::create([
            'name' => 'Move and Groove',
            'description' => 'Lorem ipsum dolor sit amet',
            'supplies' => 'adipisicing elit',
            'instructions' => 'Iusto dicta aut dolor molestias veritatis asperiores, repellendus numquam magni maxime voluptas dolores odit eligendi.'
        ]);

        $activity2 = Activity::create([
            'name' => 'Card Sharks',
            'description' => 'Ipsum dolor sit amet',
            'supplies' => 'elit',
            'instructions' => 'Molestias veritatis asperiores, repellendus numquam magni maxime voluptas dolores odit eligendi.'
        ]);

        // act
        // view the activities
        $response = $this->get('/schedule-activities');

        // assert
        // verify that activities are visible
        $response->assertStatus(200);
        $response->assertSee('Move and Groove');
        $response->assertSee('Card Sharks'); 

    }

    // /** @test */
    // function user_can_view_the_currently_scheduled_activitites_for_default_date()
    // {
    //     // currently default time is "today" upon opening the view
    //     // currently the info being asserted is also present on
    //     // view for test above: user_can_view_the_list_of_activities
        
    //     // need list of all activities for today for viewing by user

    //     // arrange
    //     // creating activities for today (2) and tomorrow (1)
    //     $activity = Activity::create([
    //         'name' => 'Activity 1 - Today',
    //         'description' => 'Description 1 - Today',
    //         'supplies' => 'Supplies 1 - Today',
    //         'instructions' => 'Instructions 1 - Today'
    //     ]);
    //     $date = Carbon::parse('today 9:00am');
    //     $activity->isScheduledFor($date);

    //     $activity2 = Activity::create([
    //         'name' => 'Activity 2 - Today',
    //         'description' => 'Description 2 - Today',
    //         'supplies' => 'Supplies 2 - Today',
    //         'instructions' => 'Instructions 2 - Today'
    //     ]);
    //     $date2 = Carbon::parse('today 4:00pm');
    //     $activity2->isScheduledFor($date2);

    //     $activity3 = Activity::create([
    //         'name' => 'Activity 3 - Tomorrow',
    //         'description' => 'Description 3 - Tomorrow',
    //         'supplies' => 'Supplies 3 - Tomorrow',
    //         'instructions' => 'Instructions 3 - Tomorrow'
    //     ]);
    //     $date3 = Carbon::parse('tomorrow 9:00am');
    //     $activity3->isScheduledFor($date3);

    //     // act
    //     // load the activities for today only
    //     $response = $this->get('/schedule-activities');

    //     // assert
    //     // check those in the data returned to the view
    //     $response->assertJson([
    //         [
    //             'name' => 'Activity 1 - Today',
    //             'activity_id' => 1,
    //             'date' => $date
    //         ],
    //         [
    //             'name' => 'Activity 2 - Today',
    //             'activity_id' => 2,
    //             'date' => $date2
    //         ],
    //     ]);
    // }

    /** @test */
    function user_can_schedule_an_activity()
    {
        // arrange
        $user = factory('App\User')->create();
        $this->be($user);
        // create an activity
        $activity = Activity::create([
            'name' => 'Move and Groove',
            'domain_id' => 1,
            'description' => 'Description for M&G',
            'supplies' => 'Supplies for M&G',
            'instructions' => 'Instructions for M&G.'
        ]);

        // indicate the date to be passed as well
        // $date = Carbon::parse('October 14, 2017 9:00am')->format('m-d-Y');
        $date = Carbon::parse('October 14, 2017 9:00am');

        // $time = '10:00';

        // act
        // schedule that activity for that date/time
        // (NOTE: will require a relationship)
        // "an activity has many date/times"
        // (an activity can be scheduled multiple times)
        $activity->isScheduledFor($date);

        // assert
        // check the database for the activity
        $this->assertDatabaseHas('scheduled_activities', [
            'activity_id' => 1,
            'date' => $date
        ]);
    }

    // /** @test */
    // function user_can_update_a_scheduled_activity()
    // {   
    //     // setting up the world
    //     // (lots of code from other tests to eventually be extracted)
    //     // creating activities for today (2)
    //     $activity = Activity::create([
    //         'name' => 'Activity 1 - Today',
    //         'description' => 'Description 1 - Today',
    //         'supplies' => 'Supplies 1 - Today',
    //         'instructions' => 'Instructions 1 - Today'
    //     ]);
    //     $date = Carbon::parse('today 9:00am');
    //     $activity->isScheduledFor($date);

    //     $activity2 = Activity::create([
    //         'name' => 'Activity 2 - Today',
    //         'description' => 'Description 2 - Today',
    //         'supplies' => 'Supplies 2 - Today',
    //         'instructions' => 'Instructions 2 - Today'
    //     ]);
    //     $date2 = Carbon::parse('today 4:00pm');
    //     $activity2->isScheduledFor($date2);

    //     // checking that the database has record of those two activities
    //     $this->assertDatabaseHas('scheduled_activities', [
    //         'activity_id' => 1,
    //         'date' => $date
    //     ]);
    //     $this->assertDatabaseHas('scheduled_activities', [
    //         'activity_id' => 2,
    //         'date' => $date2
    //     ]);

    //     // now...
    //     // I want hit a patch endpoint with a couple changes
    //     $today = Carbon::parse('today');
    //     $response = $this->patch("/schedule-activities", [
    //         'title' => 'New title',
    //         // 'subtitle' => 'New subtitle',
    //         // 'additional_information' => 'New additional information',
    //         // 'date' => '2018-12-12',
    //         // 'time' => '8:00pm',
    //         // 'venue' => 'New venue',
    //         // 'venue_address' => 'New address',
    //         // 'city' => 'New city',
    //         // 'state' => 'New state',
    //         // 'zip' => '99999',
    //         // 'ticket_price' => '72.50',
    //         // 'ticket_quantity' => '10',
    //     ]);

    // }

    // NOTE: going to want to test that the user can see
    //       a scheduled activity if one has been
    //       assigned to datetime being tested
    /** @test */
    // function user_can_see_scheduled_activities_for_selected_date_if_any_have_been_scheduled()
    // {
    //     // arrange
    //     // create a couple of activities
    //     $activity = Activity::create([
    //         'name' => 'Move and Groove',
    //         'description' => 'Lorem ipsum dolor sit amet',
    //         'supplies' => 'adipisicing elit',
    //         'instructions' => 'Iusto dicta aut dolor molestias veritatis asperiores, repellendus numquam magni maxime voluptas dolores odit eligendi.'
    //     ]);

    //     $activity2 = Activity::create([
    //         'name' => 'Card Sharks',
    //         'description' => 'Ipsum dolor sit amet',
    //         'supplies' => 'elit',
    //         'instructions' => 'Molestias veritatis asperiores, repellendus numquam magni maxime voluptas dolores odit eligendi.'
    //     ]);

    //     // assign/schedule activities for a specific date
    //     $scheduledActivity = ScheduledActivity::create([
    //         'activities_id' => $activity->id,
    //         'date' => Carbon::parse('today 9:00am'),
    //     ]);

    //     //  act
    //     //  go to Schedule Activities view and indicate that date
    //     $response = $this->get('/schedule-activities');
    //     // dd($response);

    //     //  assert
    //     //  check that you see the scheduled activities
    //     //$response->assertJson([])
    //     $response->assertJson([
    //         'time' => "9:00", 'name' => 'Move and Groove'
    //     ]);
    //     $response->assertStatus(200);
    //     $response->assertSee('9:00'); // not going to be a good test, always shows the times in the view

    // }
}
