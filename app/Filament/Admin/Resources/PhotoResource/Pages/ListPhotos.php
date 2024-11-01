<?php

namespace App\Filament\Admin\Resources\PhotoResource\Pages;

use App\Filament\Admin\Resources\PhotoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPhotos extends ListRecords
{
    protected static string $resource = PhotoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
