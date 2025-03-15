<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\Widgets\OrderOverview;
use App\Models\Order;
use App\Models\DismantledPart;
use App\Models\CarBrand;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Columns\SelectColumn;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Заказы';
    protected static ?string $pluralLabel = 'Заказы';
    protected static ?string $navigationGroup = 'Управление';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Group::make()->schema([
                        Section::make('Информация о заказе')->schema([
                            Select::make('client_id')
                                ->label('Клиент')
                                ->relationship('client', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            TextInput::make('notes')
                                ->label('Заметка')
                                ->placeholder('Введите заметку...')
                                ->columnSpanFull(),
                        ]),
                        Section::make('Запчасти')->schema([
                            Repeater::make('items')
                                ->relationship('items')
                                ->schema([
                                    // Поле для выбора запчасти
                                    Select::make('dismantled_part_id')  // Запчасть
                                        ->label('Запчасть')
                                        ->options(DismantledPart::pluck('name', 'id')) // Получаем список запчастей
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->reactive()
                                        ->afterStateUpdated(fn($state, Set $set) => $set('unit_amount', optional(DismantledPart::find($state))->price ?? 0))
                                        ->afterStateUpdated(fn($state, Set $set) => $set('total_amount', optional(DismantledPart::find($state))->price ?? 0)),
                                    Select::make('car_brand_id')
                                        ->label('Марка автомобиля')
                                        ->options(CarBrand::pluck('name', 'id')) // Получаем список марок автомобилей
                                        ->required()
                                        ->reactive(),
                                    // Поле для количества запчастей
                                    TextInput::make('quantity')
                                        ->numeric()
                                        ->required()
                                        ->default(1)
                                        ->minValue(1)
                                        ->columnSpan(2)
                                        ->reactive()
                                        ->afterStateUpdated(fn($state, Set $set, Get $get) => $set('total_amount', $state * $get('unit_amount'))),

                                    // Поле для стоимости одной запчасти
                                    TextInput::make('unit_amount')
                                        ->numeric()
                                        ->columnSpan(3),
                                    TextInput::make('total_amount')
                                        ->numeric()
                                        ->columnSpan(3),
                                ]),

                            Placeholder::make('grand_total_placeholder')
                                ->label('Итоговая сумма')
                                ->content(function (Get $get, Set $set) {
                                    $total = 0;
                                    if (!$repeaters = $get('items')) {
                                        return $total;
                                    }

                                    foreach ($repeaters as $key => $reapeater) {
                                        $total += $get("items.{$key}.total_amount");
                                    }

                                    $set('total_price', $total);
                                    return \Illuminate\Support\Number::currency($total, 'руб.');
                                }),

                            TextInput::make('total_price')
                                ->numeric()
                                ->columnSpanFull(),
                        ]),
                    ])->columnSpan(2),

                    Section::make('')->schema([
                        Select::make('payment_method')
                            ->options([
                                'stripe' => 'Оплата картой',
                                'cod' => 'Оплата наличными',
                            ])
                            ->label('Способ оплаты')
                            ->required(),

                        Select::make('status')
                            ->options([
                                'new' => 'Новый',
                                'completed' => 'Выполнен',
                                'cancelled' => 'Отмене',
                            ])
                            ->default('new')
                            ->label('Статус заказа')
                            ->required(),
                    ])->columnSpan(1),
                ])->columns(3)->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Колонка для имени клиента
                TextColumn::make('client.name')
                    ->label('Клиент')
                    ->sortable()
                    ->searchable(),

                // Способ оплаты
                SelectColumn::make('payment_method')
                    ->options([
                        'stripe' => 'Оплата картой',
                        'cod' => 'Оплата наличными',
                    ])
                    ->label('Способ оплаты')
                    ->sortable(),

                // Статус заказа
                SelectColumn::make('status')
                    ->options([
                        'new' => 'Новый',
                        'completed' => 'Выполнен',
                        'cancelled' => 'Отменен',
                    ])
                    ->label('Статус')
                    ->sortable(),

                // Итоговая стоимость заказа
                TextColumn::make('total_price')
                    ->label('Итоговая сумма')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()->requiresConfirmation(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                ExportBulkAction::make(),
            ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
