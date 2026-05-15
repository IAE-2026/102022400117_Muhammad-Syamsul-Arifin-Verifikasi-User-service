<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Verification;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class VerificationController extends Controller
{
    use ApiResponse;

    #[OA\Post(
        path: '/api/v1/verifications',
        summary: 'Mengirim data pengajuan verifikasi baru',
        tags: ['Verifications'],
        security: [['ApiKeyAuth' => []]]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['user_id', 'nik', 'bank_account_number'],
            properties: [
                new OA\Property(property: 'user_id', type: 'integer', example: 1),
                new OA\Property(property: 'nik', type: 'string', example: '3271234567890001'),
                new OA\Property(property: 'bank_account_number', type: 'string', example: '1234567890')
            ]
        )
    )]
    #[OA\Response(response: 201, description: 'Data verifikasi berhasil dikirim')]
    #[OA\Response(response: 400, description: 'Validasi Gagal')]
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|unique:verifications,user_id',
            'nik' => 'required|string|size:16|unique:verifications,nik',
            'bank_account_number' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi Gagal', 400, $validator->errors());
        }

        $verification = Verification::create([
            'user_id' => $request->user_id,
            'nik' => $request->nik,
            'bank_account_number' => $request->bank_account_number,
            'verification_status' => 'VERIFIED'
        ]);

        return $this->successResponse($verification, 'Data verifikasi berhasil dikirim', 201);
    }

    #[OA\Get(
        path: '/api/v1/verifications',
        summary: 'Mengambil daftar semua pengajuan verifikasi',
        tags: ['Verifications'],
        security: [['ApiKeyAuth' => []]]
    )]
    #[OA\Response(response: 200, description: 'Berhasil mengambil daftar verifikasi')]
    public function index()
    {
        $verifications = Verification::all();
        return $this->successResponse($verifications, 'Berhasil mengambil daftar verifikasi');
    }

    #[OA\Get(
        path: '/api/v1/verifications/{id}',
        summary: 'Mengecek status verifikasi berdasarkan user_id',
        tags: ['Verifications'],
        security: [['ApiKeyAuth' => []]]
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'ID User',
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(response: 200, description: 'Berhasil mengambil detail verifikasi')]
    #[OA\Response(response: 404, description: 'Data verifikasi tidak ditemukan')]
    public function show($id)
    {
        $verification = Verification::where('user_id', $id)->first();

        if (!$verification) {
            return $this->errorResponse('Data verifikasi untuk user ini tidak ditemukan', 404);
        }

        return $this->successResponse($verification, 'Berhasil mengambil detail verifikasi');
    }
}