<?php

namespace Tests\Feature;

use App\Resident;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResidentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_view_the_list_of_residents()
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

        // act
        // view the list of residents
        $response = $this->withSession(['location_id' => 1])->get('/residents');

        // assert
        // verify that certain residents are visible
        // check/verify endpoint
        $response->assertStatus(200);
        $response->assertSee('Gene');
        $response->assertSee('Porter');
    }
    
    /** @test */
    function an_authenticated_user_can_create_new_resident()
    {
        // arrange
        $user = factory('App\User')->create();
        $this->be($user);

        // raw() creates values as an array
        $newResident = [
            'first_name' => 'Gene',
            'last_name' => 'Porter',
            'location_id' => 1,
        ];

        // act
        $this->withSession(['location_id' => 1])->post('/residents', $newResident);

        // assert
        $this->assertDatabaseHas('residents', [
            'first_name' => 'Gene',
            'last_name' => 'Porter',
        ]);
    }

    /** @test */
    function an_authenticated_user_can_edit_an_existing_resident()
    {
        // arrange
        $user = factory('App\User')->create();
        $this->be($user);

        // create an resident
        $resident = Resident::create([
            'first_name' => 'Gene',
            'last_name' => 'Porter',
        ]);

        // act
        $updatedFirstName = 'Jim';
        $this->patch("/residents/{$resident->id}", [
            'first_name' => $updatedFirstName,
            'last_name' => 'Porter',
        ]);

        // assert
        $this->assertDatabaseHas('residents', [
            'id' => $resident->id,
            'first_name' => $updatedFirstName,
            'last_name' => 'Porter',
        ]);   

    }

    /** @test */
    function an_authenticated_user_can_soft_delete_resident()
    {
        // arrange
        $user = factory('App\User')->create();
        $this->be($user);
        // raw() creates values as an array
        $newResident = [
            'id' => 1,
            'first_name' => 'Gene',
            'last_name' => 'Porter',
            'location_id' => 1,
        ];
        // post to endpoint
        $this->withSession(['location_id' => 1])->post('/residents', $newResident);
        // confirm
        $this->assertDatabaseHas('residents', [
            'id' => 1,
            'first_name' => 'Gene',
            'last_name' => 'Porter',
        ]);
        
        // act
        // soft delete record
        $response = $this->json('DELETE', "/residents/{$newResident['id']}");
        
        // assert
        // $this->assertDatabaseHas('residents', [
        //     'id' => 1,
        //     'first_name' => 'Gene',
        //     'last_name' => 'Porter',
        // ]);
        $this->assertSoftDeleted('residents', ['id' => $newResident['id']]);
    }
}
