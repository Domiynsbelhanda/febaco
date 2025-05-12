<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EntityResource\Pages;
use App\Filament\Resources\EntityResource\RelationManagers;
use App\Models\Entity;
use App\Models\Federation;
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

class EntityResource extends Resource
{
    protected static ?string $model = Entity::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationLabel = 'Entités';
    protected static ?string $pluralModelLabel = 'Entités';
    protected static ?string $modelLabel = 'Entité';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('federation_id')
                    ->label('Fédération')
                    ->options(Federation::pluck('name', 'id'))
                    ->required(),
                TextInput::make('name')->label('Nom')->required(),
                Textarea::make('description')->label('Description'),
                TextInput::make('region')->label('Région/Province')->required(),
                TextInput::make('responsible_name')->label('Nom du Responsable'),
                TextInput::make('contact_email')->label('Email'),
                TextInput::make('contact_phone')->label('Téléphone'),
                TextInput::make('address')->label('Adresse'),
                FileUpload::make('logo')->label('Logo')->image()->directory('entity-logos')->preserveFilenames(),
                Toggle::make('is_active')->label('Actif'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')->label('Logo'),
                TextColumn::make('federation.name')->label('Fédération'),
                TextColumn::make('name')->label('Nom')->searchable(),
                TextColumn::make('region')->label('Région'),
                TextColumn::make('responsible_name')->label('Responsable'),
                TextColumn::make('contact_email')->label('Email'),
                TextColumn::make('contact_phone')->label('Téléphone'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEntities::route('/'),
            'create' => Pages\CreateEntity::route('/create'),
            'edit' => Pages\EditEntity::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasRole('Administrateur') || auth()->user()?->hasRole('Entité');
    }
}
