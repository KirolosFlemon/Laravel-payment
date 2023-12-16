<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\User\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $User = User::factory()->createOne([
            'name' => 'admin',
            'email' => 'admin@demo.com',
            'phone' => '012456987455',
            'password' =>  bcrypt('12345678'),
        ]);

    }
}
