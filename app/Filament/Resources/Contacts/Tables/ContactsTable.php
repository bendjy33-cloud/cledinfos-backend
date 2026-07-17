<?php

namespace App\Filament\Resources\Contacts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;


class ContactsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')

            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable()
                    ->copyable()
                    ->url(fn ($record) => "mailto:{$record->email}"),

                TextColumn::make('subject')
                    ->searchable(),

                TextColumn::make('message')
                    ->label('Message')
                    ->limit(60)
                    ->tooltip(fn ($record) => $record->message),

                IconColumn::make('read')
                    ->label('Lu')
                    ->boolean()
                    ->alignCenter(),

                TextColumn::make('created_at')
                    ->label('Reçu le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])

            ->filters([

            ])

            ->recordActions([
                Action::make('markAsRead')
                    ->label('Marquer lu')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn ($record) => ! $record->read)
                    ->action(fn ($record) => $record->update([
                        'read' => true,
                    ])),

                    EditAction::make(),

                    DeleteAction::make(),
                ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}