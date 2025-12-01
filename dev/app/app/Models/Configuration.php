<?php

namespace App\Models;

use App\Helpers\CommonHelper;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'key',
        'value',
    ];

    protected $attributes = [
        'group' => self::GROUP_TYPE_GENERAL,
    ];

    public const GROUP_TYPE_GENERAL = 'GENERAL';
    public const GROUP_TYPE_BEXIO = 'BEXIO';
    public const GROUP_TYPE_WILDIX = 'WILDIX';
    public const GROUP_TYPE_CLOCKODO = 'CLOCKODO';
    public const GROUP_TYPE_SEARCHCH = 'SEARCHCH';
    public const GROUP_TYPE_THEMEAPPEARANCEDARK = 'THEME-APPEARANCE-DARK';
    public const GROUP_TYPE_THEMEAPPEARANCELIGHT = 'THEME-APPEARANCE-LIGHT';
    
    public const COLOR_ENABLE_YES = 'yes';
    public const COLOR_ENABLE_NO = 'no';

    public static function getColorOptions(): array
    {
        return [
            self::COLOR_ENABLE_YES => __('Yes'),
            self::COLOR_ENABLE_NO => __('No'),
        ];
    }

   

    public const CALL_FORCEFUL_END_AFTER = 120; //Minutes

    public static function get(string $group, string $key): string | array | null
    {
        try {
            $model = self::where([
                'group' => strtoupper($group),
                'key' => strtoupper($key),
            ])->first();
        } catch (Exception $e) {
            return null;
        }

        return self::getValue($model->value);
    }

    protected static function getValue(?string $value = null): string | array | null
    {
        $data = @unserialize($value);

        return $data === false ? $value : $data;
    }

    public static function getGroupList(): array
    {
        return [
            self::GROUP_TYPE_GENERAL,
            self::GROUP_TYPE_BEXIO,
            self::GROUP_TYPE_WILDIX,
            self::GROUP_TYPE_CLOCKODO,
            self::GROUP_TYPE_SEARCHCH,
            self::GROUP_TYPE_THEMEAPPEARANCEDARK,
            self::GROUP_TYPE_THEMEAPPEARANCELIGHT,
            
            
        ];
    }

    public static function getGroupListOptions(): array
    {
        return [
            self::GROUP_TYPE_GENERAL => __('General Settings'),
            self::GROUP_TYPE_BEXIO => __('Bexio Settings'),
            self::GROUP_TYPE_WILDIX => __('Wildix Settings'),
            self::GROUP_TYPE_CLOCKODO => __('Clockodo Settings'),
            self::GROUP_TYPE_SEARCHCH => __('Search Ch Settings'),
            self::GROUP_TYPE_THEMEAPPEARANCEDARK => __('Theme Appearance Dark'),
            self::GROUP_TYPE_THEMEAPPEARANCELIGHT => __('Theme Appearance Light'),
        ];
    }

    public static function getGroup(string $group): ?string
    {
        return self::getGroupListOptions()[$group] ?? null;
    }

    public static function settingList(): array
    {
        return [
            self::GROUP_TYPE_GENERAL => [
                'SITE_TITLE' => [
                    'label' => __('Site Title'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'text',
                    'id' => 'site-title',
                    'class' => '',
                    'name' => 'SITE_TITLE',
                    'placeholder' => __('Site Title'),
                ],
                'SITE_EMAIL' => [
                    'label' => __('Site Email'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'email',
                    'id' => 'site-email',
                    'class' => '',
                    'name' => 'SITE_EMAIL',
                    'placeholder' => __('Enter site email address'),
                ],
                'SITE_PHONE' => [
                    'label' => __('Site Phone Number'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'text',
                    'id' => 'site-phone',
                    'class' => '',
                    'name' => 'SITE_PHONE',
                    'placeholder' => __('Enter site phone number'),
                ],
                'SITE_ADDRESS' => [
                    'label' => __('Address'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'textarea',
                    'id' => 'site-address',
                    'class' => '',
                    'name' => 'SITE_ADDRESS',
                    'placeholder' => __('Enter site address'),
                ],
                'DEFAULT_TIMEZONE' => [
                    'label' => __('Timezone'),
                    'required' => true,
                    'default_value' => 'UTC',
                    'type' => 'select',
                    'options' => CommonHelper::getAllTimeZones(),
                    'id' => 'time-zone',
                    'class' => '',
                    'name' => 'DEFAULT_TIMEZONE',
                    'placeholder' => __('Select site timezone'),
                ],
                'DATE_FORMT' => [
                    'label' => __('Date Format'),
                    'required' => true,
                    'default_value' => 'l, M d Y',
                    'type' => 'select',
                    'options' => [
                        'l, M d Y' => 'l, M d Y',
                        'M d Y' => 'M d Y',
                        'm d Y' => 'm d Y',
                        'Y-m-d' => 'Y-m-d',
                    ],
                    'id' => 'date-format',
                    'class' => '',
                    'name' => 'DATE_FORMT',
                    'placeholder' => __('Select date format'),
                ],
                'TIME_FORMAT' => [
                    'label' => __('Time Format'),
                    'required' => true,
                    'default_value' => 'l, M d Y',
                    'type' => 'radio',
                    'options' => [
                        'h:i a' => 'h:i a',
                        'H:i:s' => 'H:i:s',
                    ],
                    'id' => 'time-format',
                    'class' => '',
                    'name' => 'TIME_FORMAT',
                    'placeholder' => __('Select kl'),
                ],
                'SITE_LOGO' => [
                    'label' => __('Site Logo'),
                    'required' => false,
                    'default_value' => '',
                    'type' => 'file',
                    'id' => 'site-logo',
                    'class' => '',
                    'name' => 'SITE_LOGO',
                    'placeholder' => __('Upload site logo'),
                ],
                'SITE_FOOTER_LOGO' => [
                    'label' => __('Site Footer Logo'),
                    'required' => false,
                    'default_value' => '',
                    'type' => 'file',
                    'id' => 'site-footer-logo',
                    'class' => '',
                    'name' => 'SITE_FOOTER_LOGO',
                    'placeholder' => __('Upload site footer logo'),
                ],
                'SITE_FOOTER_SLOGAN' => [
                    'label' => __('Site Footer Slogan'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'text',
                    'id' => 'site-title',
                    'class' => '',
                    'name' => 'SITE_FOOTER_SLOGAN',
                    'placeholder' => __('Site Footer Slogan'),
                ],
                'SITE_LOCALE' => [
                    'label' => __('Website Lanugage'),
                    'required' => true,
                    'default_value' => Translation::LANG_CODE_ENGLISH,
                    'type' => 'select',
                    'options' => Translation::getLanguages(),
                    'id' => 'site-locale',
                    'class' => '',
                    'name' => 'SITE_LOCALE',
                    'placeholder' => __('Website Lanugage'),
                ],
                'CALL_FORCEFUL_END_AFTER' => [
                    'label' => __('Call forcefully end after X minutes'),
                    'required' => true,
                    'default_value' => self::CALL_FORCEFUL_END_AFTER,
                    'type' => 'number',
                    'id' => 'cfea',
                    'class' => '',
                    'name' => 'CALL_FORCEFUL_END_AFTER',
                    'placeholder' => __('Call forcefully end after X minutes'),
                ],
            ],
            self::GROUP_TYPE_BEXIO => [
                'BEXIO_TOKEN' => [
                    'label' => __('Bexio Access Token'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'textarea',
                    'id' => 'b-token',
                    'class' => '',
                    'name' => 'BEXIO_TOKEN',
                    'placeholder' => __('Enter bexio access token'),
                ],
                'BEXIO_SYNC_AFTER' => [
                    'label' => __('Sync Contact After X Minutes (default will be 15 minutes)'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'number',
                    'id' => 'sync-after',
                    'class' => '',
                    'name' => 'BEXIO_SYNC_AFTER',
                    'placeholder' => __('Enter minutes after contacts will sync.'),
                ],
            ],
            self::GROUP_TYPE_WILDIX => [
                'WILDIXIN_HOST' => [
                    'label' => __('Host'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'text',
                    'id' => 'w-host',
                    'class' => '',
                    'name' => 'WILDIXIN_HOST',
                    'placeholder' => __('Enter wildix host'),
                ],
                'WILDIXIN_APP_ID' => [
                    'label' => __('App ID'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'text',
                    'id' => 'w-app-id',
                    'class' => '',
                    'name' => 'WILDIXIN_APP_ID',
                    'placeholder' => __('Enter wildix app id'),
                ],
                'WILDIXIN_SECRET_KEY' => [
                    'label' => __('Secret Key'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'text',
                    'id' => 'w-secret-key',
                    'class' => '',
                    'name' => 'WILDIXIN_SECRET_KEY',
                    'placeholder' => __('Enter wildix secret key'),
                ],
                'WILDIXIN_APP_NAME' => [
                    'label' => __('App Name'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'text',
                    'id' => 'w-app-name',
                    'class' => '',
                    'name' => 'WILDIXIN_APP_NAME',
                    'placeholder' => __('Enter wildix app name'),
                ],
            ],
            self::GROUP_TYPE_CLOCKODO => [
                'CLOCKODO_API_KEY' => [
                    'label' => __('Api Key'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'text',
                    'id' => 'c-api-key',
                    'class' => '',
                    'name' => 'CLOCKODO_API_KEY',
                    'placeholder' => __('Enter clockodo api key'),
                ],
                'CLOCKODO_USER_EMAIL' => [
                    'label' => __('User Email'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'email',
                    'id' => 'c-user-email',
                    'class' => '',
                    'name' => 'CLOCKODO_USER_EMAIL',
                    'placeholder' => __('Enter clockodo user email'),
                ],
                'CLOCKODO_COMPANY' => [
                    'label' => __('Company'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'text',
                    'id' => 'c-company',
                    'class' => '',
                    'name' => 'CLOCKODO_COMPANY',
                    'placeholder' => __('Enter clockodo company'),
                ],
            ],
            self::GROUP_TYPE_SEARCHCH => [
                'SEARCHCH_KEY' => [
                    'label' => __('Api Key'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'text',
                    'id' => 'sh-api-key',
                    'class' => '',
                    'name' => 'SEARCHCH_KEY',
                    'placeholder' => __('Enter search ch api key'),
                ],
            ],
            self::GROUP_TYPE_THEMEAPPEARANCEDARK => [

            'THEMEAPPEARANCEDARK_ENABLE' => [
                    'label' => __('Enable Colors'),
                    'required' => true,
                    'default_value' => self::COLOR_ENABLE_NO,
                    'type' => 'select',
                    'options' => self::getColorOptions(),
                    'id' => 'site-locale',
                    'class' => '',
                    'name' => 'color_enabled',
                    'placeholder' => __('Enable Colors'),
                ],
                // Background of page
                'THEMEAPPEARANCEDARK_PAGE_BACKGROUND' => [
                'label' => __('Page Background Color'),
                'required' => true,
                'default_value' => '',
                'type' => 'color',
                'id' => 'dark-package-background-color',
                'class' => 'theme_appearance col-sm-4',
                'name' => 'page_background_color',
                ],

                'THEMEAPPEARANCEDARK_PAGE_FONTCOLOR' => [
                    'label' => __('Page Font Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'dark-package-font-color',
                    'class' => 'theme_appearance col-sm-4',
                    'name' => 'page_font_color',
                    ],
                // Primary Colors
                'THEMEAPPEARANCEDARK_PRIMARY_FONTCOLOR' => [
                    'label' => __('Primary Font Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'dark-primary-font',
                    'class' => 'theme_appearance col-sm-4',
                    'name' => 'primary_font_color',
                ],
                'THEMEAPPEARANCEDARK_PRIMARY_BACKGROUNDCOLOR' => [
                    'label' => __('Primary Background Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'dark-primary-bg',
                    'class' => 'theme_appearance col-sm-4',
                    'name' => 'primary_background_color',
                ],
            
                // Secondary Colors
                'THEMEAPPEARANCEDARK_SECONDARY_FONTCOLOR' => [
                    'label' => __('Secondary Font Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'dark-secondary-font',
                    'class' => 'theme_appearance',
                    'name' => 'secondary_font_color',
                ],
                'THEMEAPPEARANCEDARK_SECONDARY_BACKGROUNDCOLOR' => [
                    'label' => __('Secondary Background Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'dark-secondary-bg',
                    'class' => 'theme_appearance',
                    'name' => 'secondary_background_color',
                ],
            
                // Status Colors
                'THEMEAPPEARANCEDARK_SUCCESS_FONTCOLOR' => [
                    'label' => __('Success Font Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'dark-success',
                    'class' => 'theme_appearance',
                    'name' => 'success_font_color',
                ],
                'THEMEAPPEARANCEDARK_SUCCESS_BACKGROUNDCOLOR' => [
                    'label' => __('Success Background Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'dark-success',
                    'class' => 'theme_appearance',
                    'name' => 'success_background_color',
                ],

                'THEMEAPPEARANCEDARK_DANGER_FONTCOLOR' => [
                    'label' => __('Danger Font Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'dark-font-danger',
                    'class' => 'theme_appearance',
                    'name' => 'danger_font_color',
                ],

                'THEMEAPPEARANCEDARK_DANGER_BACKGROUNDCOLOR' => [
                    'label' => __('Danger Background Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'dark-background-danger',
                    'class' => 'theme_appearance',
                    'name' => 'danger_background_color',
                ],
                'THEMEAPPEARANCEDARK_WARNING_FONTCOLOR' => [
                    'label' => __('Warning Font Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'dark-font-warning',
                    'class' => 'theme_appearance',
                    'name' => 'warning_font_color',
                ],
                'THEMEAPPEARANCEDARK_WARNING_BACKGROUNDCOLOR' => [
                    'label' => __('Warning Background Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'dark-background-warning',
                    'class' => 'theme_appearance',
                    'name' => 'warning_background_color',
                ],

                'THEMEAPPEARANCEDARK_INFO_FONTCOLOR' => [
                    'label' => __('Info Font Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'dark-font-info',
                    'class' => 'theme_appearance',
                    'name' => 'info_font_color',
                ],
                'THEMEAPPEARANCEDARK_INFO_BACKGROUNDCOLOR' => [
                    'label' => __('Info Background Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'dark-background-info',
                    'class' => 'theme_appearance',
                    'name' => 'info_background_color',
                ],
                // General Colors
                'THEMEAPPEARANCEDARK_LIGHT_FONTCOLOR' => [
                    'label' => __('Light Font Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'dark-font-light',
                    'class' => 'theme_appearance',
                    'name' => 'light_font_color',
                ],
                 // General Colors
                 'THEMEAPPEARANCEDARK_LIGHT_BACKGROUNDCOLOR' => [
                    'label' => __('Light Background Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'dark-background-light',
                    'class' => 'theme_appearance',
                    'name' => 'light_background_color',
                ],

                'THEMEAPPEARANCEDARK_DARK_FONTCOLOR' => [
                    'label' => __('Dark Font Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'dark-font-dark',
                    'class' => 'theme_appearance',
                    'name' => 'dark_font_color',
                ],
                'THEMEAPPEARANCEDARK_DARK_BACKGROUNDCOLOR' => [
                    'label' => __('Dark Background Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'dark-background-dark',
                    'class' => 'theme_appearance',
                    'name' => 'dark_background_color',
                ],

            ],
            
            self::GROUP_TYPE_THEMEAPPEARANCELIGHT => [
                    
                    'THEMEAPPEARANCELIGHT_ENABLE' => [
                    'label' => __('Enable Colors'),
                    'required' => true,
                    'default_value' => self::COLOR_ENABLE_NO,
                    'type' => 'select',
                    'options' => self::getColorOptions(),
                    'id' => 'site-locale',
                    'class' => '',
                    'name' => 'color_enabled',
                    'placeholder' => __('Enable Colors'),
                    ],
// Background of page
                    'THEMEAPPEARANCELIGHT_PAGE_BACKGROUND' => [
                    'label' => __('Page Background Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'light-package-background-color',
                    'class' => 'theme_appearance col-sm-4',
                    'name' => 'page_background_color',
                    ],
// Font of page
                    'THEMEAPPEARANCELIGHT_PAGE_FONTCOLOR' => [
                    'label' => __('Page Font Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'light-package-font-color',
                    'class' => 'theme_appearance col-sm-4',
                    'name' => 'page_font_color',
                    ],
                // Primary Colors
                    'THEMEAPPEARANCELIGHT_PRIMARY_FONTCOLOR' => [
                    'label' => __('Primary Font Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'light-primary-font',
                    'class' => 'theme_appearance col-sm-4',
                    'name' => 'primary_font_color',
                    ],
                    'THEMEAPPEARANCELIGHT_PRIMARY_BACKGROUNDCOLOR' => [
                    'label' => __('Primary Background Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'light-primary-bg',
                    'class' => 'theme_appearance col-sm-4',
                    'name' => 'primary_background_color',
                    ],
            
                // Secondary Colors
                    'THEMEAPPEARANCELIGHT_SECONDARY_FONTCOLOR' => [
                    'label' => __('Secondary Font Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'light-secondary-font',
                    'class' => 'theme_appearance',
                    'name' => 'secondary_font_color',
                    ],
                    'THEMEAPPEARANCELIGHT_SECONDARY_BACKGROUNDCOLOR' => [
                    'label' => __('Secondary Background Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'light-secondary-bg',
                    'class' => 'theme_appearance',
                    'name' => 'secondary_background_color',
                    ],
            
                // Status Colors
                    'THEMEAPPEARANCELIGHT_SUCCESS_FONTCOLOR' => [
                    'label' => __('Success Font Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'light-success',
                    'class' => 'theme_appearance',
                    'name' => 'success_font_color',
                    ],
                    'THEMEAPPEARANCELIGHT_SUCCESS_BACKGROUNDCOLOR' => [
                    'label' => __('Success Background Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'light-success',
                    'class' => 'theme_appearance',
                    'name' => 'success_background_color',
                    ],

                    'THEMEAPPEARANCELIGHT_DANGER_FONTCOLOR' => [
                    'label' => __('Danger Font Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'light-font-danger',
                    'class' => 'theme_appearance',
                    'name' => 'danger_font_color',
                    ],

                    'THEMEAPPEARANCELIGHT_DANGER_BACKGROUNDCOLOR' => [
                    'label' => __('Danger Background Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'light-background-danger',
                    'class' => 'theme_appearance',
                    'name' => 'danger_background_color',
                    ],
                    'THEMEAPPEARANCELIGHT_WARNING_FONTCOLOR' => [
                    'label' => __('Warning Font Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'light-font-warning',
                    'class' => 'theme_appearance',
                    'name' => 'warning_font_color',
                    ],
                    'THEMEAPPEARANCELIGHT_WARNING_BACKGROUNDCOLOR' => [
                    'label' => __('Warning Background Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'light-background-warning',
                    'class' => 'theme_appearance',
                    'name' => 'warning_background_color',
                    ],

                    'THEMEAPPEARANCELIGHT_INFO_FONTCOLOR' => [
                    'label' => __('Info Font Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'light-font-info',
                    'class' => 'theme_appearance',
                    'name' => 'info_font_color',
                    ],
                    'THEMEAPPEARANCELIGHT_INFO_BACKGROUNDCOLOR' => [
                    'label' => __('Info Background Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'light-background-info',
                    'class' => 'theme_appearance',
                    'name' => 'info_background_color',
                    ],
                // General Colors
                    'THEMEAPPEARANCELIGHT_LIGHT_FONTCOLOR' => [
                    'label' => __('Light Font Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'light-font-light',
                    'class' => 'theme_appearance',
                    'name' => 'light_font_color',
                    ],
                 // General Colors
                    'THEMEAPPEARANCELIGHT_LIGHT_BACKGROUNDCOLOR' => [
                    'label' => __('Light Background Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'light-background-light',
                    'class' => 'theme_appearance',
                    'name' => 'light_background_color',
                    ],

                    'THEMEAPPEARANCELIGHT_DARK_FONTCOLOR' => [
                    'label' => __('Dark Font Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'light-font-dark',
                    'class' => 'theme_appearance',
                    'name' => 'dark_font_color',
                    ],
                    'THEMEAPPEARANCELIGHT_DARK_BACKGROUNDCOLOR' => [
                    'label' => __('Dark Background Color'),
                    'required' => true,
                    'default_value' => '',
                    'type' => 'color',
                    'id' => 'light-background-dark',
                    'class' => 'theme_appearance',
                    'name' => 'dark_background_color',
                    ],
 
            ],
            
        ];
    }

    public static function getSetting(string $group): array
    {
        return self::settingList()[$group] ?? [];
    }

    public static function boot(): void
    {
        parent::boot();

        self::created(function ($model) {
            CommonHelper::clearArtisanConfig();
        });

        self::updated(function ($model) {
            CommonHelper::clearArtisanConfig();
        });

        self::deleted(function ($model) {
            CommonHelper::clearArtisanConfig();
        });
    }
}
