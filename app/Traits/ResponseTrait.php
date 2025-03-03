<?php
namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseTrait
{
    public function successResponse(string $message, mixed $data = [], int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    public function validationErrorResponse(array $errors, string $message = "Validation failed", int $statusCode = 422): JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }

    public function errorResponse(string $message, string $error = "", int $statusCode = 500): JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'error' => $error
        ], $statusCode);
    }

    private function handleException($e): JsonResponse
    {
        return $this->errorResponse('An unexpected error occurred.', $e->getMessage());
    }
}
