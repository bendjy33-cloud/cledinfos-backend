<?php

namespace App\Filament\Resources\Tags\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class TagForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('name')
                    ->label('Nom')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set) {

                        $set(
                            'slug',
                            Str::slug($state)
                        );

                    }),

                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),

                Toggle::make('is_active')
                    ->label('Actif')
                    ->default(true),

            ]);
    }
}