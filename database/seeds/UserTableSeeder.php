<?php

use App\Client;
use App\Uid;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@admin.ru',
            'password' => Hash::make('password')
        ]);

        User::create([
            'name' => 'Sasha',
            'email' => 'sanyaadmin228@admin.ru',
            'password' => Hash::make('BhGDHlIN456234237Jvthnmkl')
        ]);

        User::create([
            'name' => 'Roman',
            'email' => 'semisotnovroman2018@yandex.ru',
            'password' => Hash::make('BfarghGDHlIfdsgN4567Jvt432hnmkl')
        ]);

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
        $client = Client::create(['name' => 'mozaiqa1', 'subdomain' => 'mozaiqa1']);

        $firstParam = \App\QueryParam::create([
            'name' => 'city',
            'client_id' => $client->id,
            'index_number' => 1
        ]);
        $secondParam = \App\QueryParam::create([
            'name' => 'type',
            'client_id' => $client->id,
            'index_number' => 2
        ]);
        $thirdParam = \App\QueryParam::create([
            'name' => 'skidka',
            'client_id' => $client->id,
            'index_number' => 3
        ]);

        \App\ParamVariable::create(['name' => 'moscow', 'QueryParam_id' => $firstParam->id]);
        \App\ParamVariable::create(['name' => 'piter', 'QueryParam_id' => $firstParam->id]);

        \App\ParamVariable::create(['name' => 'first', 'QueryParam_id' => $secondParam->id]);
        \App\ParamVariable::create(['name' => 'second', 'QueryParam_id' => $secondParam->id]);

        \App\ParamVariable::create(['name' => '5', 'QueryParam_id' => $thirdParam->id]);
        \App\ParamVariable::create(['name' => '10', 'QueryParam_id' => $thirdParam->id]);
        \App\ParamVariable::create(['name' => '15', 'QueryParam_id' => $thirdParam->id]);

        $pattern1 = \App\PatternLink::create([
            'value' => 'http://localhost/moscow/first/5/',
            'client_id' => $client->id,
            'redirectTo' => 'https://youtube.com'
        ]);
        $pattern2 = \App\PatternLink::create([
            'value' => 'http://localhost/piter/second/10/',
            'client_id' => $client->id,
            'redirectTo' => 'https://youtube.com'
        ]);

        $record1 = \App\Record::create([
            'patternlink_id' => $pattern1->id,
            'needLinks' => 15,
            'isActive' => 'no',
            'client_id' => $client->id,
            'priceOneTag' => 50
        ]);
        $record2 = \App\Record::create([
            'patternlink_id' => $pattern2->id,
            'needLinks' => 15,
            'isActive' => 'no',
            'client_id' => $client->id,
            'priceOneTag' => 50
        ]);

        for ($i = 0; $i < 10; $i++) {
            Uid::create([
                'value' => str_random(),
                'record_id' => $record1->id,
                'patternlink_id' => $pattern1->id
            ]);
        }
        for ($i = 0; $i < 5; $i++) {
            Uid::create([
                'value' => str_random(),
                'record_id' => $record2->id,
                'patternlink_id' => $pattern2->id
            ]);
        }
        $requestCount = random_int(100, 200);
        $uids = Uid::all();
        $devices = \App\Device::all();
        for ($i = 0; $i < $requestCount; $i++) {

            \App\CorrectRequest::create([
                'client_id' => 1,
                'uid_id' => random_int(1, $uids->count()),
                'device_id' => random_int(1, $devices->count()),
                'ip' => str_random(16)
            ]);
        }
        $client = Client::create(['name' => 'mozaiqa2', 'subdomain' => 'mozaiqa2']);

        $firstParam = \App\QueryParam::create([
            'name' => 'city',
            'client_id' => $client->id,
            'index_number' => 1
        ]);
        $secondParam = \App\QueryParam::create([
            'name' => 'type',
            'client_id' => $client->id,
            'index_number' => 2
        ]);
        $thirdParam = \App\QueryParam::create([
            'name' => 'skidka',
            'client_id' => $client->id,
            'index_number' => 3
        ]);

        \App\ParamVariable::create(['name' => 'moscow', 'QueryParam_id' => $firstParam->id]);
        \App\ParamVariable::create(['name' => 'piter', 'QueryParam_id' => $firstParam->id]);

        \App\ParamVariable::create(['name' => 'first', 'QueryParam_id' => $secondParam->id]);
        \App\ParamVariable::create(['name' => 'second', 'QueryParam_id' => $secondParam->id]);

        \App\ParamVariable::create(['name' => '5', 'QueryParam_id' => $thirdParam->id]);
        \App\ParamVariable::create(['name' => '10', 'QueryParam_id' => $thirdParam->id]);
        \App\ParamVariable::create(['name' => '15', 'QueryParam_id' => $thirdParam->id]);

        $pattern1 = \App\PatternLink::create([
            'value' => 'http://localhost/moscow/first/5/',
            'client_id' => $client->id,
            'redirectTo' => 'https://youtube.com'
        ]);
        $pattern2 = \App\PatternLink::create([
            'value' => 'http://localhost/piter/second/10/',
            'client_id' => $client->id,
            'redirectTo' => 'https://youtube.com'
        ]);

        $record1 = \App\Record::create([
            'patternlink_id' => $pattern1->id,
            'needLinks' => 15,
            'isActive' => 'no',
            'client_id' => $client->id,
            'priceOneTag' => 50
        ]);
        $record2 = \App\Record::create([
            'patternlink_id' => $pattern2->id,
            'needLinks' => 15,
            'isActive' => 'no',
            'client_id' => $client->id,
            'priceOneTag' => 50
        ]);

        for ($i = 0; $i < 10; $i++) {
            Uid::create([
                'value' => str_random(),
                'record_id' => $record1->id,
                'patternlink_id' => $pattern1->id
            ]);
        }
        for ($i = 0; $i < 5; $i++) {
            Uid::create([
                'value' => str_random(),
                'record_id' => $record2->id,
                'patternlink_id' => $pattern2->id
            ]);
        }
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
