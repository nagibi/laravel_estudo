<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function response($statusCode, $message, $data=null)
    {
        $response = null;
        switch ($statusCode) {
            case 400:
            case 404:
            case 500:
                $response = response()->json([
                    "status" => "Erro",
                    "statusCode" => $statusCode,
                    "message" => $message,
                    "erros" => null,
                    "result" => $data,
                ], $statusCode);
                break;
            case 200:
            case 201:
            case 202:
            case 204:
                $response = response()->json([
                    "status" => "Sucesso",
                    "statusCode" => $statusCode,
                    "message" => $message,
                    "erros" => null,
                    "result" => $data,
                ], $statusCode);
                break;

            default:
            $response = response()->json([
                    "statusCode" => $statusCode,
                    "message" => $message,
                    "erros" => null,
                    "result" => $data,
                ], $statusCode);
                break;
        }

        return $response;
    }

    protected function erros($message="MSG000071", $validator=null)
    {
        $index=0;
        foreach ($validator->errors()->keys() as $error) {
            $erros[] = array("field"=> $error, "message"=> $validator->errors()->all()[$index]);
            $index++;
        }

        return response()->json([
            "statusCode" => 404,
            "message" => $message,
            "errors" => $erros
        ], 404);
    }

    protected function erro($message="MSG000071", $fieldName,$messageFiel)
    {
        $erros[] = array("field"=> $fieldName, "message"=> $messageFiel);

        return response()->json([
            "statusCode" => 404,
            "message" => $message,
            "errors" => $erros
        ], 404);
    }
}
