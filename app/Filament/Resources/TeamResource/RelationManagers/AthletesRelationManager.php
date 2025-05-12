<?php

namespace App\Filament\Resources\TeamResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AthletesRelationManager extends RelationManager
{
    protected static string $relationship = 'athletes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('last_name')->label('Nom')->required(),
                TextInput::make('middle_name')->label('Postnom')->required(),
                TextInput::make('first_name')->label('Prénom')->required(),
                DatePicker::make('birth_date')->label('Date de naissance')->required(),
                Select::make('gender')->label('Genre')->options([
                    'Masculin' => 'Masculin',
                    'Féminin' => 'Féminin',
                ])->required(),
                TextInput::make('matricule')->label('Matricule')->required(),
                FileUpload::make('photo')->label('Photo')->image()->directory('athlete-photos')->preserveFilenames(),
                TextInput::make('position')->label('Poste préféré'),
                TextInput::make('jersey_number')->label('Numéro de maillot'),
                Toggle::make('is_active')->label('Actif'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('matricule')
            ->columns([
                TextColumn::make('last_name')->label('Nom'),
                TextColumn::make('middle_name')->label('Postnom'),
                TextColumn::make('first_name')->label('Prénom'),

                TextColumn::make('matricule')->label('Matricule'),
                TextColumn::make('position')->label('Poste'),
                IconColumn::make('is_active')->label('Actif')->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Ajouter un athlète')
                    ->modalHeading('Ajouter un nouvel athlète')
                    ->modalSubmitActionLabel('Créer'),
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
}
