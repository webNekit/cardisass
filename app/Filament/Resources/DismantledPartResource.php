<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DismantledPartResource\Pages;
use App\Filament\Resources\DismantledPartResource\RelationManagers;
use App\Models\DismantledCar;
use App\Models\DismantledPart;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DismantledPartResource extends Resource
{
    protected static ?string $model = DismantledPart::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationLabel = 'Запчасти';
    protected static ?string $pluralLabel = 'Запчасти';
    protected static ?string $navigationGroup = 'Управление';
    protected static ?string $modelLabel = "";
    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['dismantledCar.carBrand']);
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('car_display')
                    ->label('Автомобиль')
                    ->disabled()
                    ->dehydrated(false)
                    ->formatStateUsing(fn($record) => $record && $record->dismantledCar
                        ? ($record->dismantledCar->carBrand->name . ' ' . $record->dismantledCar->model)
                        : 'Не указано'),


                TextInput::make('name')
                    ->label('Название запчасти')
                    ->required(),

                TextInput::make('price')
                    ->label('Цена')
                    ->numeric(),

                Select::make('quality')
                    ->label('Качество')
                    ->options([
                        'S' => 'Хорошее',
                        'E' => 'Мелкие царапины',
                        'D' => 'Повреждения',
                        'C' => 'Плохое',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Запчасть'),
                TextColumn::make('price')->label('Цена')->money('RUB'),
                TextColumn::make('dismantledCar.carBrand.name')
                    ->label('Марка'),
                TextColumn::make('dismantledCar.model')
                    ->label('Модель'),
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
            'index' => Pages\ListDismantledParts::route('/'),
            'create' => Pages\CreateDismantledPart::route('/create'),
            'edit' => Pages\EditDismantledPart::route('/{record}/edit'),
        ];
    }
}
