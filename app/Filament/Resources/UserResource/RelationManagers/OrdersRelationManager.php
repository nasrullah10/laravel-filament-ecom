<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Filament\Resources\OrderResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                ->label('Order ID')
                ->searchable(),
                Tables\Columns\TextColumn::make('grand_total')
                ->label('Grand Total')
                ->searchable()
                ->sortable()
                ->money('PKR'),
                Tables\Columns\TextColumn::make('status')
                ->badge()
                ->color(fn($state)=>match($state){
                    'new'=>'info',
                    'processing'=>'warning',
                    'shipped'=>'success',
                    'delivered'=>'success',
                    'cancelled'=>'danger',
                })
                ->icon(fn($state)=>match($state){
                    'new'=>'heroicon-s-sparkles',
                    'processing'=>'heroicon-m-arrow-path',
                    'shipped'=>'heroicon-m-truck',
                    'delivered'=>'heroicon-m-check-badge',
                    'cancelled'=>'heroicon-x-circle',
                })
                ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                ->label('Payment Method')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('payment_status')
                ->label('Payment Status')
                ->searchable()
                ->badge()
                ->color(fn($state)=>match($state){
                    'paid'=>'success',
                    'unpaid'=>'danger',
                })
                ->icon(fn($state)=>match($state){
                    'paid'=>'heroicon-m-check-circle',
                    'unpaid'=>'heroicon-x-circle',
                })
                ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                ->label('Order Date')
                ->datetime()
                ->sortable(),
                
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make('View Order')
                ->url(fn(Order $record):string=>OrderResource::getUrl('view',['record'=>$record->id]))->color('info')->icon('heroicon-m-eye'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
