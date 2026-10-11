<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use App\Models\Application;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApplication extends EditRecord
{
    protected static string $resource = ApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getSubheading(): ?string
    {
        /** @var Application $record */
        $record = $this->getRecord();

        if (! $record->user) {
            return null;
        }

        return "Client: {$record->user->name} ({$record->user->email}) • Phone: ".($record->user->phone ?? 'N/A');
    }
}
