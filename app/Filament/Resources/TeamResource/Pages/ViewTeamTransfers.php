<?php

namespace App\Filament\Resources\TeamResource\Pages;

use App\Models\Transfer;
use App\Filament\Resources\TeamResource;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class ViewTeamTransfers extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string $resource = TeamResource::class;

    protected static string $view = 'filament.resources.team-resource.pages.view-team-transfers';

    public $record;

    public function mount($record): void
    {
        $this->record = $record;
    }

    protected function getTableQuery()
    {
        return Transfer::query()
            ->where('from_team_id', $this->record)
            ->orWhere('to_team_id', $this->record)
            ->with(['athlete', 'fromTeam', 'toTeam']);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('athlete.matricule')->label('Matricule'),
            TextColumn::make('athlete.last_name')->label('Nom'),
            TextColumn::make('fromTeam.name')->label('Équipe source'),
            TextColumn::make('toTeam.name')->label('Équipe cible'),
            TextColumn::make('type')->label('Type'),
            TextColumn::make('transfer_date')->label('Date')->date(),
            TextColumn::make('status')->label('Statut'),
        ];
    }
}
