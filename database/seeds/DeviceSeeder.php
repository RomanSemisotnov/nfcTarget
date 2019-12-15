<?php

use Illuminate\Database\Seeder;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $devices=array_merge(config('devices.android'),config('devices.ios'),config('devices.unknown'));

        foreach($devices as $device){
            \App\Device::create([
                'name' => $device
            ]);
        }
    }
}
