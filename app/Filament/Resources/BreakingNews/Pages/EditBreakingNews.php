<?php

namespace App\Filament\Resources\BreakingNews\Pages;

use App\Filament\Resources\BreakingNews\BreakingNewsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBreakingNews extends EditRecord
{
    protected static string $resource = BreakingNewsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
