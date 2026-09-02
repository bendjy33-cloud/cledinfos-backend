<?php

namespace App\Filament\Resources\Ads\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AdForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('title')
                    ->label('Titre')
                    ->required()
                    ->maxLength(255),

                Select::make('position')
                    ->label('Position')
                    ->options([
                        'header' => 'Header',
                        'sidebar' => 'Sidebar',
                        'article' => 'Article',
                        'footer' => 'Footer',
                    ])
                    ->required(),

                FileUpload::make('image')
                    ->label('Image publicitaire')
                    ->image()
                    ->directory('ads')
                    ->disk('public')
                    ->nullable()
                    ->maxSize(10240),

                FileUpload::make('video')
                    ->label('Vidéo publicitaire')
                    ->acceptedFileTypes([
                        'video/mp4',
                        'video/webm',
                        'video/ogg',
                        'video/quicktime',
                    ])
                    ->directory('ads/videos')
                    ->disk('public')
                    ->nullable()
                    ->maxSize(102400),

                TextInput::make('url')
                    ->label('Lien')
                    ->url()
                    ->nullable(),

                Toggle::make('active')
                    ->label('Actif')
                    ->default(true),

                DateTimePicker::make('starts_at')
                    ->label('Début')
                    ->nullable(),

                DateTimePicker::make('ends_at')
                    ->label('Fin')
                    ->nullable(),

            ]);
    }
}