<?php

use App\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TabelaPermissoesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /********************
         * Usuários
        ********************/
        
        $usuarios = [
            [
                'name' => 'usuario-cadastrar',
                'display_name' => 'usuario-cadastrar',
                'description' => 'Adicionar usuário',
                'usuarioCriacaoId' => 1,
                'usuarioAtualizacaoId' => 1,
                'dataCriacao'=>date('Y-m-d H:i:s'),
                'dataAtualizacao'=>date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'usuario-atualizar',
                'display_name' => 'usuario-atualizar',
                'description' => 'Atualizar usuário',
                'usuarioCriacaoId' => 1,
                'usuarioAtualizacaoId' => 1,
                'dataCriacao'=>date('Y-m-d H:i:s'),
                'dataAtualizacao'=>date('Y-m-d H:i:s'),

            ],
            [
                'name' => 'usuario-status',
                'display_name' => 'usuario-status',
                'description' => 'Ativar/Inativar usuário',
                'usuarioCriacaoId' => 1,
                'usuarioAtualizacaoId' => 1,
                'dataCriacao'=>date('Y-m-d H:i:s'),
                'dataAtualizacao'=>date('Y-m-d H:i:s'),

            ],
            [
                'name' => 'usuario-obter',
                'display_name' => 'usuario-obter',
                'description' => 'Visualizar usuário',
                'usuarioCriacaoId' => 1,
                'usuarioAtualizacaoId' => 1,
                'dataCriacao'=>date('Y-m-d H:i:s'),
                'dataAtualizacao'=>date('Y-m-d H:i:s'),

            ],
            [
                'name' => 'usuario-deletar',
                'display_name' => 'usuario-deletar',
                'description' => 'Deletar usuário',
                'usuarioCriacaoId' => 1,
                'usuarioAtualizacaoId' => 1,
                'dataCriacao'=>date('Y-m-d H:i:s'),
                'dataAtualizacao'=>date('Y-m-d H:i:s'),

            ],
            [
                'name' => 'usuario-pesquisar',
                'display_name' => 'usuario-pesquisar',
                'description' => 'Pesquisar usuário',
                'usuarioCriacaoId' => 1,
                'usuarioAtualizacaoId' => 1,
                'dataCriacao'=>date('Y-m-d H:i:s'),
                'dataAtualizacao'=>date('Y-m-d H:i:s'),

            ]
        ];

        $admin = [
            //usuario
            [
                'role_id' => 1,
                'permission_id' => 1,
            ],
            [
                'role_id' => 1,
                'permission_id' => 2,
            ],
            [
                'role_id' => 1,
                'permission_id' => 3,
            ],
            [
                'role_id' => 1,
                'permission_id' => 4,
            ],
            [
                'role_id' => 1,
                'permission_id' => 5,
            ],
            [
                'role_id' => 1,
                'permission_id' => 6,
            ]
        ];

        $edit = [];

        Permission::insert(array_merge($usuarios));
        // DB::table('permission_role')->insert(array_merge($admin,$edit));
        // DB::table('permission_role')->insert($admin);
    }
}
