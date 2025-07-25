<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Item;
use App\Models\Type;
use Filament\Tables;
use App\Models\Brand;
use Filament\Forms\Form;
use App\Models\HoresPower;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use App\Models\MountingType;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ItemResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ItemResource\RelationManagers;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationLabel = 'Delivery Receipt';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Grid::make(12)
                ->schema([
                    Forms\Components\TextInput::make('item_number')
                    ->label('DR Number')
                    ->required()
                    ->unique()
                    ->maxLength(255)
                    ->columnSpan(2),
                    TextInput::make('price')
                        ->label('Price')
                        ->required()
                        // ->reactive()
                        // ->debounce(500)
                        // ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        //     $cleanPrice = floatval(str_replace(',', '', $state));
                        //     $set('total_amount', $cleanPrice * count($get('indoor_sn') ?? []));
                        // })
                        ->prefix('₱')
                        ->mask(RawJs::make('$money($input)')) // JS-side formatting
                        ->stripCharacters(',') // helps when saving to DB
                        ->numeric()
                        ->columnSpan(3)
                        ->columnStart(10),
                ]),

            // Two-column layout for the rest
            Forms\Components\Grid::make(2)
                ->schema([
                    Forms\Components\Select::make('brand_id')
                        ->label('Brand')
                        ->required()
                        ->native(false)
                        ->searchable()
                        ->options(fn() => Brand::pluck('name', 'id')->toArray()),

                    Forms\Components\Select::make('horse_power_id')
                        ->label('Horse Power')
                        ->required()
                        ->native(false)
                        ->searchable()
                        ->options(fn() => HoresPower::pluck('name', 'id')->toArray()),

                    Forms\Components\Select::make('mounting_type_id')
                        ->label('Mounting Type')
                        ->required()
                        ->native(false)
                        ->searchable()
                        ->options(fn() => MountingType::pluck('name', 'id')->toArray()),

                    Forms\Components\Select::make('type_id')
                        ->label('AC Type')
                        ->required()
                        ->native(false)
                        ->searchable()
                        ->options(fn() => Type::pluck('name', 'id')->toArray()),

                    Forms\Components\TextInput::make('indoor_model')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('outdoor_model')
                        ->required()
                        ->maxLength(255),
                ]),

            // Full-width repeaters
            Forms\Components\Repeater::make('indoor_sn')
                ->label('Indoor Serial Number')
                ->reactive()
                ->debounce(500)
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $rawPrice = $get('price') ?? 0;
                    $cleanPrice = floatval(str_replace(',', '', $rawPrice));
                    $total = $cleanPrice * count($state ?? []);

                    // Format the total amount to 2 decimal places with comma
                    $formatted = number_format($total, 0, '.', ',');

                    $set('total_amount', $formatted);
                })
                ->simple(
                    Forms\Components\TextInput::make('indoor_sn')
                        ->required()
                        ->distinct() // Prevents duplicates in the array automatically
                        ->maxLength(255),
                ),

            Forms\Components\Repeater::make('outdoor_sn')
                ->label('Outdoor Serial Number')
                ->simple(
                    Forms\Components\TextInput::make('outdoor_sn')
                        ->required()
                        ->distinct()
                        ->maxLength(255),
                ),

            Forms\Components\Grid::make(12)
                ->schema([
                    Forms\Components\TextInput::make('total_amount')
                    ->label('Total Amount')
                    ->prefix('₱')
                    ->mask(RawJs::make('$money($input)')) // JS-side formatting
                    ->stripCharacters(',') // helps when saving to DB
                    ->numeric()
                    ->readOnly()
                    ->columnSpan(3)
                    ->columnStart(10),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')
                    ->label('Customer Name')
                    ->badge()
                    ->getStateUsing(fn($record) => $record->customer?->name ?? 'Available')
                    ->color(fn (string $state): string => match ($state) {
                        'Available' => 'success',
                        default => 'danger',
                    }),
                TextColumn::make('item_number')
                    ->label('Item #')
                    ->sortable(),
                TextColumn::make('brand.name')
                    ->label('Brand')
                    ->sortable(),
                TextColumn::make('horsePower.name')
                    ->label('HP')
                    ->sortable(),
                TextColumn::make('mountingType.name')
                    ->label('Mounting Type')
                    ->sortable(),
                TextColumn::make('type.name')
                    ->label('AC Type')
                    ->sortable(),
                TextColumn::make('indoor_model')
                    ->searchable(),
                TextColumn::make('indoor_sn')
                ->label('Indoor SN')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    default => 'warning',
                })
                ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state)
                ->wrap()
                ->searchable(),
                TextColumn::make('outdoor_model'),
                TextColumn::make('outdoor_sn')
                ->label('Outdoor SN')
                ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state)
                ->wrap()
                ->searchable(),
                TextColumn::make('price')
                    ->label('Price')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->label('Total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'view' => Pages\ViewItem::route('/{record}'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }

    public static function getPluralLabel(): string
    {
        return 'Delivery Receipts'; // Plural form
    }
    public static function getModelLabel(): string
    {
        return 'Delivery Receipt'; // Singular form
    }
}
