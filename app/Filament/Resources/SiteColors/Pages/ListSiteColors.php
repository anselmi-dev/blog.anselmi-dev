<?php

namespace App\Filament\Resources\SiteColors\Pages;

use App\Filament\Resources\SiteColors\SiteColorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSiteColors extends ListRecords
{
    protected static string $resource = SiteColorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
