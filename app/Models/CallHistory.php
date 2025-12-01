<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CallHistory extends Model
{
    use HasFactory;

    public const CALL_TYPE_OUTGOING = 'OUTGOING';
    public const CALL_TYPE_INCOMING = 'INCOMING';

    public static function getCallTypes(): array
    {
        return [
            self::CALL_TYPE_INCOMING => __('Incoming'),
            self::CALL_TYPE_OUTGOING => __('Outgoing'),
        ];
    }

    public static function getCallTypeText(string $callType): ?string
    {
        return self::getCallTypes()[$callType] ?? null;
    }

    public function timeLogs(): HasMany
    {
        return $this->hasMany(TimeLog::class);
    }
}
