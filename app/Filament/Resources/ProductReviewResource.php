<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductReviewResource\Pages;
use App\Models\ProductReview;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductReviewResource extends Resource
{
    protected static ?string $model = ProductReview::class;
    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationGroup = 'Website Content';
    protected static ?string $navigationLabel = 'Product Reviews';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Review')->schema([
                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'name')->searchable()->preload()->required(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')->searchable()->preload()->required(),
                Forms\Components\Select::make('order_id')
                    ->relationship('order', 'id')->label('Order')->searchable()->preload(),
                Forms\Components\Select::make('rating')
                    ->options([1 => '1 Star', 2 => '2 Stars', 3 => '3 Stars', 4 => '4 Stars', 5 => '5 Stars'])
                    ->required(),
                Forms\Components\TextInput::make('title')->maxLength(120)->columnSpanFull(),
                Forms\Components\Textarea::make('comment')->required()->rows(5)->maxLength(2000)->columnSpanFull(),
                Forms\Components\FileUpload::make('images')
                    ->multiple()->image()->disk('public')->directory('reviews')->maxFiles(3)->maxSize(3072)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('videos')
                    ->label('Review Video')->multiple()->maxFiles(1)->disk('public')->directory('reviews/videos')
                    ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/quicktime'])->maxSize(25600)
                    ->columnSpanFull(),
            ])->columns(2),
            Forms\Components\Section::make('Moderation')->schema([
                Forms\Components\Select::make('status')->options([
                    'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected',
                ])->required()->default('pending'),
                Forms\Components\Toggle::make('is_verified_purchase')->disabled()->dehydrated(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')->searchable()->sortable()->limit(35),
                Tables\Columns\TextColumn::make('user.name')->label('Customer')->searchable(),
                Tables\Columns\TextColumn::make('rating')->formatStateUsing(fn ($state) => $state.'/5')->badge()->color('warning'),
                Tables\Columns\TextColumn::make('media')
                    ->label('Media')
                    ->state(fn (ProductReview $record) => match (true) {
                        ! empty($record->videos) => count($record->videos).' Video',
                        ! empty($record->images) => count($record->images).' Image(s)',
                        default => 'None',
                    })
                    ->badge()
                    ->color(fn (string $state) => $state === 'None' ? 'gray' : 'info'),
                Tables\Columns\IconColumn::make('is_verified_purchase')->label('Verified')->boolean(),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn (string $state) => match ($state) {
                    'approved' => 'success', 'rejected' => 'danger', default => 'warning',
                }),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected',
                ]),
                Tables\Filters\SelectFilter::make('product_id')->relationship('product', 'name')->label('Product'),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')->icon('heroicon-o-check')->color('success')
                    ->visible(fn (ProductReview $record) => $record->status !== 'approved')
                    ->requiresConfirmation()->action(fn (ProductReview $record) => $record->update(['status' => 'approved'])),
                Tables\Actions\Action::make('reject')->icon('heroicon-o-x-mark')->color('danger')
                    ->visible(fn (ProductReview $record) => $record->status !== 'rejected')
                    ->requiresConfirmation()->action(fn (ProductReview $record) => $record->update(['status' => 'rejected'])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductReviews::route('/'),
            'edit' => Pages\EditProductReview::route('/{record}/edit'),
        ];
    }
}
