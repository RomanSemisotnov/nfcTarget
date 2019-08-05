<?php

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
            'name' => 'Sasha',
            'email' => 'sanyaadmin228@admin.ru',
            'password' => Hash::make('BhGDHlIN456234237Jvthnmkl')
        ]);

        User::create([
            'name' => 'Roman',
            'email' => 'semisotnovroman2018@yandex.ru',
            'password' => Hash::make('BfarghGDHlIfdsgN4567Jvt432hnmkl')
        ]);
    }
}
