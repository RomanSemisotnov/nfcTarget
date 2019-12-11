<?php

use App\Uid;
use Illuminate\Database\Seeder;

class CorrectRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $requestCount = random_int(100, 200);
        $uids = Uid::all();
        $devices = \App\Device::all();
        for ($i = 0; $i < $requestCount; $i++) {

            \App\CorrectRequest::create([
                'client_id' => 2,
                'uid_id' => random_int(1, $uids->count()),
                'device_id' => random_int(1, $devices->count()),
                'ip' => str_random(16)
            ]);
        }
    }
}
