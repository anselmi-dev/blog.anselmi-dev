<?php

namespace App\Filament\Resources\ReadingBooks\Pages;

use App\Filament\Resources\ReadingBooks\ReadingBookResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditReadingBook extends EditRecord
{
    protected static string $resource = ReadingBookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
