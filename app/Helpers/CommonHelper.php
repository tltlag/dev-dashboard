<?php

namespace App\Helpers;

use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CommonHelper
{
    public const BULK_ACTION_ACTIVE = 'ACTIVE';
    public const BULK_ACTION_IN_ACTIVE = 'IN_ACTIVE';
    public const BULK_ACTION_DELETE = 'DELETE';
    public const VALID_UPLOAD_MIME_TYPES = [
        '3g2' => 'video/3gpp2',
        '3gp' => 'video/3gpp',
        'avi' => 'video/x-msvideo',
        'flv' => 'video/x-flv',
        'm4a' => 'audio/mp4',
        'm4v' => 'video/x-m4v',
        'mov' => 'video/quicktime',
        'mp3' => 'audio/mpeg',
        'mp4' => 'video/mp4',
        'mpeg' => 'video/mpeg',
        'ogg' => 'audio/ogg',
        'ogv' => 'video/ogg',
        'webm' => 'video/webm',
        'wav' => 'audio/x-wav',
        'text' => 'text/plain',
        'csv' => 'text/csv',
        'doc' => 'application/msword',
        'dot' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
        'docm' => 'application/vnd.ms-word.document.macroEnabled.12',
        'dotm' => 'application/vnd.ms-word.template.macroEnabled.12',
        'xls' => 'application/vnd.ms-excel',
        'xlt' => 'application/vnd.ms-excel',
        'xla' => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'xltx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
        'xlsm' => 'application/vnd.ms-excel.sheet.macroEnabled.12',
        'xltm' => 'application/vnd.ms-excel.template.macroEnabled.12',
        'xlam' => 'application/vnd.ms-excel.addin.macroEnabled.12',
        'xlsb' => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
        'ppt' => 'application/vnd.ms-powerpoint',
        'pot' => 'application/vnd.ms-powerpoint',
        'pps' => 'application/vnd.ms-powerpoint',
        'ppa' => 'application/vnd.ms-powerpoint',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'potx' => 'application/vnd.openxmlformats-officedocument.presentationml.template',
        'ppsx' => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
        'ppam' => 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
        'pptm' => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
        'potm' => 'application/vnd.ms-powerpoint.template.macroEnabled.12',
        'ppsm' => 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
        'mdb' => 'application/vnd.ms-access',
        'pdf' => 'application/pdf',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'svg' => 'image/svg+xml',
    ];

    public static function getValidExtensions(): array
    {
        return array_keys(self::VALID_UPLOAD_MIME_TYPES);
    }

    public static function clearArtisanConfig(): bool
    {
        try {
            Artisan::call('config:clear');
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    public static function generateLocalizations()
    {
        try {
            Artisan::call('generate:translations');
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    public static function getAllTimeZones(): array
    {
        $allTimezones = DateTimeZone::listIdentifiers();
        $all = [];

        foreach ($allTimezones as $timezone) {
            $tz = new DateTimeZone($timezone);
            $now = new DateTime('now', $tz);
            $offset = $tz->getOffset($now) / 3600; // Get offset in hours
            $offsetString = ($offset >= 0 ? '+' : '-') . abs($offset);

            $all[$timezone] = "$timezone (UTC $offsetString)\n";
        }

        return $all;
    }

    public static function dateTime(string $dateTime, bool $convertTimeZone = true, $newTimeZone = false): string
    {
        $dateFormat = config('global.DATE_FORMT', 'Y-m-d');
        $timeFormat = config('global.TIME_FORMAT', 'H:i:s');
        Carbon::setLocale('en');
        $carbonDate = Carbon::parse($dateTime);

        if ($convertTimeZone) {
            $carbonDate->tz(config('global.DEFAULT_TIMEZONE', 'UTC'));
        }
        if ($newTimeZone) {
            $carbonDate->tz($newTimeZone);
        }

        return $carbonDate->translatedFormat($dateFormat . ' ' . $timeFormat);
    }

    public static function date(string $dateTime, bool $convertTimeZone = true): string
    {
        $dateFormat = config('global.DATE_FORMT', 'Y-m-d');
        $carbonDate = Carbon::parse($dateTime);

        if ($convertTimeZone) {
            $carbonDate->tz(config('global.DEFAULT_TIMEZONE', 'UTC'));
        }

        return $carbonDate->translatedFormat($dateFormat);
    }

    public static function time(string $dateTime, bool $convertTimeZone = true): string
    {
        $timeFormat = config('global.TIME_FORMAT', 'H:i:s');
        $carbonDate = Carbon::parse($dateTime);

        if ($convertTimeZone) {
            $carbonDate->tz(config('global.DEFAULT_TIMEZONE', 'UTC'));
        }

        return $carbonDate->translatedFormat($timeFormat);
    }

    public static function convertSecondsToTime(int $seconds): string
    {
        $seconds = max(0, $seconds);
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remainingSeconds = $seconds % 60;

        if ($remainingSeconds > 0) {
            $minutes += 1;
        }

        return sprintf("%02d:%02d", $hours, $minutes);
    }

    public static function bulkActionList(): array
    {
        return [
            self::BULK_ACTION_ACTIVE,
            self::BULK_ACTION_IN_ACTIVE,
            self::BULK_ACTION_DELETE,
        ];
    }

    public static function bulkActionListOptions(): array
    {
        return [
            self::BULK_ACTION_ACTIVE => __('Active'),
            self::BULK_ACTION_IN_ACTIVE => __('In-active'),
            self::BULK_ACTION_DELETE => __('Delete'),
        ];
    }

    public static function bulkAction(string $action): ?string
    {
        return self::bulkActionListOptions()[$action] ?? null;
    }

    public static function getUserProfilePath(): string
    {
        return 'profile/' . date('Y') . '/' . date('m') . '/' . date('d');
    }

    public static function getEditorPath(): string
    {
        return 'uploads/' . date('Y') . '/' . date('m') . '/' . date('d');
    }

    public static function getExcerpt($htmlContent = ''): string
    {
        $dom = new \DOMDocument();
        $dom->loadHTML($htmlContent);
        $paragraphs = $dom->getElementsByTagName('p');
        $description = '';

        if ($paragraphs->length > 0) {
            $description = $paragraphs->item(0)->textContent;
            $maxDescriptionLength = 150;
            $description = mb_strimwidth($description, 0, $maxDescriptionLength, '...');
        }

        return $description;
    }

    public static function truncateString($text, $maxLength = 200, $ellipsis = '...')
    {
        if (mb_strlen($text) > $maxLength) {
            $truncatedText = mb_substr($text, 0, $maxLength) . $ellipsis;

            return $truncatedText;
        }

        return $text;
    }

    public function transDb($key, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();

        return config("languages.{$locale}.{$key}", $key);
    }

    public static function isIframeRequest(Request $request): bool
    {
        return false;
        // return (bool) ($request->server->get('HTTP_SEC_FETCH_DEST') === 'iframe');
        // return (bool) $request->headers->get('X-Iframe-Request', false);
    }

    public static function convertSecondsToHours(int $seconds): string
    {
        $hours = $seconds / 3600;
        $minutes = ($seconds % 3600) / 60;
        $minutes = $minutes < 0 ? $minutes * -1 : $minutes;

        return sprintf('%02d:%02d h', $hours, $minutes);
    }

    public static function getDifferenceInHours(int $start, int $end): string
    {
        $diff = $end - $start;
        $hours = floor($diff / 3600);
        $minutes = floor(($diff % 3600) / 60);

        return sprintf('%02d:%02d h', $hours, $minutes);
    }
}
