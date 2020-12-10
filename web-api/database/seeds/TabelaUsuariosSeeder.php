<?php

use Illuminate\Database\Seeder;
use App\Usuario;

class TabelaUsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Usuario::create([ 
            'email' => 'admin@tks-br.com',
            'password' => Hash::make('123456'),
            'nome' => 'Admin TKS',
            'confirmacaoEmail' => 1,
            'usuarioCriacaoId'=>1,
            'status'=>1,
            'usuarioAtualizacaoId'=>1,
            'dataCriacao'=>date('Y-m-d H:i:s'),
            'dataAtualizacao'=>date('Y-m-d H:i:s'),
        ]);

        Usuario::create([ 
            'email' => 'edit@tks-br.com',
            'password' => Hash::make('123456'),
            'nome' => 'Edit TKS',
            'status'=>1,
            'confirmacaoEmail' => 1,
            'usuarioCriacaoId'=>1,
            'usuarioAtualizacaoId'=>1,
            'dataCriacao'=>date('Y-m-d H:i:s'),
            'dataAtualizacao'=>date('Y-m-d H:i:s'),
        ]);
    }
}
