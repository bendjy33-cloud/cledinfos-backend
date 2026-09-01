<?php

namespace App\Filament\Resources\Authors\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class AuthorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('name')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(
                        fn ($state, callable $set) =>
                        $set('slug', Str::slug($state))
                    ),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(ignoreRecord: true),

                FileUpload::make('photo')
                    ->label('Photo')
                    ->image()
                    ->disk('public')
                    ->directory('authors')
                    ->visibility('public')
                    ->imageEditor(),

                TextInput::make('job_title')
                    ->label('Fonction')
                    ->maxLength(255),

                Textarea::make('bio_fr')
                    ->label('Biographie — Français')
                    ->rows(5)
                    ->columnSpanFull(),

                Textarea::make('bio_en')
                    ->label('Biography — English')
                    ->rows(5)
                    ->columnSpanFull(),

                Textarea::make('bio_ht')
                    ->label('Biografi — Kreyòl')
                    ->rows(5)
                    ->columnSpanFull(),

                Textarea::make('bio_es')
                    ->label('Biografía — Español')
                    ->rows(5)
                    ->columnSpanFull(),

                TextInput::make('facebook')
                    ->label('Facebook')
                    ->url()
                    ->maxLength(255),

                TextInput::make('twitter')
                    ->label('X / Twitter')
                    ->url()
                    ->maxLength(255),

                TextInput::make('linkedin')
                    ->label('LinkedIn')
                    ->url()
                    ->maxLength(255),

                Toggle::make('is_active')
                    ->label('Actif')
                    ->default(true),

            ]);
    }
}