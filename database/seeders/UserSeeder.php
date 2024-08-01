<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ownerRole = DB::table('roles')->where('name', 'owner')->first();
        DB::table('users')->insert([
            'id' => Str::uuid(),
            'name' => 'Owner',
            'email' => 'owner@gmail.com',
            'password' => Hash::make('12345678'),
            'role_id' => $ownerRole->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
