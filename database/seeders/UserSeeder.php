<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $dakar     = DB::table('sites')->where('name', 'Siège ANAM Dakar')->value('id');
        $thies     = DB::table('sites')->where('name', 'Agence Thiès')->value('id');
        $stLouis   = DB::table('sites')->where('name', 'Agence Saint-Louis')->value('id');
        $kaolack   = DB::table('sites')->where('name', 'Agence Kaolack')->value('id');
        $ziguinchor = DB::table('sites')->where('name', 'Agence Ziguinchor')->value('id');

        DB::table('users')->insert([
            [
                'name'       => 'Administrateur',
                'email'      => 'admin@anam.sn',
                'password'   => Hash::make('password'),
                'site_id'    => $dakar,
                'tel'        => '770000000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Abdoulaye Diallo',
                'email'      => 'abdoulaye@anam.sn',
                'password'   => Hash::make('password'),
                'site_id'    => $dakar,
                'tel'        => '771234567',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Fatou Sow',
                'email'      => 'fatou@anam.sn',
                'password'   => Hash::make('password'),
                'site_id'    => $thies,
                'tel'        => '772345678',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Moussa Ndiaye',
                'email'      => 'moussa@anam.sn',
                'password'   => Hash::make('password'),
                'site_id'    => $kaolack,
                'tel'        => '773456789',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Aminata Diop',
                'email'      => 'aminata@anam.sn',
                'password'   => Hash::make('password'),
                'site_id'    => $stLouis,
                'tel'        => '774567890',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Ibrahima Ba',
                'email'      => 'ibrahima@anam.sn',
                'password'   => Hash::make('password'),
                'site_id'    => $ziguinchor,
                'tel'        => '775678901',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
