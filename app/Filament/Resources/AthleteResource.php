<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AthleteResource\Pages;
use App\Filament\Resources\AthleteResource\RelationManagers;
use App\Models\Athlete;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AthleteResource extends Resource
{
    protected static ?string $model = Athlete::class;

    protected static ?string $navigationLabel = 'Athlètes';
    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('team_id')
                    ->label('Équipe')
                    ->relationship('team', 'name')
                    ->searchable()
                    ->default(request()->query('team_id'))
                    ->disabled()
                    ->dehydrated(), // 👈 ajoute cette ligne !

                Forms\Components\FileUpload::make('photo')
                    ->image()
                    ->disk('public')
                    ->directory('photos')
                    ->label('Photo de l’athlète')
                    ->visibility('public'),


                TextInput::make('last_name')->label('Nom')->required(),
                TextInput::make('middle_name')->label('Postnom')->required(),
                TextInput::make('first_name')->label('Prénom')->required(),
                DatePicker::make('birth_date')->label('Date de naissance')->required(),

                Select::make('gender')->label('Genre')->options([
                    'Masculin' => 'Masculin',
                    'Féminin' => 'Féminin',
                ])->required(),

                TextInput::make('matricule')->label('Matricule')->required(),
                TextInput::make('position')->label('Poste préféré'),
                TextInput::make('jersey_number')->label('Numéro de maillot'),
                Toggle::make('is_active')->label('Actif'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(array(
                ImageColumn::make('photo')->label('Photo'),
                TextColumn::make('matricule')->label('Matricule'),
                TextColumn::make('last_name')->label('Nom'),
                TextColumn::make('middle_name')->label('Postnom'),
                TextColumn::make('first_name')->label('Prénom'),
                TextColumn::make('team.name')->label('Équipe'),
                TextColumn::make('position')->label('Poste'),
                IconColumn::make('is_active')->boolean()->label('Actif'),
            ))
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListAthletes::route('/'),
            'create' => Pages\CreateAthlete::route('/create'),
            'edit' => Pages\EditAthlete::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
