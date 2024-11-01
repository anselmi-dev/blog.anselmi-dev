<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PostResource\Pages;
use App\Filament\Admin\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Fieldset;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-m-clipboard-document-check';

    public static function getNavigationLabel(): string
    {
        return __('post.plural_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('post.plural_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label(__('post.form.title.label'))
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->label('Title'),

                Select::make('status')
                    ->label(__('post.form.status.label'))
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ])
                    ->default('draft')
                    ->required(),

                TextInput::make('slug')
                    ->label(__('post.form.slug.label'))
                    ->unique(ignoreRecord: true)
                    ->label('Slug'),
                    
                Fieldset::make('Content')
                    ->schema([
                        TextInput::make('description')
                            ->label(__('post.form.description.label'))
                            ->required()
                            ->label('Description'),
                        RichEditor::make('content')
                        ->label(__('post.form.content.label'))
                        ->toolbarButtons([
                            'attachFiles',
                            'blockquote',
                            'bold',
                            'bulletList',
                            'codeBlock',
                            'h2',
                            'h3',
                            'italic',
                            'link',
                            'orderedList',
                            'redo',
                            'strike',
                            'underline',
                            'undo',
                        ])
                            ->required(),
                    ])
                    ->columns(1),

                DateTimePicker::make('published_at')
                    ->label(__('post.form.published_at.label')),

                Select::make('category_id')
                    ->label(__('post.form.category.label'))
                    ->relationship('category', 'name')
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')->label(__('tag.form.name.label'))->unique(ignoreRecord: true)->required(),
                        TextInput::make('description')->label(__('tag.form.description.label')),
                    ]),

                Select::make('tags')
                    ->label(__('post.form.tags.label'))
                    ->multiple()
                    ->preload()
                    ->relationship('tags', 'name')
                    ->createOptionForm([
                        TextInput::make('name')->label(__('tag.form.name.label'))
                            ->minLength(2)
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        TextInput::make('description')
                            ->label(__('tag.form.description.label'))
                            ->minLength(2)
                            ->maxLength(255),
                    ]),
                    
                Fieldset::make()
                    ->schema([
                        DateTimePicker::make('created_at')
                            ->label('filament.form.created_at.label'),
                
                        DateTimePicker::make('updated_at')
                            ->disabled()
                            ->label('filament.form.updated_at.label'),
                    ]),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('post.form.title.label'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->label(__('post.form.status.label'))
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'secondary',
                        'published' => 'success',
                    }),

                TextColumn::make('slug')
                    ->label(__('post.form.slug.label'))
                    ->searchable(),

                TextColumn::make('category.name')
                    ->label(__('post.form.category.label'))
                    ->sortable()
                    ->toggleable(),

                Fieldset::make()
                    ->schema([
                        DateTimePicker::make('created_at')
                            ->label('filament.form.created_at.label'),
                
                        DateTimePicker::make('updated_at')
                            ->disabled()
                            ->label('filament.form.updated_at.label'),
                    ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
