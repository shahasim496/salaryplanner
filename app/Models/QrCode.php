<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class QrCode extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'status',
        'expires_at',
        'scanned_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'scanned_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function generateForUser(int $userId): self
    {
        // Delete any existing pending QR codes for this user
        self::where('user_id', $userId)
            ->where('status', 'pending')
            ->delete();

        // Create new QR code
        return self::create([
            'user_id' => $userId,
            'token' => Str::random(64),
            'status' => 'pending',
            'expires_at' => now()->addMinutes(5), // QR code expires in 5 minutes
        ]);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function markAsScanned(): void
    {
        $this->update([
            'status' => 'scanned',
            'scanned_at' => now(),
        ]);
    }

    public function markAsExpired(): void
    {
        $this->update(['status' => 'expired']);
    }
}
