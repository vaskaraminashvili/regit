<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationLabel = 'Orders';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('name')->disabled()->dehydrated(false),
                Forms\Components\TextInput::make('phone')->disabled()->dehydrated(false),
                Forms\Components\TextInput::make('city')->disabled()->dehydrated(false),
                Forms\Components\TextInput::make('address')->disabled()->dehydrated(false)->columnSpanFull(),
                Forms\Components\Textarea::make('notes')->disabled()->dehydrated(false)->columnSpanFull(),
                Forms\Components\TextInput::make('total')
                    ->disabled()
                    ->dehydrated(false)
                    ->suffix('₾'),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Order')
                    ->schema([
                        Infolists\Components\TextEntry::make('id')->label('Order #'),
                        Infolists\Components\TextEntry::make('status')->badge(),
                        Infolists\Components\TextEntry::make('payment_method')
                            ->label('Payment')
                            ->formatStateUsing(fn ($state): string => $state === 'bog_installment' ? 'BOG installment' : 'Standard')
                            ->badge()
                            ->color(fn ($state): string => $state === 'bog_installment' ? 'warning' : 'gray'),
                        Infolists\Components\TextEntry::make('installment_months')
                            ->label('Months')
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('payment_status')
                            ->label('BOG status')
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('bog_order_id')
                            ->label('BOG order ID')
                            ->placeholder('—')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('total')->suffix(' ₾'),
                        Infolists\Components\TextEntry::make('created_at')->dateTime(),
                    ])->columns(4),
                Infolists\Components\Section::make('Customer')
                    ->schema([
                        Infolists\Components\TextEntry::make('user.email')->label('Account email'),
                        Infolists\Components\TextEntry::make('name'),
                        Infolists\Components\TextEntry::make('phone'),
                        Infolists\Components\TextEntry::make('city'),
                        Infolists\Components\TextEntry::make('address')->columnSpanFull(),
                        Infolists\Components\TextEntry::make('notes')->columnSpanFull(),
                    ])->columns(2),
                Infolists\Components\RepeatableEntry::make('items')
                    ->label('Items')
                    ->schema([
                        Infolists\Components\TextEntry::make('title'),
                        Infolists\Components\TextEntry::make('sku')->label('SKU'),
                        Infolists\Components\TextEntry::make('with_installation')
                            ->label('Installation')
                            ->formatStateUsing(fn ($state): string => $state ? 'With service' : 'Without service')
                            ->badge()
                            ->color(fn ($state): string => $state ? 'warning' : 'gray'),
                        Infolists\Components\TextEntry::make('price')->suffix(' ₾'),
                        Infolists\Components\TextEntry::make('quantity'),
                        Infolists\Components\TextEntry::make('line_total')->suffix(' ₾'),
                    ])
                    ->columns(6)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total')
                    ->suffix(' ₾')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Payment')
                    ->formatStateUsing(fn ($state): string => $state === 'bog_installment' ? 'BOG installment' : 'Standard')
                    ->badge()
                    ->color(fn ($state): string => $state === 'bog_installment' ? 'warning' : 'gray'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'processing',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
