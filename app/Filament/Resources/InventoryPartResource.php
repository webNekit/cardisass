<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryPartResource\Pages;
use App\Models\InventoryPart;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;

class InventoryPartResource extends Resource
{
    protected static ?string $model = InventoryPart::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationLabel = 'Склад запчастей';
    protected static ?string $pluralLabel = 'Склад запчастей';
    protected static ?string $navigationGroup = 'Управление';
    protected static ?int $navigationSort = 3;

    // Только просмотр
    public static function canCreate(): bool { return false; }
    public static function canEdit(Model $record): bool { return false; }
    public static function canDelete(Model $record): bool { return false; }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('carBrand.name')->label('Марка')->sortable()->searchable(),
                TextColumn::make('model')->label('Модель')->sortable()->searchable(),
                TextColumn::make('name')->label('Запчасть')->sortable()->searchable(),
                TextColumn::make('quantity')->label('Количество')->sortable(),
            ])
            ->filters([
                SelectFilter::make('name')
                    ->label('Запчасть')
                    ->options(InventoryPart::query()->distinct()->pluck('name', 'name')->toArray()),
            ])
            ->actions([]) // отключаем действия
            ->bulkActions([]); // отключаем массовые действия
    }

    public static function form(Form $form): Form
    {
        return $form; // форма не нужна
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventoryParts::route('/'),
        ];
    }
}
