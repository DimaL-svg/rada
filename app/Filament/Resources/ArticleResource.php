<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Статті';
    protected static ?string $modelLabel = 'Стаття';
    protected static ?string $pluralModelLabel = 'Статті';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Основний контент')
                    ->schema([
                        TextInput::make('title')
                            ->label('Заголовок')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set, $operation) => 
                                $operation === 'create' ? $set('slug', Str::slug($state)) : null
                            ),
                        
                        TextInput::make('slug')
                            ->label('URL (Slug)')
                            ->required()
                            ->unique(ignoreRecord: true),

                        RichEditor::make('content')
                            ->label('Текст статті')
                            ->required()
                            ->fileAttachmentsDirectory('articles')
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Налаштування')
                    ->schema([
                        Select::make('category_id')
                            ->label('Категорія')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Select::make('user_id')
                            ->label('Автор')
                            ->relationship('author', 'name')
                            ->default(auth()->id())
                            ->required()
                            ->searchable(),

                        Toggle::make('is_active')
                            ->label('Опубліковано')
                            ->default(true),
                    ])->columns(3),

                Section::make('SEO налаштування')
                    ->description('Якщо залишити порожніми, дані згенеруються автоматично')
                    ->collapsed()
                    ->schema([
                        TextInput::make('seo_title')
                            ->label('SEO Заголовок'),
                        Textarea::make('seo_desc')
                            ->label('SEO Опис'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('title')
                    ->label('Заголовок')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->grow(), 

                TextColumn::make('category.name')
                    ->label('Категорія')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Статус'),

                TextColumn::make('created_at')
                    ->label('Дата створення')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),

                Tables\Filters\SelectFilter::make('category')
                    ->label('Категорія')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}