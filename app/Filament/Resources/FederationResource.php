<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FederationResource\Pages;
use App\Filament\Resources\FederationResource\RelationManagers;
use App\Models\Federation;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FederationResource extends Resource
{
    protected static ?string $model = Federation::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Fédération';
    protected static ?string $pluralModelLabel = 'Fédération';
    protected static ?string $modelLabel = 'Fédération';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nom de la Fédération')
                    ->required(),

                Textarea::make('description')
                    ->label('Description'),

                FileUpload::make('logo')
                    ->label('Logo')
                    ->image()
                    ->directory('federation-logos')
                    ->preserveFilenames(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')->label('Logo'),
                TextColumn::make('name')->label('Nom')->searchable(),
                TextColumn::make('description')->label('Description')->limit(50),
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
            'index' => Pages\ListFederations::route('/'),
            'create' => Pages\CreateFederation::route('/create'),
            'edit' => Pages\EditFederation::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasRole('Administrateur') || auth()->user()?->hasRole('Fédération');
    }
}
