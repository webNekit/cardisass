<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DismantledCarResource\Pages;
use App\Filament\Resources\DismantledCarResource\RelationManagers\DismantledPartsRelationManager;
use App\Models\CarBrand;
use App\Models\DismantledCar;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DismantledCarResource extends Resource
{
    protected static ?string $model = DismantledCar::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationLabel = 'Автомобили';
    protected static ?string $pluralLabel = 'Автомобили';
    protected static ?string $navigationGroup = 'Управление';
    protected static ?string $modelLabel = "";
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make()->schema([
                        Select::make('car_brand_id')
                            ->searchable()
                            ->label('Марка авто')
                            ->options(CarBrand::all()->pluck('name', 'id'))
                            ->required(),
                        TextInput::make('model')
                            ->label('Модель')
                            ->required(),
                        Fieldset::make('Данные об автомобиле')->schema([
                            TextInput::make('vin')
                                ->label('VIN - номер')
                                ->unique(DismantledCar::class, 'vin', ignoreRecord:true)
                                ->required(),
                            TextInput::make('mileage')
                                ->label('Пробег')
                                ->numeric()
                                ->required(),
                            TextInput::make('power')
                                ->label('Мощность (л/с)')
                                ->numeric()
                                ->required(),
                        ])->columns(3)->columnSpanFull(),
                        Select::make('condition')
                            ->label('Состояние')
                            ->options([
                                'S' => 'Хорошее качество',
                                'E' => 'Мелкие царапины',
                                'D' => 'Повреждение кузова',
                                'C' => 'Полное повреждение',
                            ])
                            ->required(),
                        Select::make('status')
                            ->label('Статус')
                            ->options([
                                'arrived' => 'Привезён',
                                'dismantling' => 'В разборке',
                                'dismantled' => 'Разобран',
                                'rejected' => 'Отклонён',
                            ])
                            ->required()
                            ->reactive(),
                        TextInput::make('rejection_reason')
                            ->label('Причина отклонения')
                            ->visible(fn ($get) => $get('status') === 'rejected')
                            ->required(fn ($get) => $get('status') === 'rejected'),
                    ]),
                    Section::make()->schema([
                        Fieldset::make()->schema([
                            TagsInput::make('damaged_parts')
                                ->label('Поврежденные детали'),
                            TagsInput::make('parts_for_sale')
                                ->required()
                                ->label('Запчасти для разборки'),
                        ])->columns(2)->columnSpanFull()
                    ]),
                    Section::make()->schema([
                        FileUpload::make('image')
                            ->label('Изображение')
                            ->directory('cars')
                            ->image(),
                    ]),
                ])->columns(2)->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('brand.name')
                    ->label('Марка')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('model')
                    ->label('Модель')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('vin')
                    ->label('VIN')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('mileage')
                    ->label('Пробег'),

                Tables\Columns\TextColumn::make('power')
                    ->label('Мощность'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Статус')
                    ->colors([
                        'primary' => 'arrived',
                        'warning' => 'dismantling',
                        'success' => 'dismantled',
                        'danger' => 'rejected',
                    ])
                    ->formatStateUsing(function (string $state): string {
                        return match ($state) {
                            'arrived' => 'Привезён',
                            'dismantling' => 'В разборке',
                            'dismantled' => 'Разобран',
                            'rejected' => 'Отклонён',
                            default => ucfirst($state),
                        };
                    }),

                Tables\Columns\TextColumn::make('rejection_reason')
                    ->label('Причина отклонения')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Статус')
                    ->options([
                        'arrived' => 'Привезён',
                        'dismantling' => 'В разборке',
                        'dismantled' => 'Разобран',
                        'rejected' => 'Отклонён',
                    ]),
                Tables\Filters\SelectFilter::make('car_brand_id')
                    ->label('Марка авто')
                    ->relationship('brand', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            DismantledPartsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDismantledCars::route('/'),
            'create' => Pages\CreateDismantledCar::route('/create'),
            'edit' => Pages\EditDismantledCar::route('/{record}/edit'),
        ];
    }
}
