<?php

use Illuminate\Database\Seeder;
use App\Categoria;

class TabelaCategoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Categoria::create([ 
            'nome' => 'Cate 1',
            'usuarioCriacaoId'=>1,
            'status'=>1,
            'usuarioAtualizacaoId'=>1,
            'dataCriacao'=>date('Y-m-d H:i:s'),
            'dataAtualizacao'=>date('Y-m-d H:i:s'),
        ]);

        Categoria::create([ 
            'nome' => 'Cate 2',
            'categoriaId' => 1,
            'usuarioCriacaoId'=>1,
            'status'=>1,
            'usuarioAtualizacaoId'=>1,
            'dataCriacao'=>date('Y-m-d H:i:s'),
            'dataAtualizacao'=>date('Y-m-d H:i:s'),
        ]);

        Categoria::create([ 
            'nome' => 'Cate 3',
            'categoriaId' => 2,
            'usuarioCriacaoId'=>1,
            'status'=>1,
            'usuarioAtualizacaoId'=>1,
            'dataCriacao'=>date('Y-m-d H:i:s'),
            'dataAtualizacao'=>date('Y-m-d H:i:s'),
        ]);

        Categoria::create([ 
            'nome' => 'Cate 4',
            'categoriaId' => 2,
            'usuarioCriacaoId'=>1,
            'status'=>1,
            'usuarioAtualizacaoId'=>1,
            'dataCriacao'=>date('Y-m-d H:i:s'),
            'dataAtualizacao'=>date('Y-m-d H:i:s'),
        ]);

    }
}
