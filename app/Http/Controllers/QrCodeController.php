<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QrCodeController extends Controller
{
    /**
     * Generate QR code for authenticated user
     */
    public function generate()
    {
        $user = Auth::user();
        $qrCode = QrCode::generateForUser($user->id);

        // QR code data to encode
        $qrData = json_encode([
            'token' => $qrCode->token,
            'url' => url('/api/v1/qr/verify'),
        ]);

        return view('qr-code', [
            'qrCode' => $qrCode,
            'qrData' => $qrData,
        ]);
    }

    /**
     * Get QR code data as JSON (for AJAX polling)
     */
    public function getQrData(Request $request)
    {
        $user = Auth::user();
        
        // Get or create QR code
        $qrCode = QrCode::where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->first();

        if (!$qrCode || $qrCode->isExpired()) {
            $qrCode = QrCode::generateForUser($user->id);
        }

        return response()->json([
            'success' => true,
            'token' => $qrCode->token,
            'qr_data' => json_encode([
                'token' => $qrCode->token,
                'url' => url('/api/v1/qr/verify'),
            ]),
            'expires_at' => $qrCode->expires_at,
            'expires_in' => $qrCode->expires_at->diffInSeconds(now()),
        ]);
    }
}
