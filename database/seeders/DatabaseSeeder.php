<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;

use App\Models\About;
use App\Models\Event;
use App\Models\Program;
use App\Models\Promotion;
use App\Models\Timetable;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create();

        About::factory(1)->create();
        Event::factory(5)->create();
        Program::factory(10)->create();
        Promotion::factory(10)->create();
        Timetable::factory(10)->create();
    }
}
