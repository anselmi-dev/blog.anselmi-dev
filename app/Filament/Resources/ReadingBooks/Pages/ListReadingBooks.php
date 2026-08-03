<?php

namespace App\Filament\Resources\ReadingBooks\Pages;

use App\Filament\Resources\ReadingBooks\ReadingBookResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReadingBooks extends ListRecords
{
    protected static string $resource = ReadingBookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
