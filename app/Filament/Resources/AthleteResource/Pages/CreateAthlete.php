<?php

namespace App\Filament\Resources\AthleteResource\Pages;

use App\Filament\Resources\AthleteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAthlete extends CreateRecord
{
    protected static string $resource = AthleteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Pages\Actions\Action::make('Retour à l\'équipe')
                ->url(function () {
                    $teamId = $this->record->team_id ?? request()->query('team_id');
                    return $teamId
                        ? route('filament.admin.resources.teams.edit', ['record' => $teamId])
                        : route('filament.admin.resources.athletes.index');
                })
                ->color('gray')
                ->icon('heroicon-o-arrow-left'),
        ];
    }

    public function getRedirectUrl(): string
    {
        $teamId = request()->query('team_id');

        return $teamId
            ? route('filament.admin.resources.teams.edit', ['record' => $teamId])
            : route('filament.admin.resources.athletes.index');
    }

}
