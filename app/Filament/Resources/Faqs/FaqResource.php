<?php

namespace App\Filament\Resources\Faqs;

use App\Filament\Resources\Faqs\Pages\CreateFaq;
use App\Filament\Resources\Faqs\Pages\EditFaq;
use App\Filament\Resources\Faqs\Pages\ListFaqs;
use App\Filament\Support\CompleteWithAiAction;
use App\Filament\Support\LanguageTabs;
use App\Models\Faq;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQuestionMarkCircle;

    protected static string|UnitEnum|null $navigationGroup = 'Servicios';

    protected static ?string $navigationLabel = 'FAQs';

    protected static ?string $modelLabel = 'FAQ';

    protected static ?string $pluralModelLabel = 'FAQs';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'question';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                LanguageTabs::make(
                    spanish: [
                        TextInput::make('question')
                            ->label('Pregunta')
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('answer')
                            ->label('Respuesta')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),
                    ],
                    translations: [
                        'en' => [
                            TextInput::make('question_en')
                                ->label('Question')
                                ->maxLength(255),
                            Textarea::make('answer_en')
                                ->label('Answer')
                                ->rows(4),
                        ],
                    ],
                    translatableFields: ['question', 'answer'],
                    completion: CompleteWithAiAction::make(
                        about: 'una pregunta frecuente de la sección FAQ, sobre cómo trabajo como desarrollador web freelance',
                        guidance: [
                            'question' => 'La pregunta, tal como la haría un cliente.',
                            'answer' => 'Respuesta clara en 2 a 4 oraciones. No prometas precios, plazos ni servicios concretos que no estén en el contexto; si hace falta, invitá a escribir por el formulario de contacto.',
                        ],
                    ),
                ),
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
                TextColumn::make('question')
                    ->label('Pregunta')
                    ->searchable()
                    ->wrap()
                    ->limit(80),
                TextColumn::make('sort_order')
                    ->label('Orden')
                    ->sortable(),
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
            'index' => ListFaqs::route('/'),
            'create' => CreateFaq::route('/create'),
            'edit' => EditFaq::route('/{record}/edit'),
        ];
    }
}
