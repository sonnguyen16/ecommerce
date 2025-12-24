<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'John Doe',
            'email' => 'user@gmail.com',
            'password' => bcrypt('123456'),
            'phone' => '0123456789',
            'role' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
