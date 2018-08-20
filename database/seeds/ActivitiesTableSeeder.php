<?php

use Illuminate\Database\Seeder;

class ActivitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('domains')->insert([
        //     [
        //         'name' => 'Reminisce',
        //     ],
        //     [
        //         'name' => 'Life Sharing',
        //     ],
        //     [
        //         'name' => 'Art Therapy',
        //     ],
        //     [
        //         'name' => 'Mind Challenge',
        //     ],
        //     [
        //         'name' => 'Massage Therapy',
        //     ],
        //     [
        //         'name' => 'Community Entertainment',
        //     ],
        //     [
        //         'name' => 'Music Therapy',
        //     ],
        //     [
        //         'name' => 'Physical Fitness',
        //     ],
        //     [
        //         'name' => 'Outdoor Experience',
        //     ],
        //     [
        //         'name' => 'Outing',
        //     ],
        //     [
        //         'name' => 'Devotions/Bible Study',
        //     ],
        //     [
        //         'name' => 'Pet Therapy',
        //     ],
        //     [
        //         'name' => 'Sorting',
        //     ],
        //     [
        //         'name' => "Men/Women's Group",
        //     ],
        //     [
        //         'name' => '1:1 Companionship',
        //     ],
        // ]);
        
        // // works to create a number of faker domains
        
        // [ ] create these as seed data - see wathan vid: Building Sponsorship #10 @ 20:30
            // Work It Out  -- Physical Fitness
            // Daily News -- Current Events
            // Music on the Patio - Music and Movement
            // You’ve Got Mail - Life Sharing
            // Gourmet Cafe - Hobby Sharing
            // Indoor Bowling - Physical Fitness
            // Rose Garden Reads - Learning & Exploration
            // Pet Therapy - Complementary Therapies

        factory(App\Domain::class, 4)->create()->each(function(App\Domain $domain) {
            $activities = factory(App\Activity::class, 2)->create(['domain_id' => $domain->id]);
        });

        // original: no relationship with Domains
        //factory(App\Activity::class, 1)->create();
    }
}
