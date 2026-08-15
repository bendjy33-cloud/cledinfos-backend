<?php

namespace App\Filament\Resources\BreakingNews\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BreakingNewsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // =========================
                // FRENCH
                // =========================

                TextInput::make('title_fr')
                    ->label('Titre (Français)')
                    ->required()
                    ->maxLength(255),

                // =========================
                // ENGLISH
                // =========================

                TextInput::make('title_en')
                    ->label('Title (English)')
                    ->maxLength(255),

                // =========================
                // KREYÒL
                // =========================

                TextInput::make('title_ht')
                    ->label('Tit (Kreyòl)')
                    ->maxLength(255),

                // =========================
                // GENERAL
                // =========================

                TextInput::make('link')
                    ->label('Lien')
                    ->url()
                    ->nullable(),

                Toggle::make('active')
                    ->label('Actif')
                    ->default(true),

                DateTimePicker::make('starts_at')
                    ->label('Début'),

                DateTimePicker::make('ends_at')
                    ->label('Fin'),

            ]);
    }
}