<?php

namespace App\Filament\Resources\Specializations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SpecializationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // NAMA SPECIALIZATION
                TextInput::make('name')
                    ->label('Specialization Name')
                    ->required()
                    ->maxLength(255),

                // DESCRIPTION
                Textarea::make('description')
                    ->label('Description')
                    ->rows(4)
                    ->required(),

            ]);
    }
}