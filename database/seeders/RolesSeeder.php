<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['id' => Str::uuid(), 'name' => 'owner', 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'name' => 'user', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
