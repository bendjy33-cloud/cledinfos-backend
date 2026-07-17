<?php

namespace App\Filament\Resources\Contacts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ContactForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->disabled(),

                TextInput::make('email')
                    ->email()
                    ->disabled(),

                TextInput::make('subject')
                    ->disabled(),

                Textarea::make('message')
                    ->rows(8)
                    ->disabled()
                    ->columnSpanFull(),

                Toggle::make('read')
                    ->label('Message lu'),
            ]);
    }
}