@if(config('global.THEMEAPPEARANCEDARK_ENABLE') == 'yes')

<style> 
body.darkTheme, .darkTheme .header, .darkTheme .sidebar-nav-wrapper, .darkTheme .card-style,.darkTheme .sidebar-nav-wrapper .sidebar-nav ul .nav-item a , .darkTheme span, .darkTheme a, .darkTheme .input-style-1 label, .darkTheme .input-style-2 label, .darkTheme .input-style-3 label,.darkTheme .breadcrumb-wrapper .breadcrumb li a,.darkTheme .header .header-right .profile-info .info h6{
    color: {{ config('global.THEMEAPPEARANCEDARK_PAGE_FONTCOLOR', '') }};
    background-color: {{ config('global.THEMEAPPEARANCEDARK_PAGE_BACKGROUND', '') }};
    border-color: {{ config('global.THEMEAPPEARANCEDARK_PAGE_BACKGROUND', '') }};
}



body.darkTheme .btn-primary,body.darkTheme .primary-btn {
    color: {{ config('global.THEMEAPPEARANCEDARK_PRIMARY_FONTCOLOR', '') }};
    background-color: {{ config('global.THEMEAPPEARANCEDARK_PRIMARY_BACKGROUNDCOLOR', '') }};
    border-color: {{ config('global.THEMEAPPEARANCEDARK_PRIMARY_BACKGROUNDCOLOR', '') }};
}

body.darkTheme .btn-secondary,body.darkTheme .secondary-btn {
    color: {{ config('global.THEMEAPPEARANCEDARK_SECONDARY_FONTCOLOR', '') }};
    background-color: {{ config('global.THEMEAPPEARANCEDARK_SECONDARY_BACKGROUNDCOLOR', '') }};
    border-color: {{ config('global.THEMEAPPEARANCEDARK_SECONDARY_BACKGROUNDCOLOR', '') }};
}

body.darkTheme .btn-success ,body.darkTheme .success-btn {
    color: {{ config('global.THEMEAPPEARANCEDARK_SUCCESS_FONTCOLOR', '') }};
    background-color: {{ config('global.THEMEAPPEARANCEDARK_SUCCESS_BACKGROUNDCOLOR', '') }};
    border-color: {{ config('global.THEMEAPPEARANCEDARK_SUCCESS_BACKGROUNDCOLOR', '') }};
}

body.darkTheme .btn-danger,body.darkTheme .danger-btn {
    color: {{ config('global.THEMEAPPEARANCEDARK_DANGER_FONTCOLOR', '') }};
    background-color: {{ config('global.THEMEAPPEARANCEDARK_DANGER_BACKGROUNDCOLOR', '') }};
    border-color: {{ config('global.THEMEAPPEARANCEDARK_DANGER_BACKGROUNDCOLOR', '') }};
}

body.darkTheme .btn-warning , body.darkTheme .warning-btn {
    color: {{ config('global.THEMEAPPEARANCEDARK_WARNING_FONTCOLOR', '') }};
    background-color: {{ config('global.THEMEAPPEARANCEDARK_WARNING_BACKGROUNDCOLOR', '') }};
    border-color: {{ config('global.THEMEAPPEARANCEDARK_WARNING_BACKGROUNDCOLOR', '') }};
}

body.darkTheme .btn-info,body.darkTheme .info-btn {
    color: {{ config('global.THEMEAPPEARANCEDARK_INFO_FONTCOLOR', '') }};
    background-color: {{ config('global.THEMEAPPEARANCEDARK_INFO_BACKGROUNDCOLOR', '') }};
    border-color: {{ config('global.THEMEAPPEARANCEDARK_INFO_BACKGROUNDCOLOR', '') }};
}

body.darkTheme .btn-light,body.darkTheme .light-btn {
    color: {{ config('global.THEMEAPPEARANCEDARK_LIGHT_FONTCOLOR', '') }};
    background-color: {{ config('global.THEMEAPPEARANCEDARK_LIGHT_BACKGROUNDCOLOR', '') }};
    border-color: {{ config('global.THEMEAPPEARANCEDARK_LIGHT_BACKGROUNDCOLOR', '') }};
}

body.darkTheme .btn-dark,body.darkTheme .dark-btn {
    color: {{ config('global.THEMEAPPEARANCEDARK_DARK_FONTCOLOR', '') }};
    background-color: {{ config('global.THEMEAPPEARANCEDARK_DARK_BACKGROUNDCOLOR', '') }};
    border-color: {{ config('global.THEMEAPPEARANCEDARK_DARK_BACKGROUNDCOLOR', '') }};
}
   
 
</style>
@endif


@if(config('global.THEMEAPPEARANCELIGHT_ENABLE') == 'yes')

<style> 
 

body.lightTheme, .lightTheme .header, .lightTheme .sidebar-nav-wrapper, .lightTheme .card-style,.lightTheme .sidebar-nav-wrapper .sidebar-nav ul .nav-item a , .lightTheme span, .lightTheme a, .lightTheme .input-style-1 label, .lightTheme .input-style-2 label, .lightTheme .input-style-3 label,.lightTheme .breadcrumb-wrapper .breadcrumb li a,.lightTheme .header .header-right .profile-info .info h6{
    color: {{ config('global.THEMEAPPEARANCELIGHT_PAGE_FONTCOLOR', '') }};
    background-color: {{ config('global.THEMEAPPEARANCELIGHT_PAGE_BACKGROUND', '') }};
    border-color: {{ config('global.THEMEAPPEARANCELIGHT_PAGE_BACKGROUND', '') }};
}
body.lightTheme .btn-primary,body.lightTheme .primary-btn {
    color: {{ config('global.THEMEAPPEARANCELIGHT_PRIMARY_FONTCOLOR', '') }};
    background-color: {{ config('global.THEMEAPPEARANCELIGHT_PRIMARY_BACKGROUNDCOLOR', '') }};
    border-color: {{ config('global.THEMEAPPEARANCELIGHT_PRIMARY_BACKGROUNDCOLOR', '') }};
}

body.lightTheme .btn-secondary,body.lightTheme .secondary-btn {
    color: {{ config('global.THEMEAPPEARANCELIGHT_SECONDARY_FONTCOLOR', '') }};
    background-color: {{ config('global.THEMEAPPEARANCELIGHT_SECONDARY_BACKGROUNDCOLOR', '') }};
    border-color: {{ config('global.THEMEAPPEARANCELIGHT_SECONDARY_BACKGROUNDCOLOR', '') }};
}

body.lightTheme .btn-success ,body.lightTheme .success-btn {
    color: {{ config('global.THEMEAPPEARANCELIGHT_SUCCESS_FONTCOLOR', '') }};
    background-color: {{ config('global.THEMEAPPEARANCELIGHT_SUCCESS_BACKGROUNDCOLOR', '') }};
    border-color: {{ config('global.THEMEAPPEARANCELIGHT_SUCCESS_BACKGROUNDCOLOR', '') }};
}

body.lightTheme .btn-danger,body.lightTheme .danger-btn {
    color: {{ config('global.THEMEAPPEARANCELIGHT_DANGER_FONTCOLOR', '') }};
    background-color: {{ config('global.THEMEAPPEARANCELIGHT_DANGER_BACKGROUNDCOLOR', '') }};
    border-color: {{ config('global.THEMEAPPEARANCELIGHT_DANGER_BACKGROUNDCOLOR', '') }};
}

body.lightTheme .btn-warning , body.lightTheme .warning-btn {
    color: {{ config('global.THEMEAPPEARANCELIGHT_WARNING_FONTCOLOR', '') }};
    background-color: {{ config('global.THEMEAPPEARANCELIGHT_WARNING_BACKGROUNDCOLOR', '') }};
    border-color: {{ config('global.THEMEAPPEARANCELIGHT_WARNING_BACKGROUNDCOLOR', '') }};
}

body.lightTheme .btn-info,body.lightTheme .info-btn {
    color: {{ config('global.THEMEAPPEARANCELIGHT_INFO_FONTCOLOR', '') }};
    background-color: {{ config('global.THEMEAPPEARANCELIGHT_INFO_BACKGROUNDCOLOR', '') }};
    border-color: {{ config('global.THEMEAPPEARANCELIGHT_INFO_BACKGROUNDCOLOR', '') }};
}

body.lightTheme .btn-light,body.lightTheme .light-btn {
    color: {{ config('global.THEMEAPPEARANCELIGHT_LIGHT_FONTCOLOR', '') }};
    background-color: {{ config('global.THEMEAPPEARANCELIGHT_LIGHT_BACKGROUNDCOLOR', '') }};
    border-color: {{ config('global.THEMEAPPEARANCELIGHT_LIGHT_BACKGROUNDCOLOR', '') }};
}

body.lightTheme .btn-dark,body.lightTheme .dark-btn {
    color: {{ config('global.THEMEAPPEARANCELIGHT_DARK_FONTCOLOR', '') }};
    background-color: {{ config('global.THEMEAPPEARANCELIGHT_DARK_BACKGROUNDCOLOR', '') }};
    border-color: {{ config('global.THEMEAPPEARANCELIGHT_DARK_BACKGROUNDCOLOR', '') }};
}
  
    </style>
    @endif