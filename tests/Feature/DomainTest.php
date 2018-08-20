<?php

namespace Tests\Feature;

use App\Domain;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DomainTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_view_the_list_of_domains()
    {
        // arrange
        $user = factory('App\User')->create();
        $this->be($user);

        // create a domain
        $domain = Domain::create([
            'name' => 'Reminisce',
        ]);

        // act
        // view the domain
        $response = $this->get('/domains');
        //dd($response->data);

        // assert
        $response->assertStatus(200);
        $response->assertSee('Reminisce');
    }

    /** @test */
    function an_authenticated_user_can_create_new_domains()
    {
        // arrange
        $user = factory('App\User')->create();
        $this->be($user);

        // raw() creates values as an array
        $newDomain = [
            'name' => 'New Domain Name'
        ];

        // act
        $this->post('/domains', $newDomain);

        // assert
        $this->assertDatabaseHas('domains', [
            'name' => 'New Domain Name',
        ]);
    }

    /** @test */
    function an_authenticated_user_can_edit_an_existing_domain()
    {
        // arrange
        $user = factory('App\User')->create();
        $this->be($user);

        // create a domain
        $domain = Domain::create([
            'name' => 'New Domain Name',
        ]);

        // act
        $updatedDomainName = 'Updated Domain Name';
        $this->patch("/domains/{$domain->id}", [
            'name' => $updatedDomainName,
        ]);

        // assert
        $this->assertDatabaseHas('domains', [
            'id' => $domain->id,
            'name' => $updatedDomainName,
        ]);
    }

    /** @test */
    function an_authenticated_user_can_delete_an_existing_domain()
    {
        // arrange
        $user = factory('App\User')->create();
        $this->be($user);
        // raw() creates values as an array
        $newDomain = [
            'id' => 1,
            'name' => 'New Domain Name',
        ];
        // post to endpoint
        $this->post('/domains', $newDomain);
        // confirm
        $this->assertDatabaseHas('domains', [
            'id' => 1,
            'name' => 'New Domain Name',
        ]);

        // act
        // delete the record
        $response = $this->json('DELETE', "/domains/{$newDomain['id']}");

        // assert
        $this->assertDatabaseMissing('domains', ['id' => $newDomain['id']]);
        
    }
}
