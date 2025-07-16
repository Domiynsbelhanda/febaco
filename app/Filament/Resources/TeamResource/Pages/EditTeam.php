<?php

namespace App\Filament\Resources\TeamResource\Pages;

use App\Filament\Resources\TeamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;

class EditTeam extends EditRecord
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('transferts')
                ->label('Voir les transferts')
                ->icon('heroicon-o-arrows-right-left')
                ->url(fn () => static::getResource()::getUrl('view-team-transfers', ['record' => $this->record]))
                ->color('info'),

            Actions\DeleteAction::make(),
        ];
    }
}
