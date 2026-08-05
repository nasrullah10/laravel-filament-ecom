<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogPostResource\Pages;
use App\Models\BlogPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;
    protected static ?string $navigationGroup = 'Blog';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Group::make()->schema([
                Forms\Components\Section::make('Post Content')->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()->maxLength(255)->live(onBlur: true)
                        ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state))),
                    Forms\Components\TextInput::make('slug')
                        ->required()->maxLength(255)->unique(ignoreRecord: true),
                    Forms\Components\Textarea::make('excerpt')->rows(3)->maxLength(500)->columnSpanFull(),
                    Forms\Components\RichEditor::make('content')
                        ->required()->fileAttachmentsDisk('public')->fileAttachmentsDirectory('blog/attachments')->columnSpanFull(),
                ])->columns(2),
                Forms\Components\Section::make('SEO')->schema([
                    Forms\Components\TextInput::make('meta_title')->maxLength(60),
                    Forms\Components\Textarea::make('meta_description')->maxLength(160)->rows(3),
                ])->columns(2)->collapsible(),
            ])->columnSpan(2),
            Forms\Components\Group::make()->schema([
                Forms\Components\Section::make('Publishing')->schema([
                    Forms\Components\Select::make('status')->options([
                        'draft' => 'Draft', 'published' => 'Published',
                    ])->default('draft')->required()->live(),
                    Forms\Components\DateTimePicker::make('published_at')
                        ->seconds(false)->default(fn () => now())->required(fn (Forms\Get $get) => $get('status') === 'published'),
                    Forms\Components\Select::make('blog_category_id')
                        ->relationship('category', 'name')->searchable()->preload(),
                    Forms\Components\Select::make('user_id')
                        ->relationship('author', 'name')->searchable()->preload()->default(fn () => auth()->id()),
                    Forms\Components\Toggle::make('is_featured')->default(false),
                ]),
                Forms\Components\Section::make('Featured Image')->schema([
                    Forms\Components\FileUpload::make('featured_image')
                        ->image()->imageEditor()->disk('public')->directory('blog')->visibility('public')->maxSize(4096),
                ]),
            ])->columnSpan(1),
        ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')->disk('public')->square(),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable()->limit(50),
                Tables\Columns\TextColumn::make('category.name')->badge()->placeholder('Uncategorized'),
                Tables\Columns\TextColumn::make('author.name')->label('Author')->placeholder('Unknown'),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn (string $state) => $state === 'published' ? 'success' : 'gray'),
                Tables\Columns\IconColumn::make('is_featured')->boolean(),
                Tables\Columns\TextColumn::make('published_at')->dateTime()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(['draft' => 'Draft', 'published' => 'Published']),
                Tables\Filters\SelectFilter::make('blog_category_id')->relationship('category', 'name')->label('Category'),
                Tables\Filters\TernaryFilter::make('is_featured'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit' => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }
}
