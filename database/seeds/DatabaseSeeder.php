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
     //   $this->call(UserTableSeeder::class);
      //  $this->call(DeviceSeeder::class);
        $this->call(RecordSeeder::class);
        $this->call(CorrectRequestSeeder::class);
    }
}
