<?php

namespace App\Filament\Resources\Posts;

use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Filament\Resources\Posts\Pages\ListPosts;
use App\Models\Post;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'Contenido';

    protected static ?string $navigationLabel = 'Posts y notas';

    protected static ?string $modelLabel = 'post / nota';

    protected static ?string $pluralModelLabel = 'Posts y notas';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Contenido')
                    ->columns(2)
                    ->schema([
                        Select::make('kind')
                            ->label('Tipo')
                            ->options([
                                'note' => 'Nota / Post',
                                'image' => 'Imagen (blog)',
                            ])
                            ->required()
                            ->live()
                            ->default('note'),
                        TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, ?string $state, ?string $old, Get $get): void {
                                if (filled($get('slug')) && $get('slug') !== Str::slug((string) $old)) {
                                    return;
                                }
                                $set('slug', Str::slug((string) $state));
                            }),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('URL: /blog/{slug}'),
                        TextInput::make('kicker')
                            ->label('Kicker')
                            ->placeholder('Mar 2025 · proceso'),
                        Textarea::make('excerpt')
                            ->label('Extracto')
                            ->rows(3)
                            ->columnSpanFull(),
                        Repeater::make('body')
                            ->label('Párrafos')
                            ->simple(Textarea::make('paragraph')->rows(3)->required())
                            ->default([])
                            ->columnSpanFull()
                            ->addActionLabel('Agregar párrafo'),
                        Textarea::make('caption')
                            ->label('Pie de imagen')
                            ->rows(2)
                            ->visible(fn (Get $get): bool => $get('kind') === 'image')
                            ->columnSpanFull(),
                        FileUpload::make('image_path')
                            ->label('Imagen')
                            ->image()
                            ->disk('public')
                            ->directory('posts')
                            ->visibility('public')
                            ->imageEditor()
                            ->visible(fn (Get $get): bool => $get('kind') === 'image')
                            ->columnSpanFull(),
                        TextInput::make('alt')
                            ->label('Texto alt')
                            ->visible(fn (Get $get): bool => $get('kind') === 'image'),
                    ]),
                Section::make('Bento / listado')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        Toggle::make('show_in_bento')
                            ->label('Mostrar en el bento del blog')
                            ->live()
                            ->default(false),
                        Select::make('bento_type')
                            ->label('Tipo en bento')
                            ->options([
                                'card' => 'Tarjeta (texto)',
                                'image' => 'Imagen',
                            ])
                            ->visible(fn (Get $get): bool => (bool) $get('show_in_bento')),
                        TextInput::make('bento_grid_class')
                            ->label('Clases de grilla')
                            ->placeholder('xl:col-span-3')
                            ->visible(fn (Get $get): bool => (bool) $get('show_in_bento'))
                            ->columnSpanFull(),
                        TextInput::make('bento_sort')
                            ->label('Orden en bento')
                            ->numeric()
                            ->default(0)
                            ->visible(fn (Get $get): bool => (bool) $get('show_in_bento')),
                        TextInput::make('sort_order')
                            ->label('Orden general')
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_published')
                            ->label('Publicado')
                            ->default(true),
                        DateTimePicker::make('published_at')
                            ->label('Fecha de publicación'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Img')
                    ->disk('public')
                    ->square(),
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kind')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'image' ? 'Imagen' : 'Nota')
                    ->color(fn (string $state): string => $state === 'image' ? 'info' : 'success'),
                TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('show_in_bento')
                    ->label('Bento')
                    ->boolean(),
                IconColumn::make('is_published')
                    ->label('Pub.')
                    ->boolean(),
                TextColumn::make('published_at')
                    ->label('Fecha')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('kind')
                    ->label('Tipo')
                    ->options([
                        'note' => 'Nota / Post',
                        'image' => 'Imagen',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }
}
