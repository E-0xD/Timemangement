<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Branding
    |--------------------------------------------------------------------------
    |
    | Centralised identity for the application. Reference these values in
    | views and controllers via config('site.name'), config('site.logo'), etc.
    | Update APP_NAME and related values in your .env to change them globally.
    |
    */

    'name' => env('APP_NAME', 'StudyFlow'),

    'tagline' => env('APP_TAGLINE', 'Master Your Academic Journey'),

    'description' => 'A student time management system to help you organise schedules, manage assignments, and track academic progress.',

    'logo' => env('APP_LOGO', null),

    'logo_icon' => env('APP_LOGO_ICON', null),

    'support_email' => env('APP_SUPPORT_EMAIL', 'support@studyflow.app'),

    'url' => env('APP_URL', 'http://localhost'),

];
