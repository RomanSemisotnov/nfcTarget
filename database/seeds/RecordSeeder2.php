<?php

use App\Client;
use App\Uid;
use Illuminate\Database\Seeder;

class RecordSeeder2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
    }
}
