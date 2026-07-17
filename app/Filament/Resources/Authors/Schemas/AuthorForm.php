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
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(
                        fn ($state, callable $set) =>
                        $set('slug', Str::slug($state))
                    ),

                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),

                FileUpload::make('photo')
                    ->label('Photo')
                    ->image()
                    ->disk('public')
                    ->directory('authors')
                    ->imageEditor(),

                TextInput::make('job_title')
                    ->label('Fonction')
                    ->maxLength(255),

                Textarea::make('bio')
                    ->rows(5)
                    ->columnSpanFull(),

                TextInput::make('facebook')
                    ->url()
                    ->maxLength(255),

                TextInput::make('twitter')
                    ->label('X / Twitter')
                    ->url()
                    ->maxLength(255),

                TextInput::make('linkedin')
                    ->url()
                    ->maxLength(255),

                Toggle::make('is_active')
                    ->label('Actif')
                    ->default(true),

            ]);
    }
}