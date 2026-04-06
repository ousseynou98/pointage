<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QrCodeSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            'Entrée Principale',
            'Sortie Principale',
            'Entrée Secondaire',
            'Sortie Secondaire',
            'Cafétéria',
            'Salle de Réunion',
        ];

        foreach ($locations as $location) {
            DB::table('qr_codes')->insert([
                'code'          => Str::random(10),
                'location_name' => $location,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }
}
