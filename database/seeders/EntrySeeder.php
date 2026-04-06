<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntrySeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('users')->pluck('id')->toArray();

        // Coordonnées approximatives de Dakar
        $lat = 14.6937;
        $lng = -17.4441;

        foreach ($users as $userId) {
            // Entrée du matin
            DB::table('entries')->insert([
                'user_id'    => $userId,
                'type'       => 'entrée',
                'motif'      => null,
                'latitude'   => $lat + (rand(-100, 100) / 10000),
                'longitude'  => $lng + (rand(-100, 100) / 10000),
                'created_at' => now()->setTime(8, rand(0, 30)),
                'updated_at' => now()->setTime(8, rand(0, 30)),
            ]);

            // Sortie provisoire (pause déjeuner)
            DB::table('entries')->insert([
                'user_id'     => $userId,
                'type'        => 'sortie_provisoire',
                'motif'       => 'Pause déjeuner',
                'latitude'    => $lat + (rand(-100, 100) / 10000),
                'longitude'   => $lng + (rand(-100, 100) / 10000),
                'heure_sortie' => now()->setTime(13, 0),
                'heure_retour' => now()->setTime(14, 0),
                'created_at'  => now()->setTime(13, 0),
                'updated_at'  => now()->setTime(14, 0),
            ]);

            // Descente du soir
            DB::table('entries')->insert([
                'user_id'    => $userId,
                'type'       => 'descente',
                'motif'      => null,
                'latitude'   => $lat + (rand(-100, 100) / 10000),
                'longitude'  => $lng + (rand(-100, 100) / 10000),
                'heure_sortie' => now()->setTime(17, rand(0, 30)),
                'created_at' => now()->setTime(17, rand(0, 30)),
                'updated_at' => now()->setTime(17, rand(0, 30)),
            ]);
        }
    }
}
