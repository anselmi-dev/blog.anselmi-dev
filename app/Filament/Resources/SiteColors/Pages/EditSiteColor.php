<?php

namespace App\Filament\Resources\SiteColors\Pages;

use App\Filament\Resources\SiteColors\SiteColorResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSiteColor extends EditRecord
{
    protected static string $resource = SiteColorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
