<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamResource\Pages;
use App\Filament\Resources\TeamResource\RelationManagers\AthletesRelationManager;
use App\Models\Entity;
use App\Models\Team;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('entity_id')
                    ->label('Ligue, Entente ou Cercle')
                    ->options(Entity::pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Select::make('user_id')
                    ->label('Responsable (Utilisateur)')
                    ->options(User::pluck('name', 'id')->toArray())
                    ->searchable()
                    ->nullable(),

                TextInput::make('name')
                    ->label('Nom du club')
                    ->required(),

                TextInput::make('matricule')
                    ->label('Matricule'),

                TextInput::make('province')
                    ->label('Province'),

                TextInput::make('categorie')
                    ->label('Catégorie'),

                TextInput::make('ville')
                    ->label('Ville'),

                TextInput::make('division')
                    ->label('Division'),

                TextInput::make('version')
                    ->label('Version'),

                TextInput::make('casier_no')
                    ->label('Casier N°'),

                TextInput::make('bp')
                    ->label('B.P'),

                TextInput::make('couleurs')
                    ->label('Couleurs'),

                Textarea::make('description')
                    ->label('Description'),

                TextInput::make('responsible_name')
                    ->label('Correspondant officiel'),

                TextInput::make('contact_email')
                    ->label('Email'),

                TextInput::make('contact_phone')
                    ->label('Téléphone'),

                TextInput::make('address')
                    ->label('Siège'),

                FileUpload::make('logo')
                    ->label('Logo')
                    ->image()
                    ->directory('team-logos')
                    ->preserveFilenames(),

                Toggle::make('is_active')
                    ->label('Actif'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')->label('Logo')->circular(),
                TextColumn::make('name')->label('Nom du club')->sortable()->searchable(),
                TextColumn::make('matricule')->label('Matricule')->sortable()->searchable(),
                TextColumn::make('province')->label('Province')->sortable(),
                TextColumn::make('categorie')->label('Catégorie')->sortable(),
                TextColumn::make('division')->label('Division'),
                TextColumn::make('entity.name')->label('Entité'),
                TextColumn::make('responsible_name')->label('Correspondant officiel'),
                IconColumn::make('is_active')->boolean()->label('Actif'),
            ])
            ->filters([
                //
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
            AthletesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasRole('Administrateur') || auth()->user()?->hasRole('Équipe');
    }

}
