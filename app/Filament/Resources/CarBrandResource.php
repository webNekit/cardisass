<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarBrandResource\Pages;
use App\Filament\Resources\CarBrandResource\RelationManagers;
use App\Models\CarBrand;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CarBrandResource extends Resource
{
    protected static ?string $model = CarBrand::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Марки авто';
    protected static ?string $pluralLabel = 'Марки авто';
    protected static ?string $navigationGroup = 'Администрирование';
    protected static ?string $modelLabel = "";
    protected static ?int $navigationSort = 1;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Название марки')
                        ->required()
                        ->unique(CarBrand::class, 'name'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Название марки')
                    ->sortable()
                    ->searchable(),
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
            'index' => Pages\ListCarBrands::route('/'),
            'create' => Pages\CreateCarBrand::route('/create'),
            'edit' => Pages\EditCarBrand::route('/{record}/edit'),
        ];
    }
}
