<?php

namespace Tests\Feature;

use App\Domain;
use App\Activity;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewActivitiesListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_view_the_list_of_activities()
    {
        // arrange
        $user = factory('App\User')->create();
        $this->be($user);

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
        // view the list of activities
        $response = $this->get('/activities');

        // assert
        // verify that certain activities are visible
        // check/verify endpoint
        $response->assertStatus(200);
        $response->assertSee('Move and Groove');
        $response->assertSee('Card Sharks');

    }

    /** @test */
    function user_can_view_the_list_of_activities_that_includes_associated_domain()
    {
        // this is to test the addition of the domain column to the view (12-25-2017)
        
        // arrange
        $user = factory('App\User')->create();
        $this->be($user);

        // create a list of activities
        $domain = Domain::create([
            'name' => 'New Domain Name',
        ]);

        $activity = Activity::create([
            'name' => 'Move and Groove',
            'description' => 'Lorem ipsum dolor sit amet',
            'supplies' => 'adipisicing elit',
            'instructions' => 'Iusto dicta aut dolor molestias veritatis asperiores, repellendus numquam magni maxime voluptas dolores odit eligendi.',
            'domain_id' => $domain->id,
        ]);

        // act
        // view the list of activities
        $response = $this->get('/activities');

        // assert
        // verify that certain activities are visible
        // check/verify endpoint
        $response->assertStatus(200);
        // activity name
        $response->assertSee('Move and Groove');
        // associted domain name
        $response->assertSee('New Domain Name');

    }

    /** @test */
    function user_can_create_new_activity()
    {
        // arrange
        $user = factory('App\User')->create();
        $this->be($user);

        // raw() creates values as an array
        $newActivity = [
            'name' => 'New Activity',
            'description' => 'New description here',
            'supplies' => 'Supplies listed here',
            'instructions' => 'And finally, instructions are listed here',
        ];

        // act
        $this->post('/activities', $newActivity);

        // assert
        $this->assertDatabaseHas('activities', [
            'name' => 'New Activity',
            'description' => 'New description here',
            'supplies' => 'Supplies listed here',
            'instructions' => 'And finally, instructions are listed here',
        ]);
    }

    /** @test */
    function user_can_edit_an_existing_activity()
    {
        // arrange
        $user = factory('App\User')->create();
        $this->be($user);

        // create an activity
        $activity = Activity::create([
            'name' => 'Move and Groove',
            'description' => 'New description here',
            'supplies' => 'Supplies listed here',
            'instructions' => 'And finally, instructions are listed here',
        ]);

        // act
        $updatedName = 'Moveth and Grooveth';
        $this->patch("/activities/{$activity->id}", [
            'name' => $updatedName,
            'description' => 'New description here',
            'supplies' => 'Supplies listed here',
            'instructions' => 'And finally, instructions are listed here',
        ]);

        // assert
        $this->assertDatabaseHas('activities', [
            'id' => $activity->id,
            'name' => $updatedName,
            'description' => 'New description here',
            'supplies' => 'Supplies listed here',
            'instructions' => 'And finally, instructions are listed here',
        ]);   

    }

    /** @test */
    function user_can_delete_an_existing_activity()
    {
        // arrange
        $user = factory('App\User')->create();
        $this->be($user);
        // raw() creates values as an array
        $newActivity = [
            'id' => 1,
            'name' => 'New Activity',
            'description' => 'New description here',
            'supplies' => 'Supplies listed here',
            'instructions' => 'And finally, instructions are listed here',
        ];
        // post to endpoint
        $this->post('/activities', $newActivity);
        // confirm
        $this->assertDatabaseHas('activities', [
            'id' => 1,
            'name' => 'New Activity',
            'description' => 'New description here',
            'supplies' => 'Supplies listed here',
            'instructions' => 'And finally, instructions are listed here',
        ]);

        // act
        // delete the record
        $response = $this->json('DELETE', "/activities/{$newActivity['id']}");

        // assert
        $this->assertDatabaseMissing('activities', ['id' => $newActivity['id']]);
    }
}
