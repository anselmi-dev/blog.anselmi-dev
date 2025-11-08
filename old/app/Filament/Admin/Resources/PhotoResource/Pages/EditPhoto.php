<?php

namespace App\Filament\Admin\Resources\PhotoResource\Pages;

use App\Filament\Admin\Resources\PhotoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPhoto extends EditRecord
{
    protected static string $resource = PhotoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
