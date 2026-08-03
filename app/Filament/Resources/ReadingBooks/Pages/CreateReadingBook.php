<?php

namespace App\Filament\Resources\ReadingBooks\Pages;

use App\Filament\Resources\ReadingBooks\ReadingBookResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReadingBook extends CreateRecord
{
    protected static string $resource = ReadingBookResource::class;
}
