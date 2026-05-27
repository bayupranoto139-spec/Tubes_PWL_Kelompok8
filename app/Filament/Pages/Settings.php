<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use BackedEnum;

class Settings extends Page
{
    // ICON SIDEBAR
    protected static string | BackedEnum | null $navigationIcon =
        'heroicon-o-cog-6-tooth';

    // BLADE VIEW
    protected string $view = 'filament.pages.settings';

    // MENU SIDEBAR
    protected static ?string $navigationLabel = 'Settings';

    // TITLE PAGE
    protected static ?string $title = 'System Settings';

    // URUTAN MENU
    protected static ?int $navigationSort = 99;
}