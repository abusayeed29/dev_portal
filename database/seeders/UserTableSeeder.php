<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'role_id' => '1',
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('11111111'),
        ]);
        DB::table('users')->insert([
            'role_id' => '2',
            'name' => 'Sabit Humam',
            'email' => 'pnc@pnc.com',
            'password' => bcrypt('11111111'),
        ]);
        DB::table('users')->insert([
            'role_id' => '4',
            'name' => 'user',
            'email' => 'user@user.com',
            'password' => bcrypt('11111111'),
        ]);
    }
}
