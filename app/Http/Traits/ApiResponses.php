<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

trait ApiResponses
{
   
    protected function success(string $message = 'Success.', mixed $data = null, int $status = 200): JsonResponse
    {
        $payload = [
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            $payload['data'] = $data instanceof JsonResource ? $data->resolve() : $data;
        }

        return response()->json($payload, $status);
    }

    protected function error(string $message = 'Something went wrong.', mixed $errors = null, int $status = 400): JsonResponse
    {
        $payload = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $status);
    }
}