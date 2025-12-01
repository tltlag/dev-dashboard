<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserHasOngoingCall extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'extension_number',
        'phone_number',
        'status',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
