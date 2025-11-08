<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DateTimePicker;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
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

                TextInput::make('description')
                    ->label(__('post.form.description.label'))
                    ->required()
                    ->label('Description'),

                Section::make()
                    ->schema([
                        Repeater::make('content')
                            ->label(__('post.form.content.label'))
                            ->schema([
                                Select::make('type')
                                    ->label(__('post.form.type.label'))
                                    ->options([
                                        'view' => 'View',
                                        'richEditor' => 'richEditor',
                                        'image' => 'imagen',
                                    ])
                                    ->required()
                                    ->reactive(),

                                Select::make('view')
                                    ->label(__('post.form.view.label'))
                                    ->options(function () {
                                        $files = \Illuminate\Support\Facades\File::files(resource_path('views/components/post/contents'));
                                        $options = [];
                                        foreach ($files as $file) {
                                            $filename = str_replace('.blade', '', pathinfo($file->getFilename(), PATHINFO_FILENAME));
                                            $options[$filename] = ucfirst($filename); // Usa el nombre del archivo como opción
                                        }

                                        return $options;
                                    })
                                    ->visible(fn ($get) => $get('type') === 'view')
                                    ->required(),

                                SpatieMediaLibraryFileUpload::make('image')
                                    ->label(__('photo.form.image.label'))
                                    // ->collection(Post::DISK)
                                    ->responsiveImages()
                                    ->conversion('thumb')
                                    ->visible(fn ($get) => $get('type') === 'image'),

                                RichEditor::make('richEditor')
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
                                    ->required()
                                    ->visible(fn ($get) => $get('type') === 'richEditor'),
                            ])
                            ->columns(1),
                    ])->columnSpanFull(),

                DateTimePicker::make('published_at')
                    ->label(__('post.form.published_at.label'))
                    ->required(),

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

                Section::make()
                    ->schema([
                        DateTimePicker::make('created_at')
                            ->label('filament.form.created_at.label'),

                        DateTimePicker::make('updated_at')
                            ->disabled()
                            ->label('filament.form.updated_at.label'),
                    ])->columnSpanFull()->columns(2),
            ]);

    }
}
