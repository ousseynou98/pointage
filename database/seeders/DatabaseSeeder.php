<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SiteSeeder::class,    // 1. Sites d'abord (requis par users)
            UserSeeder::class,    // 2. Utilisateurs (requis par entries)
            QrCodeSeeder::class,  // 3. QR Codes
            EntrySeeder::class,   // 4. Pointages (dépend des users)
        ]);
    }
}
