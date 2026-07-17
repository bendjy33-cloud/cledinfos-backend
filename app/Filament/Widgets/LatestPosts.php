<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestPosts extends BaseWidget
{
    protected static ?string $heading = 'Derniers articles';

    protected function getTableQuery(): Builder
    {
        return Post::query()->latest('published_at');
    }

    protected function getTableColumns(): array
    {
        return [

            Tables\Columns\TextColumn::make('title')
                ->label('Titre')
                ->searchable()
                ->limit(40),

            Tables\Columns\TextColumn::make('category.name')
                ->label('Catégorie'),

            Tables\Columns\IconColumn::make('is_published')
                ->label('Publié')
                ->boolean(),

            Tables\Columns\TextColumn::make('views')
                ->label('Vues'),

            Tables\Columns\TextColumn::make('published_at')
                ->label('Date')
                ->dateTime('d/m/Y'),
        ];
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }

    public function getTableRecordsPerPage(): int
    {
        return 5;
    }
}