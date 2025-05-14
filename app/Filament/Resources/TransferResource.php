<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransferResource\Pages;
use App\Filament\Resources\TransferResource\RelationManagers;
use App\Models\Transfer;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransferResource extends Resource
{
    protected static ?string $model = Transfer::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left'; // icône de transfert
    protected static ?string $navigationLabel = 'Transferts';
    protected static ?string $modelLabel = 'Transfert';
    protected static ?string $pluralModelLabel = 'Transferts';
    protected static ?string $navigationGroup = 'Gestion des athlètes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('athlete_id')
                    ->label('Athlète')
                    ->relationship(
                        'athlete',
                        'matricule',
                        fn (Builder $query) => $query->with('team') // ✅ ici on rend $query lisible
                    )
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->last_name} {$record->first_name} ({$record->matricule})")
                    ->searchable()
                    ->required(),

                Select::make('from_team_id')
                    ->label('Équipe actuelle (source)')
                    ->relationship('fromTeam', 'name')
                    ->required(),

                Select::make('to_team_id')
                    ->label('Équipe de destination')
                    ->relationship('toTeam', 'name')
                    ->required()
                    ->different('from_team_id'),

                DatePicker::make('transfer_date')
                    ->label('Date du transfert')
                    ->default(now())
                    ->required(),

                Select::make('type')
                    ->label('Type de transfert')
                    ->options([
                        'prêt' => 'Prêt',
                        'définitif' => 'Définitif',
                    ])
                    ->required(),

                Select::make('status')
                    ->label('Statut')
                    ->disabled()
                    ->default('en_attente'),

                Textarea::make('notes')
                    ->label('Remarques')
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('athlete.matricule')
                    ->label('Matricule')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('athlete.last_name')
                    ->label('Nom')
                    ->searchable(),

                TextColumn::make('fromTeam.name')
                    ->label('Équipe de départ'),

                TextColumn::make('toTeam.name')
                    ->label('Équipe cible'),

                TextColumn::make('type')
                    ->label('Type'),

                TextColumn::make('transfer_date')
                    ->label('Date')
                    ->date(),

                TextColumn::make('status')
                    ->label('Statut')
                    ->formatStateUsing(function (string $state): string {
                        return match ($state) {
                            'en_attente' => '🟠 En attente',
                            'accepté_par_recepteur' => '🔵 Accepté par équipe',
                            'validé' => '✅ Validé',
                            'refusé' => '❌ Refusé',
                            default => ucfirst($state),
                        };
                    })
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Filtrer par statut')
                    ->options([
                        'en_attente' => 'En attente',
                        'accepté_par_recepteur' => 'Accepté par l\'équipe cible',
                        'validé' => 'Validé',
                        'refusé' => 'Refusé',
                    ])
                    ->native()
                    ->placeholder('Tous'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('accepter')
                    ->label('Accepter')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn ($record): bool =>
                    (auth()->user()->hasRole('Équipe')
                            || auth()->user()->hasRole('Administrateur'))
//                        && $record->to_team_id === auth()->user()->team?->id
                        && $record->status === 'en_attente'
                    )
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'accepté_par_recepteur',
                            'confirmation_by_destination' => true,
                        ]);
                    })->after(function () {
                        return redirect(request()->header('Referer') ?? url()->current());
                    })
                ,

                Tables\Actions\Action::make('refuser')
                    ->label('Refuser')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record): bool =>
                        (auth()->user()->hasRole('Équipe')
                            || auth()->user()->hasRole('Administrateur'))
//                        && $record->to_team_id === auth()->user()->team?->id
                        && $record->status === 'en_attente'
                    )
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'refusé',
                            'confirmation_by_destination' => false,
                        ]);
                    })->after(function () {
                        return redirect(request()->header('Referer') ?? url()->current());
                    })
                ,

                Tables\Actions\Action::make('valider')
                    ->label('Valider')
                    ->icon('heroicon-o-shield-check')
                    ->color('success')
                    ->visible(fn ($record): bool =>
                    (auth()->user()->hasRole('Fédération')
                        || auth()->user()->hasRole('Administrateur'))
                        && $record->status === 'accepté_par_recepteur'
                    )
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'validé',
                            'confirmation_by_federation' => true,
                        ]);

                        // Mise à jour du joueur
                        $record->athlete->update([
                            'team_id' => $record->to_team_id,
                        ]);
                    })->after(function () {
                        return redirect(request()->header('Referer') ?? url()->current());
                    }),

                Tables\Actions\Action::make('rejeter')
                    ->label('Rejeter')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record): bool =>
                        (auth()->user()->hasRole('Fédération')
                            || auth()->user()->hasRole('Administrateur'))
                        && $record->status === 'accepté_par_recepteur'
                    )
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'refusé',
                            'confirmation_by_federation' => false,
                        ]);
                    })->after(function () {
                        return redirect(request()->header('Referer') ?? url()->current());
                    })

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function getTitle(): string
    {
        return match (auth()->user()->getRoleNames()->first()) {
            'Fédération' => 'Transferts à valider',
            'Équipe' => 'Transferts en cours',
            default => 'Tous les transferts',
        };
    }


    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();

        $user = auth()->user();

        if ($user->hasRole('Fédération')) {
            // La fédération voit les transferts à valider
            return $query->where('status', 'accepté_par_recepteur');
        }

        if ($user->hasRole('Équipe')) {
            // L'équipe voit les transferts qu'elle envoie ou reçoit
            $teamId = $user->team?->id;

            return $query->where(function ($q) use ($teamId) {
                $q->where('from_team_id', $teamId)
                    ->orWhere('to_team_id', $teamId);
            });
        }

        // Les autres rôles (ex : admin) voient tout
        return $query;
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransfers::route('/'),
            'create' => Pages\CreateTransfer::route('/create'),
            'edit' => Pages\EditTransfer::route('/{record}/edit'),
        ];
    }
}
