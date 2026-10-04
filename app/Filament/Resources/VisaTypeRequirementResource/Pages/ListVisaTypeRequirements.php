<?php

namespace App\Filament\Resources\VisaTypeRequirementResource\Pages;

use App\Filament\Resources\VisaTypeRequirementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVisaTypeRequirements extends ListRecords
{
    protected static string $resource = VisaTypeRequirementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
