<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Models\Testimonial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Website Content';
    protected static ?string $navigationLabel = 'Client Reviews';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Client Info')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('client_name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. Ayesha Khan'),

                        Forms\Components\TextInput::make('client_location')
                            ->maxLength(255)
                            ->placeholder('e.g. Lahore, Pakistan'),

                        Forms\Components\FileUpload::make('client_image')
                            ->image()
                            ->directory('testimonials/clients')
                            ->imageEditor()
                            ->hint('Client profile photo')
                            ->maxSize(2048)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Review Content')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->options([
                                'text' => 'Text Review',
                                'video' => 'Video Review',
                            ])
                            ->default('text')
                            ->required()
                            ->live()
                            ->hint('Select review type'),

                        // Text Review Fields
                        Forms\Components\Textarea::make('content')
                            ->rows(4)
                            ->maxLength(1000)
                            ->placeholder('Client ka feedback yahan likhein...')
                            ->visible(fn (Forms\Get $get) => $get('type') === 'text')
                            ->required(fn (Forms\Get $get) => $get('type') === 'text'),

                        // Video Review Fields - DIRECT UPLOAD
                        Forms\Components\FileUpload::make('video_file')
                            ->label('Upload Video')
                            ->directory('testimonials/videos')
                            ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg'])
                            ->maxSize(51200) // 50MB max
                            ->hint('MP4, WebM, OGG format. Max 50MB')
                            ->visible(fn (Forms\Get $get) => $get('type') === 'video')
                            ->required(fn (Forms\Get $get) => $get('type') === 'video'),

                        Forms\Components\FileUpload::make('video_thumbnail')
                            ->image()
                            ->directory('testimonials/thumbnails')
                            ->imageEditor()
                            ->hint('Video thumbnail (optional) - agar nahi dalein to first frame show hoga')
                            ->maxSize(2048)
                            ->visible(fn (Forms\Get $get) => $get('type') === 'video'),
                    ]),

                Forms\Components\Section::make('Additional Info')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('rating')
                            ->options([
                                5 => '⭐⭐⭐⭐⭐ (5 Stars)',
                                4 => '⭐⭐⭐⭐ (4 Stars)',
                                3 => '⭐⭐⭐ (3 Stars)',
                                2 => '⭐⭐ (2 Stars)',
                                1 => '⭐ (1 Star)',
                            ])
                            ->default(5)
                            ->required(),

                        Forms\Components\TextInput::make('product_name')
                            ->maxLength(255)
                            ->placeholder('e.g. Embroidered Kurti')
                            ->hint('Kis product ka review hai'),
                    ]),

                Forms\Components\Section::make('Settings')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->hint('Lower = first'),

                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->hint('Sirf active reviews website pe show honge'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('client_image')
                    ->circular()
                    ->size(50)
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->client_name) . '&background=1B4332&color=fff'),

                Tables\Columns\TextColumn::make('client_name')
                    ->searchable()
                    ->weight('font-bold'),

                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'success' => 'text',
                        'warning' => 'video',
                    ])
                    ->icons([
                        'heroicon-o-chat-bubble-left-ellipsis' => 'text',
                        'heroicon-o-video-camera' => 'video',
                    ]),

                Tables\Columns\TextColumn::make('content')
                    ->limit(50)
                    ->visibleFrom('md')
                    ->color('gray'),

                Tables\Columns\TextColumn::make('rating')
                    ->formatStateUsing(fn ($state) => str_repeat('⭐', $state))
                    ->color('warning'),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y')
                    ->color('gray'),
            ])
            ->defaultSort('sort_order', 'asc')
            ->reorderable('sort_order')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}