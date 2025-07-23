<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Particular;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ParticularResource\Pages;
use App\Filament\Resources\ParticularResource\RelationManagers;

class ParticularResource extends Resource
{
    protected static ?string $model = Particular::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('type')
                    ->required()
                    ->maxLength(255),
                TextInput::make('indoor_model')
                    ->maxLength(255),
                TextInput::make('outdoor_model')
                    ->maxLength(255),
                // TextInput::make('indoor_sn'),
                // TextInput::make('outdoor_sn'),
                Repeater::make('Indoor Serial Number')
                ->simple(
                    TextInput::make('indoor_sn')
                        ->required()
                        ->maxLength(255),
                ),
                Repeater::make('Outdoor Serial Number')
                ->simple(
                    TextInput::make('outdoor_sn')
                        ->required()
                        ->maxLength(255),
                ),
                TextInput::make('inv_1')
                    ->maxLength(255),
                TextInput::make('inv_2')
                    ->maxLength(255),
                DatePicker::make('date_issued_1'),
                DatePicker::make('date_issued_2'),
                TextInput::make('total')
                    ->numeric(),
                TextInput::make('remarks')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('indoor_model')
                    ->searchable(),
                Tables\Columns\TextColumn::make('outdoor_model')
                    ->searchable(),
                Tables\Columns\TextColumn::make('inv_1')
                    ->searchable(),
                Tables\Columns\TextColumn::make('inv_2')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_issued_1')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_issued_2')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('remarks')
                    ->searchable(),
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
            'index' => Pages\ListParticulars::route('/'),
            'create' => Pages\CreateParticular::route('/create'),
            'view' => Pages\ViewParticular::route('/{record}'),
            'edit' => Pages\EditParticular::route('/{record}/edit'),
        ];
    }
}
