<?php

use Illuminate\Database\Seeder;

/**
 * Created by PhpStorm.
 * User: russian_rave
 * Date: 12/9/2019
 * Time: 8:29 PM
 */
class DeviceSeeder extends Seeder
{

    public function run()
    {
        $devices = ['Iphone',
            'Samsung phone',
            'Sony phone',
            'Asus phone',
            'Another phone',
            'Xiomi phone',
            'Ipad',
            'Samsung tablet',
            'Sony tablet',
            'Asus tablet',
            'Xiomi tablet',
            'Another tablet',
            'unknown'];

        foreach ($devices as $device) {
            \App\Device::create(['name' => $device]);
        }
    }

}