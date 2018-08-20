<?php

namespace Tests\Feature;

use App\Location;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LocationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function an_authenticated_user_can_view_the_list_of_locations()
    {
        // arrange
        // $user = factory('App\User')->create();
        $user = factory('App\User')->create([
            'last_selected_location_id' => 1]);
        $this->be($user);

        // create locations
        $location = Location::create([
            'name' => 'Location 1'
        ]);
        $location2 = Location::create([
            'name' => 'Location 2'
        ]); 

        // act
        // view the locations
        $response = $this->get('/home');

        // assert
        $response->assertStatus(200);
        $response->assertSee('Location 1');
        $response->assertSee('Location 2');
    }

    /** @test */
    function an_authenticated_user_can_set_the_location_session_variable()
    {
        // arrange
        $user = factory('App\User')->create();
        $this->be($user);
        // create the location
        $location = Location::create([
            'name' => 'Location 1'
        ]);

        // selection from front end to set session variable
        // using route/model binding in routes file
        $response = $this->post("/home/{$location->id}");

        // assert
        // check that session variable has been set/updated
        $response->assertSessionHas(['location_id' => $location->id]);
        $response->assertSessionHas(['location_name' => $location->name]);
    }
}
