<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

class EnumController extends Controller
{

    public function simNao()
    {

        $result = [
            ['valor' => 0, 'descricao' => 'Sim'],
            ['valor' => 1, 'descricao' => 'Não'],
        ];

        return $this->response(200, "MSG000151", $result);
    }

    /**
     * @OA\Get(
     *     tags={"Enums"},
     *     path="/api/v1/enums/status-documento",
     *     summary="Lista os Status de Documento",
     *     description="Lista os tipos de Status de Documento",
     *     operationId="enumStatusDocumento",
     *     security={ {"bearer": {} }},
     *   @OA\Response(
     *     response=201,
     *     description="Sucesso",
     *        @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                property="result",
     *                type="array",
     *                example={{
     *                  "valor": 0,
     *                  "descricao": "Não"
     *                }, {
     *                  "valor": 1,
     *                  "descricao": "Sim"
     *                }},
     *                @OA\Items(
     *                      @OA\Property(
     *                         property="valor",
     *                         type="int",
     *                         example=""
     *                      ),
     *                      @OA\Property(
     *                         property="descricao",
     *                         type="string",
     *                         example=""
     *                      )
     *                ),
     *             ),
     *        ),
     *   )
     * )
     */
    public function statusDocumento()
    {

        $result = [
            ['valor' => 0, 'descricao' => 'Aguardando processamento'],
            ['valor' => 1, 'descricao' => 'Em processamento'],
            ['valor' => 2, 'descricao' => 'Processado'],
            ['valor' => 3, 'descricao' => 'Erro ao processar'],
        ];

        return $this->response(200, "MSG000151", $result);
    }

    public function tipoArquivo()
    {
        $result = [
            ['valor' => 0, 'descricao' => 'Sim'],
            ['valor' => 1, 'descricao' => 'Não'],
        ];
        return $this->response(200, "MSG000151", $result);
    }

    public function conciliacao()
    {
        $result = [
            ['valor' => 0, 'descricao' => 'Conciliado'],
            ['valor' => 1, 'descricao' => 'Conciliado Manualmente'],
            ['valor' => 2, 'descricao' => 'Não conciliado'],
        ];
        return $this->response(200, "MSG000151", $result);
    }

    public function tipoDocumento()
    {
        $result = [
            ['valor' => 0, 'descricao' => 'Estoque Kardex'],
            ['valor' => 1, 'descricao' => 'Estoque Razão'],
        ];
        return $this->response(200, "MSG000151", $result);
    }

    public function sexo()
    {
        $result = [
            ['valor' => 2, 'descricao' => 'Feminino'],
            ['valor' => 1, 'descricao' => 'Masculino'],
            ['valor' => 0, 'descricao' => 'Outros'],
        ];
        return $this->response(200, "MSG000151", $result);
    }

    public function status()
    {
        $result = [
            ['valor' => 0, 'descricao' => 'Inativo'],
            ['valor' => 1, 'descricao' => 'Ativo'],
        ];
        return $this->response(200, "MSG000151", $result);
    }
}
