<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class MostReadPosts extends BaseWidget
{
    protected static ?string $heading = 'Articles les plus lus';

    protected function getTableQuery(): Builder
    {
        return Post::query()
            ->orderByDesc('views');
    }

    protected function getTableColumns(): array
    {
        return [

            Tables\Columns\TextColumn::make('title')
                ->label('Titre')
                ->limit(40),

            Tables\Columns\TextColumn::make('views')
                ->label('Vues')
                ->sortable(),

            Tables\Columns\TextColumn::make('category.name')
                ->label('Catégorie'),
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