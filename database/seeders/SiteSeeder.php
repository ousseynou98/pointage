<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sites')->insert([
            ['name' => 'Siège ANAM Dakar',       'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Agence Thiès',            'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Agence Saint-Louis',      'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Agence Kaolack',          'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Agence Ziguinchor',       'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
