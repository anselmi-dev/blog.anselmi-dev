<?php

namespace App\Filament\Admin\Resources\PhotoResource\Pages;

use App\Filament\Admin\Resources\PhotoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePhoto extends CreateRecord
{
    protected static string $resource = PhotoResource::class;
}
