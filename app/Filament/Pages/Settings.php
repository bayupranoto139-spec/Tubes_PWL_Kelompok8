<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;

class Settings extends Page
{
    /*
    |--------------------------------------------------------------------------
    | NAVIGATION
    |--------------------------------------------------------------------------
    */

    protected static string|BackedEnum|null $navigationIcon =
        'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel =
        'Settings';

    protected static ?string $title =
        'System Settings';

    protected static ?int $navigationSort = 10;

    /*
    |--------------------------------------------------------------------------
    | ACCESS CONTROL
    |--------------------------------------------------------------------------
    */

    public static function shouldRegisterNavigation(): bool
    {
        return filament()->auth()->user()?->role === 'super_admin';
    }

    public static function canAccess(): bool
    {
        return filament()->auth()->user()?->role === 'super_admin';
    }

    /*
    |--------------------------------------------------------------------------
    | VIEW
    |--------------------------------------------------------------------------
    */

    protected string $view =
        'filament.pages.settings';
}