<?php

namespace App\Filament\Resources\BreakingNews\Pages;

use App\Filament\Resources\BreakingNews\BreakingNewsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBreakingNews extends ListRecords
{
    protected static string $resource = BreakingNewsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
