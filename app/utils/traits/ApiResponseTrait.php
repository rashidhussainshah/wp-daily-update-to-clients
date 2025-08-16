<?php

namespace App\utils\traits;

trait ApiResponseTrait
{
    /**
     * Return a success response.
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($data, $message = 'Operation Successful', $code = 200)
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ], $code);
    }

    /**
     * Return an error response.
     *
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse($message = 'Operation Failed', $code = 500, $errors = null)
    {
        return response()->json([
            'success' => false,
            'data' => null,
            'message' => $message,
            'code' => $code,
            'errors' => $errors,
        ], $code);
    }

}
