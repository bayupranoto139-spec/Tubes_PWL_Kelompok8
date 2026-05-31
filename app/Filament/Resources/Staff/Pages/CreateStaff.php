<?php

namespace App\Filament\Resources\Staff\Pages;

use App\Filament\Resources\Staff\StaffResource;

use Filament\Resources\Pages\CreateRecord;

class CreateStaff extends CreateRecord
{
    protected static string $resource = StaffResource::class;

    /*
    |--------------------------------------------------------------------------
    | REMOVE "CREATE & CREATE ANOTHER"
    |--------------------------------------------------------------------------
    */

    public function canCreateAnother(): bool
    {
        return false;
    }
}