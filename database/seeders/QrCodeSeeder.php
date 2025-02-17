<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QrCode;
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
            'Salle de Réunion'
        ];

        foreach ($locations as $location) {
            QrCode::create([
                'code' => Str::random(10),
                'location_name' => $location
            ]);
        }
    }
}
