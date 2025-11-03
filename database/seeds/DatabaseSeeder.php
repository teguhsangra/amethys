<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(InitialSeeder::class);
        $this->call(ParameterSettingsTableSeeder::class);
        $this->call(ProjectSeeder::class);
    }
}
