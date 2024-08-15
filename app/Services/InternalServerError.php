<?php

declare(strict_types=1);

namespace App\Services;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class InternalServerError
{
    public static function generate($method): JsonResponse
    {
        Log::error("Error 500, something went wrong with {$method}!");
        return response()->json([
            'message' => 'Unexpected error occurred',
        ], 500);
    }
}