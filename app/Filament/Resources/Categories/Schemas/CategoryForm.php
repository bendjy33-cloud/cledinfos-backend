<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // =========================
                // FRENCH
                // =========================

                TextInput::make('name_fr')
                    ->label('Nom (Français)')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(
                        fn ($state, callable $set) =>
                        $set('slug', Str::slug($state))
                    ),

                Textarea::make('description_fr')
                    ->label('Description (Français)')
                    ->rows(4)
                    ->columnSpanFull(),

                // =========================
                // ENGLISH
                // =========================

                TextInput::make('name_en')
                    ->label('Name (English)')
                    ->maxLength(255),

                Textarea::make('description_en')
                    ->label('Description (English)')
                    ->rows(4)
                    ->columnSpanFull(),

                // =========================
                // KREYÒL
                // =========================

                TextInput::make('name_ht')
                    ->label('Non (Kreyòl)')
                    ->maxLength(255),

                Textarea::make('description_ht')
                    ->label('Deskripsyon (Kreyòl)')
                    ->rows(4)
                    ->columnSpanFull(),

                // =========================
                // GENERAL
                // =========================

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),

            ]);
    }
}