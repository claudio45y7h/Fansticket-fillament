<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información de la Orden')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->label('Cliente'),

                        Forms\Components\Select::make('event_id')
                            ->relationship('event', 'event')
                            ->required()
                            ->searchable()
                            ->label('Evento'),

                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pendiente',
                                'completed' => 'Completado',
                                'cancelled' => 'Cancelado'
                            ])
                            ->required()
                            ->label('Estado'),

                        Forms\Components\TextInput::make('total')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->label('Total'),
                    ])->columns(2),

                Forms\Components\Section::make('Información de Pago')
                    ->schema([
                        Forms\Components\TextInput::make('brand')
                            ->label('Marca de Tarjeta'),

                        Forms\Components\TextInput::make('issuer')
                            ->label('Emisor'),

                        Forms\Components\TextInput::make('last4')
                            ->label('Últimos 4 dígitos')
                            ->maxLength(4),

                        Forms\Components\TextInput::make('receipt_no')
                            ->label('Número de Recibo'),
                    ])->columns(2),

                Forms\Components\Section::make('Tickets')
                    ->schema([
                        Forms\Components\Select::make('tickets')
                            ->relationship('tickets', 'info')
                            ->multiple()
                            ->searchable()
                            ->label('Tickets Asignados'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->label('ID'),

                Tables\Columns\TextColumn::make('user.name')
                    ->sortable()
                    ->searchable()
                    ->label('Cliente'),

                Tables\Columns\TextColumn::make('event.event')
                    ->sortable()
                    ->searchable()
                    ->label('Evento'),

                Tables\Columns\TextColumn::make('total')
                    ->money('mxn')
                    ->sortable()
                    ->label('Total'),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pendiente',
                        'completed' => 'Completado',
                        'cancelled' => 'Cancelado',
                    })
                    ->sortable()
                    ->label('Estado'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->label('Creada'),
            ])
            ->filters([
                // Puedes agregar filtros aquí si es necesario
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
