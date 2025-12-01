<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'bexio_country_id',
        'name',
        'name_short',
        'iso3166_alpha2',
        'default',
    ];

    public static function getDefaultCounry(): ?int
    {
        return self::where('default', true)->pluck('bexio_country_id')->first();
    }
}
