<?php

namespace App\Filament\Resources\DismantledCarResource\RelationManagers;

use App\Models\DismantledPart;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DismantledPartsRelationManager extends RelationManager
{
    protected static string $relationship = 'dismantledParts';

    public function onCreate() {
        return false;
    }

    protected static ?string $title = 'Запчасти';
    protected static ?string $label = 'Запчасть';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Запчасть'),
                Tables\Columns\TextColumn::make('price')->label('Цена')->money('RUB'),
                Tables\Columns\TextColumn::make('dismantledCar.carBrand.name')
                    ->label('Марка'),
                Tables\Columns\TextColumn::make('dismantledCar.model')
                    ->label('Модель'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
