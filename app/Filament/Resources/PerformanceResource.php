<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PerformanceResource\Pages;
use App\Filament\Resources\PerformanceResource\RelationManagers;
use App\Models\Performance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PerformanceResource extends Resource
{
    protected static ?string $model = Performance::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Performances';
    protected static ?string $pluralModelLabel = 'Performances';
    protected static ?string $modelLabel = 'Performance';
    protected static ?string $navigationGroup = 'Gestion des athlètes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('athlete_id')
                    ->label('Athlète')
                    ->relationship('athlete', 'matricule')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->last_name} {$record->first_name} ({$record->matricule})")
                    ->searchable()
                    ->required(),

                Forms\Components\DatePicker::make('date')
                    ->label('Date de la performance')
                    ->required(),

                Forms\Components\TextInput::make('event')
                    ->label('Événement')
                    ->required(),

                Forms\Components\TextInput::make('position')
                    ->label('Position')
                    ->maxLength(100),

                Forms\Components\TextInput::make('score')
                    ->label('Score / Note')
                    ->numeric(),

                Forms\Components\Textarea::make('observation')
                    ->label('Observation')
                    ->rows(3),

                Forms\Components\Select::make('recorded_by')
                    ->label('Enregistré par')
                    ->relationship('recorder', 'name')
                    ->default(auth()->id())
                    ->disabledOn('edit')
                    ->hiddenOn('create'), // sera assigné automatiquement en back
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('athlete.matricule')->label('Matricule'),
                TextColumn::make('athlete.last_name')->label('Nom'),
                TextColumn::make('event')->label('Événement'),
                TextColumn::make('date')->label('Date')->date(),
                TextColumn::make('score')->label('Score')->sortable(),
                TextColumn::make('recorder.name')->label('Encadreur'),
            ])
            ->filters([
                SelectFilter::make('athlete_id')
                    ->label('Filtrer par athlète')
                    ->relationship('athlete', 'matricule'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListPerformances::route('/'),
            'create' => Pages\CreatePerformance::route('/create'),
            'edit' => Pages\EditPerformance::route('/{record}/edit'),
        ];
    }
}
