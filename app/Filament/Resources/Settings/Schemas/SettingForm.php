<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\FileUpload;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('site_name')
                    ->required()
                    ->default('Clé d\'Infos'),
                FileUpload::make('logo')
                    ->image()
                    ->directory('settings')
                    ->disk('public'),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->default(null),
                TextInput::make('phone')
                    ->tel()
                    ->default(null),
                TextInput::make('address')
                    ->default(null),
                TextInput::make('facebook')
                    ->url(),
                TextInput::make('instagram')
                    ->url(),
                TextInput::make('youtube')
                    ->url(),
                TextInput::make('twitter')
                    ->url(),
                Textarea::make('about')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('breaking_news')
                    ->default(null),
                Toggle::make('breaking_active')
                    ->required(),
            ]);
    }
}
