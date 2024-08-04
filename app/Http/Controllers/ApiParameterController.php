<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class ApiParameterController extends Controller
{
    public function showParameters(): JsonResponse
    {
        $parameters = [];
        try {
            $parameters = getAllParametersWithImages();
        } catch (Throwable $exception) {
            Log::error($exception->getMessage());
        }

        return response()->json($parameters);
    }
}
