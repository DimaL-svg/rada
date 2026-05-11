<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Користувачі';
    protected static ?string $modelLabel = 'Користувач';
    protected static ?string $pluralModelLabel = 'Користувачі';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        Section::make('Особисті дані')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Ім’я')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true),
                            ])->columnSpan(2),
                        Section::make('Доступ')
                            ->schema([
                                Forms\Components\Select::make('role')
                                    ->label('Роль')
                                    ->options([
                                        'admin' => 'Адміністратор',
                                        'editor' => 'Редактор',
                                    ])
                                    ->required()
                                    ->native(false)
                                    ->disabled(fn ($record) => $record && $record->id === 1),

                                Forms\Components\Toggle::make('is_active')
                                    ->label('Активний')
                                    ->helperText('Вимкніть для блокування входу')
                                    ->default(true)
                                    ->disabled(fn ($record) => $record && ($record->id === 1 || $record->id === auth()->id())),
                            ])->columnSpan(1),
                        Section::make('Безпека')   
                            ->schema([
                                Forms\Components\TextInput::make('password')
                                    ->label('Новий пароль')
                                    ->password()
                                    ->revealable()
                                    ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->required(fn (string $context): bool => $context === 'create')
                                    ->validationAttribute('пароль'),

                                Forms\Components\TextInput::make('password_confirmation')
                                    ->label('Підтвердження пароля')
                                    ->password()
                                    ->revealable()
                                    ->required(fn (string $context): bool => $context === 'create')
                                    ->same('password')
                                    ->dehydrated(false),
                            ])->columnSpan(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Ім’я')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('role')
                    ->label('Роль')
                    ->colors([
                        'danger' => 'admin',
                        'warning' => 'editor',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => 'Адміністратор',
                        'editor' => 'Редактор',
                        default => $state,
                    }),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Активний')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Роль')
                    ->options([
                        'admin' => 'Адміністратори',
                        'editor' => 'Редактори',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Статус'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                 
                    ->hidden(fn ($record) => $record->id === 1 || $record->id === auth()->id()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}