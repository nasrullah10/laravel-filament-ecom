<?php
// app/Filament/Resources/CategoryResource.php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // ===== HIERARCHY =====
                Forms\Components\Section::make('Category Hierarchy')
                    ->icon('heroicon-o-list-bullet')
                    ->schema([
                        Forms\Components\Select::make('parent_id')
                            ->label('Parent Category')
                            ->placeholder('— No Parent (Main Category) —')
                            ->options(function (?Category $record) {
                                return Category::query()
                                    ->where('is_active', true)
                                    ->when($record, fn($q) => $q->where('id', '!=', $record->id))
                                    ->orderBy('name')
                                    ->get()
                                    ->mapWithKeys(function ($cat) {
                                        $level = $cat->parent_id ? ' [Sub]' : ' [Main]';
                                        return [$cat->id => $cat->name . $level];
                                    });
                            })
                            ->searchable()
                            ->preload()
                            ->live(),

                        Forms\Components\Placeholder::make('level_display')
                            ->label('Category Level')
                            ->content(function (Forms\Get $get): string {
                                $parentId = $get('parent_id');
                                if (!$parentId) return '⭐ Main Category';
                                $parent = Category::find($parentId);
                                return (!$parent || !$parent->parent_id) ? '📁 Subcategory' : '🔹 Child Category';
                            }),
                    ]),

                // ===== BASIC INFO =====
                Forms\Components\Section::make('Basic Information')
                    ->icon('heroicon-o-information-circle')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Forms\Set $set, ?string $state) {
                                $set('slug', Str::slug($state));
                            }),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(Category::class, 'slug', ignoreRecord: true)
                            ->readOnly(),
                    ]),

                // ===== MEDIA =====
                Forms\Components\Section::make('Media')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->directory('categories')
                            ->disk('public')
                            ->visibility('public')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->columnSpanFull(),
                    ]),

                // ===== SIZE CHART (Khaadi Style) =====
                Forms\Components\Section::make('Size Chart & Guide')
                    ->icon('heroicon-o-scale')  // ✅ CORRECT ICON
                    ->description('Add size options and measurements for this category')
                    ->collapsible()
                    ->schema([
                        
                        // Enable Size Chart
                        Forms\Components\Toggle::make('has_size_chart')
                            ->label('Enable Size Chart')
                            ->default(false)
                            ->live()
                            ->helperText('Turn ON to show size guide on product pages'),

                        // Size Options (XS, S, M, L, XL)
                        Forms\Components\TagsInput::make('size_options')
                            ->label('Available Sizes')
                            ->placeholder('Type size and press Enter')
                            ->separator(',')
                            ->suggestions(['XS', 'S', 'M', 'L', 'XL', 'XXL', '8', '10', '12', '14', '16'])
                            ->helperText('e.g. XS, S, M, L, XL')
                            ->visible(fn (Forms\Get $get) => $get('has_size_chart')),

                        // Size Guide Text
                        Forms\Components\Textarea::make('size_guide_text')
                            ->label('Size Guide Note')
                            ->rows(2)
                            ->placeholder('All measurements are in inches. Model is wearing size S.')
                            ->helperText('This text shows above the size chart table')
                            ->visible(fn (Forms\Get $get) => $get('has_size_chart')),

                        // Size Chart Table Builder
                        Forms\Components\Repeater::make('size_chart')
                            ->label('Size Measurements Table')
                            ->addActionLabel('Add Size Row')
                            ->reorderableWithDragAndDrop()
                            ->schema([
                                Forms\Components\TextInput::make('size')
                                    ->label('Size')
                                    ->placeholder('S')
                                    ->required(),

                                Forms\Components\TextInput::make('length')
                                    ->label('Length')
                                    ->placeholder('30')
                                    ->numeric()
                                    ->suffix('inches'),

                                Forms\Components\TextInput::make('shoulder')
                                    ->label('Shoulder')
                                    ->placeholder('14')
                                    ->numeric()
                                    ->suffix('inches'),

                                Forms\Components\TextInput::make('chest')
                                    ->label('Chest')
                                    ->placeholder('21')
                                    ->numeric()
                                    ->suffix('inches'),

                                Forms\Components\TextInput::make('sleeve_length')
                                    ->label('Sleeve Length')
                                    ->placeholder('22')
                                    ->numeric()
                                    ->suffix('inches'),
                            ])
                            ->columns(5)
                            ->defaultItems(5)
                            ->visible(fn (Forms\Get $get) => $get('has_size_chart'))
                            ->helperText('Add measurements for each size. All values in inches.'),
                    ]),

                // ===== SETTINGS =====
                Forms\Components\Section::make('Settings')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->default(true),

                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->disk('public')
                    ->square()
                    ->size(50),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function (Category $record): string {
                        $indent = '';
                        if ($record->parent_id) {
                            $parent = $record->parent;
                            $indent = $parent && $parent->parent_id ? '      └─ ' : '   └─ ';
                        }
                        return $indent . $record->name;
                    })
                    ->weight(fn (Category $record): string => $record->parent_id ? 'font-normal' : 'font-bold'),

                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Parent')
                    ->placeholder('— Main —')
                    ->badge(),

                // Size Chart Indicator
                Tables\Columns\IconColumn::make('has_size_chart')
                    ->label('Size Chart')
                    ->boolean()
                    ->trueIcon('heroicon-o-scale')  // ✅ CORRECT
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable()
                    ->badge(),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('parent_id')
                    ->label('Filter by Parent')
                    ->options(function () {
                        return Category::whereNull('parent_id')
                            ->where('is_active', true)
                            ->pluck('name', 'id');
                    }),
                Tables\Filters\TernaryFilter::make('is_active'),
                Tables\Filters\TernaryFilter::make('has_size_chart')
                    ->label('Has Size Chart'),
            ])
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['parent', 'children'])
            ->orderByRaw('
                CASE 
                    WHEN parent_id IS NULL THEN id 
                    ELSE parent_id 
                END, 
                parent_id IS NOT NULL, 
                sort_order
            ');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}