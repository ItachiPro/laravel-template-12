<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    public static function errorResponse($message, $errors = null, $code = 400)
    {
        return response()->json([
            "success" => false,
            "message" => $message,
            "data" => null,
            "errors" => $errors
        ], $code);
    }

    public static function successResponse($data = null, $message = "", $code = 200)
    {
        return response()->json([
            "success" => true,
            "message" => $message,
            "data" => $data,
            "errors" => null
        ], $code);
    }

    public static function deletedResponse()
    {
        return response()->noContent();
    }
}