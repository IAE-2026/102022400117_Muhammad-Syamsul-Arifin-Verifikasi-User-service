<?php

namespace App\Traits;

trait ApiResponse
{
    /**
     * Format untuk respons berhasil (2xx) sesuai kontrak.
     */
    protected function successResponse($data, $message = 'Success', $code = 200, $meta = null)
    {
        $response = [
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ];

        // Tambahkan meta jika ada (opsional sesuai kontrak)
        if ($meta) {
            $response['meta'] = $meta;
        } else {
            // Meta default sesuai standar
            $response['meta'] = [
                'service_name' => 'Verifikasi-Service',
                'api_version' => 'v1'
            ];
        }

        return response()->json($response, $code);
    }

    /**
     * Format untuk respons gagal (4xx/5xx) sesuai kontrak.
     */
    protected function errorResponse($message = 'Error', $code = 400, $errors = null)
    {
        $response = [
            'status' => 'error',
            'message' => $message,
        ];

        // Tambahkan detail errors jika ada (misal dari validasi form)
        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }
}