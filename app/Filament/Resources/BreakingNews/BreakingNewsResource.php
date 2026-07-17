<?php

namespace App\Filament\Resources\BreakingNews;

use App\Filament\Resources\BreakingNews\Pages\CreateBreakingNews;
use App\Filament\Resources\BreakingNews\Pages\EditBreakingNews;
use App\Filament\Resources\BreakingNews\Pages\ListBreakingNews;
use App\Filament\Resources\BreakingNews\Schemas\BreakingNewsForm;
use App\Filament\Resources\BreakingNews\Tables\BreakingNewsTable;
use App\Models\BreakingNews;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BreakingNewsResource extends Resource
{
    protected static ?string $model = BreakingNews::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return BreakingNewsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BreakingNewsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBreakingNews::route('/'),
            'create' => CreateBreakingNews::route('/create'),
            'edit' => EditBreakingNews::route('/{record}/edit'),
        ];
    }
}
