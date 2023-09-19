<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
        	'name' => 'Admin',
            'slug' => 'admin',
        ]);
        DB::table('roles')->insert([
        	'name' => 'Sub',
            'slug' => 'sub-admin',
        ]);
        DB::table('roles')->insert([
        	'name' => 'Head',
            'slug' => 'head',
        ]);
        DB::table('roles')->insert([
        	'name' => 'User',
            'slug' => 'user',
        ]);
    }
}
