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
        $this->call(UserTableSeeder::class);
        $this->call(\RecordSeeder::class);
        $this->call(\CorrectRequestSeeder::class);

        $this->call(\RecordSeeder2::class);
        $this->call(\CorrectRequestSeeder2::class);
    }
}
