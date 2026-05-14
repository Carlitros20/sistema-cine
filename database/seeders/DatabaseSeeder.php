<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seeder principal — ejecuta CineSeeder.
     *
     * Se invoca con: php artisan db:seed
     * o con:        php artisan migrate --seed
     */
    public function run(): void
    {
        $this->call([
            CineSeeder::class,
        ]);
    }
}
