<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(App\Comment::class)->create();

        DB::table('comments')->insert([
            [
                'resident_id' => 1,
                'date' => Carbon::now('America/Denver')->format('Y-m'),
                'comment' => 'Lorem ipsum commentario',
            ]
        ]);
    }
}
