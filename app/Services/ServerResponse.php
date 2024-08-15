<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\JsonResponse;

class ServerResponse
{
    public static function generateResponse($message, $status = 200, $data = NULL): JsonResponse
    {
        if ($data != NULL) {
            return response()->json([
                'data' => $data,
                'message' => $message,
            ], $status);
        }

        return response()->json([
            'message' => $message,
        ], $status);
    }
}