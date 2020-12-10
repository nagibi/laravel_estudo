<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(TabelaUsuariosSeeder::class);
        $this->call(TabelaCategoriasSeeder::class);
        $this->call(TabelaRolesSeeder::class);
        $this->call(TabelaPermissoesSeeder::class);
    }
}
