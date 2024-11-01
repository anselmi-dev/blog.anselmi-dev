<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PhotoResource\Pages;
use App\Filament\Resources\PhotoResource\RelationManagers;
use App\Models\Photo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\DateTimeColumn;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\Section;
class PhotoResource extends Resource
{
    protected static ?string $model = Photo::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return __('photo.plural_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('photo.plural_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->label(__('photo.form.title.label')),
        
                Textarea::make('description')
                    ->label(__('photo.form.description.label')),
        
                Textarea::make('text')
                    ->label(__('photo.form.text.label')),
        
                DateTimePicker::make('created_at')
                    ->label('Created At'),
        
                DateTimePicker::make('updated_at')
                    ->disabled()
                    ->label('Updated At'),

                Section::make(__('photo.form.image.label'))
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('image')
                            ->collection(Photo::DISK)
                            ->responsiveImages()
                            // ->multiple()
                            ->conversion('thumb'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('photo.form.title.label'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->label(__('photo.form.description.label'))
                    ->limit(50)
                    ->toggleable(),

                SpatieMediaLibraryImageColumn::make(Photo::DISK)
                    ->collection(Photo::DISK)
                    ->label(__('photo.form.image.label'))
                    ->circular(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Created At')
                    ->sortable(),
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
            'index' => Pages\ListPhotos::route('/'),
            'create' => Pages\CreatePhoto::route('/create'),
            'edit' => Pages\EditPhoto::route('/{record}/edit'),
        ];
    }
}
