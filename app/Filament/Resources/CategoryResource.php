<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Категорії';
    protected static ?string $modelLabel = 'Категорія';
    protected static ?string $pluralModelLabel = 'Категорії';
    public static function canViewAny(): bool
    {
        return auth()->user()->role === 'admin';
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Основна інформація')
                    ->schema([
                        TextInput::make('name')
                            ->label('Назва категорії')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set, $operation) => 
                                $operation === 'create' ? $set('slug', Str::slug($state)) : null
                            ),

                        Toggle::make('is_visible')
                            ->label('Відображати на сайті')
                            ->default(true)
                            ->helperText('Якщо вимкнено, категорія та її статті не будуть відображатися.'),

                        TextInput::make('slug')
                            ->label('URL (Slug)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('Генерується автоматично з назви'),

                        Select::make('parent_id')
                            ->label('Батьківська категорія')
                            ->relationship('parent', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Це головна категорія'),

                        TextInput::make('pos')
                            ->label('Позиція')
                            ->numeric()
                            ->default(0)
                            ->disabled() 
                            ->dehydrated(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('pos') 
            ->defaultSort('pos', 'asc')
            ->columns([
                TextColumn::make('name')
                    ->label('Назва')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Category $record): string => $record->slug ?? '')
                    ->wrap()
                    ->grow(), 

                TextColumn::make('parent.name')
                    ->label('Батьківська')
                    ->badge()
                    ->color('gray')
                    ->placeholder('Головна'),

                TextColumn::make('articles_count')
                    ->label('Статей')
                    ->counts('articles')
                    ->badge(),

                ToggleColumn::make('is_visible')
                    ->label('Активна')
                    ->onColor('success')
                    ->offColor('danger'),

                TextColumn::make('deleted_at')
                    ->label('Видалено')
                    ->dateTime()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
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