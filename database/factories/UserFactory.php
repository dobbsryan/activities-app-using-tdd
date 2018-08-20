<?php

use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    static $password;

    return [
        'name' => 'Ryan',
        // 'name' => $faker->name,
        'email' => 'ryan@example.com',
        // 'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        // 'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\ScheduledActivity::class, function (Faker $faker) {
    return [
        'activity_id' => 1,
        'date' => Carbon::parse('today 11:00am'),
    ];
});

$factory->define(App\Resident::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName(),
        'last_name' => $faker->lastName(),
        'location_id' => $faker->numberBetween($min = 1, $max = 2)
    ];
});

$factory->define(App\Activity::class, function (Faker $faker) {
    return [
        'name' => implode($faker->words()),
        'description' => $faker->sentence(),
        'supplies' => implode($faker->words()),
        'instructions' => implode($faker->sentences()),
        // see ActivitiesTableSeeder when making changes to domain_id
        //'domain_id' => $faker->numberBetween($min = 1, $max = 15),
    ];
});

$factory->define(App\Domain::class, function (Faker $faker) {
    return [
        'name' => $faker->word(),
    ];
});

$factory->define(App\IndividualAttendanceRecord::class, function (Faker $faker) {
    return [
        'resident_id' => 1,
        'activity_id' => 1,
        'date' => Carbon::now('America/Denver')->format('Y-m-d'),
    ];
});