<?php

namespace App\Filament\Resources\Tools;

use App\Filament\Resources\Tools\Pages\CreateTool;
use App\Filament\Resources\Tools\Pages\EditTool;
use App\Filament\Resources\Tools\Pages\ListTools;
use App\Filament\Support\CompleteWithAiAction;
use App\Filament\Support\LanguageTabs;
use App\Models\Tool;
use App\Support\JarShape;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ToolResource extends Resource
{
    protected static ?string $model = Tool::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static string|UnitEnum|null $navigationGroup = 'Servicios';

    protected static ?string $navigationLabel = 'Herramientas';

    protected static ?string $modelLabel = 'herramienta';

    protected static ?string $pluralModelLabel = 'Herramientas';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required(),
                LanguageTabs::make(
                    spanish: [
                        TextInput::make('tagline')
                            ->label('Descripción corta'),
                        TextInput::make('category')
                            ->label('Categoría')
                            ->placeholder('Backend'),
                        Textarea::make('description')
                            ->label('Descripción')
                            ->helperText('Se muestra al abrir el frasco en detalle: qué es y para qué la usás.')
                            ->rows(4)
                            ->columnSpanFull(),
                    ],
                    translations: [
                        'en' => [
                            TextInput::make('tagline_en')
                                ->label('Short description')
                                ->maxLength(255),
                            TextInput::make('category_en')
                                ->label('Category')
                                ->maxLength(255),
                            Textarea::make('description_en')
                                ->label('Description')
                                ->rows(4)
                                ->columnSpanFull(),
                        ],
                    ],
                    translatableFields: ['tagline', 'category', 'description'],
                    completion: CompleteWithAiAction::make(
                        about: 'una herramienta o tecnología que uso para trabajar como desarrollador web (sale dentro de un frasco en /servicios/herramientas)',
                        guidance: [
                            'tagline' => 'Qué es, en 2 a 6 palabras (ej. "Backend & estructura de la app").',
                            'category' => 'Rubro en 1 o 2 palabras: Backend, Frontend, Base de datos, Infraestructura, Diseño, Herramientas…',
                            'description' => 'Qué es y para qué sirve en un proyecto web, en 1 o 2 oraciones (máximo 260 caracteres).',
                        ],
                        context: ['name' => 'Nombre', 'url' => 'Sitio web'],
                        webSearch: true,
                    ),
                ),
                TextInput::make('url')
                    ->label('URL')
                    ->url()
                    ->required(),
                TextInput::make('icon')
                    ->label('Icono Simple Icons')
                    ->helperText('Slug de https://simpleicons.org (ej: laravel, livewire)'),
                ColorPicker::make('label_color')
                    ->label('Color de la etiqueta')
                    ->helperText('Color del frasco en /servicios/herramientas. El texto y las manchas se ajustan solos para que se lea bien.'),
                ColorPicker::make('fill_color')
                    ->label('Color del contenido')
                    ->helperText('Lo que se ve dentro del frasco. Opcional.'),
                Select::make('jar_shape')
                    ->label('Forma del frasco')
                    ->options(JarShape::class)
                    ->placeholder('Automática')
                    ->helperText('Si la dejás vacía, se elige una según el orden.'),
                TextInput::make('sort_order')
                    ->label('Orden')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_published')
                    ->label('Publicado')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category')
                    ->label('Categoría')
                    ->badge(),
                TextColumn::make('tagline')
                    ->label('Tagline')
                    ->limit(40)
                    ->toggleable(),
                ColorColumn::make('label_color')
                    ->label('Color'),
                TextColumn::make('icon')
                    ->label('Icono')
                    ->toggleable(),
                IconColumn::make('is_published')
                    ->label('Pub.')
                    ->boolean(),
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

    public static function getPages(): array
    {
        return [
            'index' => ListTools::route('/'),
            'create' => CreateTool::route('/create'),
            'edit' => EditTool::route('/{record}/edit'),
        ];
    }
}
