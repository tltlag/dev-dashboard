<?php

namespace App\Traits;

trait StatusColumn
{
    public const STATUS_IN_ACTIVE = 0;
    public const STATUS_ACTIVE = 1;

    public static function getStatusList(): array
    {
        return [
            self::STATUS_IN_ACTIVE,
            self::STATUS_ACTIVE,
        ];
    }

    public static function getStatusListOptions(): array
    {
        return [
            self::STATUS_IN_ACTIVE => __('In Active'),
            self::STATUS_ACTIVE => __('Active'),
        ];
    }

    public static function getStatus(int $status): ?string
    {
        return self::getStatusListOptions()[$status] ?? null;
    }
}
