<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Translation extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'locale', 'value'];

    public const LANG_CODE_ENGLISH = 'en';
    public const LANG_CODE_GERMAN = 'de';

    public static function getLanguages(): array
    {
        return [
            self::LANG_CODE_ENGLISH => __('English'),
            self::LANG_CODE_GERMAN => __('German'),
        ];
    }

    public static function getLanguage(string $langCode): array
    {
        return self::getLanguages()[$langCode] ?? null;
    }

    public static function generateTranslationFiles()
    {
        $translations = self::all();
        $groupedTranslations = $translations->groupBy('locale');

        foreach ($groupedTranslations as $locale => $translations) {
            $langFile = resource_path('lang/') . '/' . $locale . '.json';

            if (File::exists($langFile)) {
                File::delete($langFile, true);
            }

            $langData = [];
            foreach ($translations as $translation) {
                $langData[$translation->key] = $translation->value;
            }

            $content = json_encode($langData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            file_put_contents($langFile, $content);
        }
    }
}
