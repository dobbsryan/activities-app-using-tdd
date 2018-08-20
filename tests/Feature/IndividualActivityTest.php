<?php

namespace Tests\Feature;

use App\Activity;
use App\Resident;
use Carbon\Carbon;
use Tests\TestCase;
use App\IndividualAttendanceRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndividualActivityTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    function authenticated_user_can_view_the_dropdown_for_residents_and_activities()
    {    
        // arrange
        $user = factory('App\User')->create();
        $this->be($user);

        // create a list of residents
        $resident = Resident::create([
            'first_name' => 'Gene',
            'last_name' => 'Porter',
            'location_id' => 1,
        ]);
        $resident2 = Resident::create([
            'first_name' => 'Bob',
            'last_name' => 'Simmons',
            'location_id' => 1,
        ]);  

        // create a list of activities
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
        // view the list of residents
        // $response = $this->get('/individual-activities');
        $response = $this->withSession(['location_id' => 1])->get('/individual-activities');

        // assert
        // verify that certain residents are visible
        // check/verify endpoint
        $response->assertStatus(200);
        $response->assertSee('Gene');
        $response->assertSee('Porter');
        $response->assertSee('Bob');
        $response->assertSee('Simmons');
        $response->assertSee('Move and Groove');
        $response->assertSee('Card Sharks');
    }    

    /** @test */
    function authenticated_user_can_see_current_attendance_for_individual_activities_for_default_or_selected_date()
    {
        // NOTE - API-ish (secondary call from axios upon initial page load)
        // this is for getting the section below that shows any individual
        // activities already scheduled for default load date
        // arrange
        $user = factory('App\User')->create();
        $this->be($user);
        // need a couple example residents
        $resident = Resident::create([
            'first_name' => 'Ryan',
            'last_name' => 'Dobbs',
            'location_id' => 1,
        ]);
        $resident2 = Resident::create([
            'first_name' => 'Rachel',
            'last_name' => 'McAdams',
            'location_id' => 1,
        ]);
        // need a couple example activities
        $activity = Activity::create([
            'name' => 'Move and Groove',
        ]);
        $activity2 = Activity::create([
            'name' => 'Acting 101',
        ]);
        // need date
        $date = Carbon::now('America/Denver');
        //$dateAsString = $date->format('Y-m-d');

        // create the individual scheduled activity
        $individualAttendanceRecord = IndividualAttendanceRecord::create([
            'resident_id' => $resident->id,
            'activity_id' => $activity->id,
            'date' => $date,
            'location_id' => 1,
        ]);
        $individualAttendanceRecord2 = IndividualAttendanceRecord::create([
            'resident_id' => $resident2->id,
            'activity_id' => $activity2->id,
            'date' => $date,
            'location_id' => 1,
        ]);

        // act
        // view the individual activity attendance data
        $response = $this->withSession(['location_id' => 1])->post('/individual-activity-attendance', [
            'date' => $date
        ]);
        
        // assert that you can see the data
        $response->assertStatus(200);
        $response->assertJson([
            [
                "id" => 1,
                "resident_id" => "1",
                "activity_id" => "1",
                "date" => $date,
                "resident" => [
                    "id" => 1,
                    "first_name" => "Ryan",
                    "last_name" => "Dobbs",
                ],
                "activity" => [
                    "id" => 1,
                    "name" => "Move and Groove",
                    "description" => null,
                    "supplies" => null,
                    "instructions" => null,
                    "domain_id" => null
                ]
            ],
            [
                "id" => 2,
                "resident_id" => "2",
                "activity_id" => "2",
                "date" => $date,
                "resident" => [
                    "id" => 2,
                    "first_name" => "Rachel",
                    "last_name" => "McAdams",
                ],
                "activity" => [
                    "id" => 2,
                    "name" => "Acting 101",
                    "description" => null,
                    "supplies" => null,
                    "instructions" => null,
                    "domain_id" => null
                ]
            ]
            // original - before json structure returned from controller method
            // [
            //     'resident' => [
            //         'id' => 1,
            //         'first_name' => 'Ryan',
            //         'last_name' => 'Dobbs',
            //     ],
            //     'activity' => [
            //         'id' => 1,
            //         'name' => 'Move and Groove'
            //     ]
            // ],
            // [
            //     'resident' => [
            //         'id' => 2,
            //         'first_name' => 'Rachel',
            //         'last_name' => 'McAdams',
            //     ],
            //     'activity' => [
            //         'id' => 2,
            //         'name' => 'Acting 101'
            //     ]
            // ],
            // 'date' => $dateAsString,
        ]);
    }

    /** @test */
    function authenticated_user_can_delete_an_individual_activities()
    {
        // NOTE - API endpoint approach
        // arrange
        $user = factory('App\User')->create();
        $this->be($user);
        // need a couple example residents
        $resident = Resident::create([
            'first_name' => 'Ryan',
            'last_name' => 'Dobbs',
        ]);
        $resident2 = Resident::create([
            'first_name' => 'Rachel',
            'last_name' => 'McAdams',
        ]);
        // need a couple example activities
        $activity = Activity::create([
            'name' => 'Move and Groove',
        ]);
        $activity2 = Activity::create([
            'name' => 'Acting 101',
        ]);
        // need date
        $date = Carbon::now('America/Denver');
        $dateAsString = $date->format('Y-m-d');

        // create the individual scheduled activity
        $individualAttendanceRecord = IndividualAttendanceRecord::create([
            'resident_id' => $resident->id,
            'activity_id' => $activity->id,
            'date' => $dateAsString,
        ]);
        $individualAttendanceRecord2 = IndividualAttendanceRecord::create([
            'resident_id' => $resident2->id,
            'activity_id' => $activity2->id,
            'date' => $dateAsString,
        ]);
        
        // act
        // delete record for Ryan Dobbs
        //$individualAttendanceRecord->delete();
        
        $response = $this->json('DELETE', '/individual-activity-attendance/' . $individualAttendanceRecord->id);        
        // $response = $this->destroy('/individual-activity-attendance/');
        
        // assert that you can see the data
        $this->assertDatabaseMissing('individual_attendance_records', ['id' => $individualAttendanceRecord->id]);
    }

    /** @test */
    function authenticated_user_can_add_an_attendance_record()
    {
        // arrange
        $user = factory('App\User')->create();
        $this->be($user);
        // date
        $date = Carbon::now('America/Denver');
        $dateAsString = $date->format('Y-m-d');
        // resident
        $resident = Resident::create([
            'first_name' => 'Ryan',
            'last_name' => 'Dobbs',
            'location_id' => 1,
        ]);
        // activity
        $activity = Activity::create([
            'name' => 'Move and Groove',
        ]);

        // act
        // hit the API endpoint
        $response = $this->withSession(['location_id' => 1])->post('/individual-activity-attendance/store', [
            'resident_id' => $resident->id,
            'activity_id' => $activity->id,
            'date' => $dateAsString,
        ]);

        // assert
        // check that record is in table
        $this->assertDatabaseHas('individual_attendance_records', [
            'resident_id' => $resident->id,
            'activity_id' => $activity->id,
            'date' => $dateAsString,
        ]);
    }
}
