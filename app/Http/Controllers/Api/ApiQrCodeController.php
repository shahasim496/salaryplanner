<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QrCode;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ApiQrCodeController extends Controller
{
    /**
     * Verify QR code token and authenticate user
     */
    public function verify(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'token' => ['required', 'string', 'size:64'],
            ]);

            $qrCode = QrCode::where('token', $request->token)
                ->where('status', 'pending')
                ->first();

            if (!$qrCode) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired QR code',
                ], 404);
            }

            if ($qrCode->isExpired()) {
                $qrCode->markAsExpired();
                return response()->json([
                    'success' => false,
                    'message' => 'QR code has expired. Please generate a new one.',
                ], 410);
            }

            // Mark QR code as scanned
            $qrCode->markAsScanned();

            // Get user and create token
            $user = $qrCode->user;
            $token = $user->createToken('mobile-app')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Authentication successful',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'email_verified_at' => $user->email_verified_at,
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at,
                    ],
                    'token' => $token,
                    'token_type' => 'Bearer',
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Verification failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check QR code status (for polling)
     */
    public function checkStatus(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'token' => ['required', 'string', 'size:64'],
            ]);

            $qrCode = QrCode::where('token', $request->token)->first();

            if (!$qrCode) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR code not found',
                    'status' => 'not_found',
                ], 404);
            }

            if ($qrCode->isExpired() && $qrCode->status === 'pending') {
                $qrCode->markAsExpired();
                return response()->json([
                    'success' => false,
                    'message' => 'QR code expired',
                    'status' => 'expired',
                ], 410);
            }

            return response()->json([
                'success' => true,
                'status' => $qrCode->status,
                'expires_at' => $qrCode->expires_at,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Check failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
