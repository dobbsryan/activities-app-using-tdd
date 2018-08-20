<?php

namespace Tests\Feature;

use App\Domain;
use App\Comment;
use App\Activity;
use App\Resident;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\IndividualAttendanceRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResidentReportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function authenticated_user_can_view_resident_report_general_layout()
    {
        // NOTE: not much going on here; mainly just checking
        // route and view existence as well as list of
        // residents for dropdown select-option
         
        // arrange
        $user = factory('App\User')->create();
        $this->be($user);

        // (besides resident and domain list, the rest will be pulled in via
        //  api by user seletion)
        // need the list of residents
        $resident = Resident::create([
            'first_name' => 'Gene',
            'last_name' => 'Porter',
            'location_id' => 1, 
        ]);
        
        $resident2 = Resident::create([
            'first_name' => 'Ryan',
            'last_name' => 'Dobbs',
            'location_id' => 1,
        ]);
        
        // act
        // hit the endpoint for the view
       $response = $this->withSession(['location_id' => 1])->get('/reports'); 

        // assert
        // assert see resident
        $response->assertStatus(200);
        $response->assertSee('Gene');
        $response->assertSee('Porter');
        $response->assertSee('Ryan');
        $response->assertSee('Dobbs');
    }

    /** @test */
    function authenticated_user_can_retrieve_group_attendance_records_for_resident()
    {
        // arrange
        $user = factory('App\User')->create();
        $this->be($user);
        
        // resident to be tested
        $resident = Resident::create([
            'first_name' => 'Gene',
            'last_name' => 'Porter',
        ]);
        $domain = Domain::create([
            'name' => 'Test Domain Name'
        ]);
        // activity
        $activity = Activity::create([
            'name' => 'Move and Groove',
            'domain_id' => $domain->id
        ]);
        // schedule that activity
        // indicate the date to be passed as well
        $date = Carbon::parse('today 9:00am');
        $scheduledActivity = $activity->isScheduledFor($date);
        //dd($scheduledActivity);
        // check resident in
        $resident->attends($scheduledActivity);
        // formatted date to pass along as "month"
        $dateAsString = $date->format('Y-m');

        // act
        // hit API endpoint to retrieve data and return it
        // will be posting the resident id and date/month
        $response = $this->post('/reports', [
            'date' => $dateAsString,
            'resident_id' => $resident->id,
        ]);
        
        // assert
        // verify json has expected data
        $response->assertStatus(201);
        //dd($response->decodeResponseJson());
        $response->assertJson([
          "group" => [
            0 => [
              "id" => 1,
              "activity_id" => "1",
              "date" => $date,
              "pivot" => [
                "resident_id" => "1",
                "scheduled_activity_id" => "1",
              ],
              "activity" => [
                "id" => 1,
                "name" => "Move and Groove",
                "description" => null,
                "supplies" => null,
                "instructions" => null,
                "domain_id" => "1",
                "domain" => [
                  "id" => 1,
                  "name" => "Test Domain Name",
                ]
              ]
            ]
          ],
          "individual" => []
        ]);
    }

    /** @test */
    function authenticated_user_can_retrieve_individual_attendance_records_for_resident()
    {
        // arrange
        $user = factory('App\User')->create();
        $this->be($user);
        
        // resident to be tested
        $resident = Resident::create([
            'first_name' => 'Gene',
            'last_name' => 'Porter',
        ]);
        $domain = Domain::create([
            'name' => 'Test Domain Name'
        ]);
        // activity
        $activity = Activity::create([
            'name' => 'Move and Groove',
            'domain_id' => $domain->id
        ]);
        // schedule that activity
        // indicate the date to be passed as well
        $date = Carbon::parse('today');
        $dateAsString = $date->format('Y-m-d'); // NOTE that you're currently storing date with day in table; that's fine; this makes for the apples to apples comparison as to why code was working for interface but not for test
        $individualScheduledActivity = IndividualAttendanceRecord::create([
            'resident_id' => $resident->id,
            'activity_id' => $activity->id,
            'date' => $dateAsString,
        ]);

        // check that record is in table
        $this->assertDatabaseHas('individual_attendance_records', [
            'resident_id' => $resident->id,
            'activity_id' => $activity->id,
            'date' => $dateAsString,
        ]);

        // act
        // hit API endpoint to retrieve data and return it
        // will be posting the resident id and date/month
        $response = $this->post('/reports', [
            'date' => $dateAsString, 
            'resident_id' => $resident->id,
        ]);
        
        // assert
        // verify json has expected data
        $response->assertStatus(201);
        //dd($response->decodeResponseJson());
        $response->assertJson([
            "group" => [],
            "individual" => [
                0 => [
                    "id" => 1,
                    "resident_id" => "1",
                    "activity_id" => "1",
                    "date" => $dateAsString,
                    "activity" => [
                        "id" => 1,
                        "name" => "Move and Groove",
                        "description" => null,
                        "supplies" => null,
                        "instructions" => null,
                        "domain_id" => "1",
                        "domain" => [
                            "id" => 1,
                            "name" => "Test Domain Name",
                        ]
                    ]
                ]
            ]
        ]);
    }

    /** @test */
    function authenticated_user_can_view_comments_for_resident_for_monthly_report()
    {
        // arrange
        // authenticated user
        $user = factory('App\User')->create();
        $this->be($user);
        // need a resident
        $resident = Resident::create([
            'first_name' => 'Gene',
            'last_name' => 'Porter',
        ]);
        // need a month
        $date = Carbon::parse('today');
        $dateAsString = $date->format('Y-m');
        // $date = '2018-01';
        // need a comment associated with resident and month
        $comment = Comment::create([
            'resident_id' => $resident->id,
            'date' => $dateAsString,
            'comment' => 'This is a comment for the month.',
        ]);
        // check that record is in table
        $this->assertDatabaseHas('comments', [
            'resident_id' => $resident->id,
            'date' => $dateAsString,
            'comment' => 'This is a comment for the month.',
        ]);
        // act
        // hit the endpoint
        $response = $this->post('/reports', [
            'date' => $dateAsString, 
            'resident_id' => $resident->id,
        ]);

        // assert
        // confirm can see comment
        $response->assertJson([
            "group" => [],
            "individual" => [],
            "comment" => [
                "This is a comment for the month."
                // [
                    // "id" => 1,
                    // "resident_id" => "1",
                    // "date" => $dateAsString,
                    // "comment" => "This is a comment for the month.",
                // ]
            ]
        ]);
    }

    /** @test */
    function authenticated_user_can_create_a_comment()
    {
        // arrange
        // authenticated user
        $user = factory('App\User')->create();
        $this->be($user);
        // need a resident
        $resident = Resident::create([
            'first_name' => 'Gene',
            'last_name' => 'Porter',
        ]);
        // need a month
        $date = Carbon::parse('today');
        $dateAsString = $date->format('Y-m');
        // need a comment
        $newComment = 'This is a comment for the month.';
        
        // act
        // hit the endpoint
        $response = $this->post('/reports/comment', [
            'date' => $dateAsString, 
            'resident_id' => $resident->id,
            'comment' => $newComment,
        ]);
        
        // assert
        // check that record is in table
        $this->assertDatabaseHas('comments', [
            'resident_id' => $resident->id,
            'date' => $dateAsString,
            'comment' => $newComment,
        ]);
    }

    /** @test */
    function authenticated_user_can_update_a_comment()
    {
        // arrange
        // authenticated user
        $user = factory('App\User')->create();
        $this->be($user);
        // need a resident
        $resident = Resident::create([
            'first_name' => 'Gene',
            'last_name' => 'Porter',
        ]);
        // need a month
        $date = Carbon::parse('today');
        $dateAsString = $date->format('Y-m');
        // need a comment
        $newComment = 'This is a comment for the month.';
        // hit the endpoint to save new comment
        $response = $this->post('/reports/comment', [
            'date' => $dateAsString, 
            'resident_id' => $resident->id,
            'comment' => $newComment,
        ]);
        // check that record is in table
        $this->assertDatabaseHas('comments', [
            'resident_id' => $resident->id,
            'date' => $dateAsString,
            'comment' => $newComment,
        ]);
        
        // act
        // now update the comment
        $updatedComment = 'This is an updated comment.';
        // hit the endpoint to save new comment
        $response = $this->post('/reports/comment', [
            'date' => $dateAsString, 
            'resident_id' => $resident->id,
            'comment' => $updatedComment,
        ]);
        
        // assert
        // check that record is in table
        $this->assertDatabaseHas('comments', [
            'resident_id' => $resident->id,
            'date' => $dateAsString,
            'comment' => $updatedComment,
        ]);
    }

    /** @test */
    function authenticated_user_can_delete_a_comment()
    {
        // arrange
        // authenticated user
        $user = factory('App\User')->create();
        $this->be($user);
        // need a resident
        $resident = Resident::create([
            'first_name' => 'Gene',
            'last_name' => 'Porter',
        ]);
        // need a month
        $date = Carbon::parse('today');
        $dateAsString = $date->format('Y-m');
        // need a comment
        $newComment = 'This is a comment for the month.';
        // hit the endpoint to save new comment
        $response = $this->post('/reports/comment', [
            'date' => $dateAsString, 
            'resident_id' => $resident->id,
            'comment' => $newComment,
        ]);
        // check that record is in table
        $this->assertDatabaseHas('comments', [
            'resident_id' => $resident->id,
            'date' => $dateAsString,
            'comment' => $newComment,
        ]);
        
        // act
        // now delete the comment by passing in empty string
        $updatedComment = '';
        // hit the endpoint to save new comment
        $response = $this->post('/reports/comment', [
            'date' => $dateAsString, 
            'resident_id' => $resident->id,
            'comment' => $updatedComment,
        ]);
        
        // assert
        // check that record is in table
        $this->assertDatabaseMissing('comments', [
            'resident_id' => $resident->id,
            'date' => $dateAsString,
        ]);
    }
}
