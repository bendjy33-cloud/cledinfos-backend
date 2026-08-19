<?php

namespace App\Filament\Resources\BreakingNews\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class BreakingNewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('title_fr')
                    ->label('Français')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('title_en')
                    ->label('English')
                    ->searchable(),

                TextColumn::make('title_ht')
                    ->label('Kreyòl')
                    ->searchable(),

                TextColumn::make('title_es')
                    ->label('Español')
                    ->searchable(),

                TextColumn::make('link')
                    ->label('Lien')
                    ->limit(40)
                    ->copyable(),

                IconColumn::make('active')
                    ->label('Actif')
                    ->boolean(),

                TextColumn::make('starts_at')
                    ->label('Début')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('ends_at')
                    ->label('Fin')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->since(),

            ])

            ->filters([

                TernaryFilter::make('active')
                    ->label('Actif'),

            ])

            ->recordActions([
                EditAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}