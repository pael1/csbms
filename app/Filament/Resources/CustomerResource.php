<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Item;
use Filament\Tables;
use App\Models\Customer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CustomerResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CustomerResource\RelationManagers;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationLabel = 'Sales Invoice';
    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
    ->schema([
        TextInput::make('name')
            ->label('Customer Name')
            ->required()
            ->maxLength(255),

        Select::make('item_num')
            ->label('DR Number')
            ->required()
            ->searchable()
            ->multiple()
            ->preload()
            ->reactive() // ✅ Make reactive
            ->options(fn () => Item::whereNull('customer_id')
                                    ->pluck('item_number', 'item_number')
                                    ->toArray())
            ->afterStateUpdated(function ($state, callable $set) {
                $total = Item::whereIn('item_number', $state)->sum('total_amount');

                $formatted = number_format($total, 0, '.', ',');
                $set('amount', $formatted);
            }),

        TextInput::make('invoice_no')
            ->required()
            ->maxLength(255),

        DatePicker::make('invoice_date')
            ->required(),

        TextInput::make('delivery_no')
            ->required()
            ->maxLength(255),

        DatePicker::make('delivery_date')
            ->required(),

        TextInput::make('amount')
            ->label('Total Amount')
            ->numeric()
            ->prefix('₱')
            ->mask(RawJs::make('$money($input)')) // JS-side formatting
            ->stripCharacters(',') // helps when saving to DB
            ->numeric()
            ->readOnly(),
       
        Textarea::make('remarks')
            ->columnSpanFull(),
    ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Customer Name')
                    ->searchable(),
                TextColumn::make('item_num')
                    ->label('DR Number')
                    ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state)
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('invoice_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('invoice_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('delivery_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('delivery_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'view' => Pages\ViewCustomer::route('/{record}'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }

    public static function getPluralLabel(): string
    {
        return 'Sales Invoices'; // Plural form
    }
    public static function getModelLabel(): string
    {
        return 'Sales Invoice'; // Singular form
    }
}
