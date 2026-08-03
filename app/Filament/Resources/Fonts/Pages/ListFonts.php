<?php

namespace App\Filament\Resources\Fonts\Pages;

use App\Filament\Resources\Fonts\FontResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFonts extends ListRecords
{
    protected static string $resource = FontResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
