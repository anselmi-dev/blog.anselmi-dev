<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TagResource\Pages;
use App\Filament\Admin\Resources\TagResource\RelationManagers;
use App\Models\Tag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\{
    TextInput,
    Fieldset,
    DateTimePicker
};
use Filament\Tables\Columns\{
    TextColumn
};

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function getNavigationGroup(): string
    {
        return __('filament.navigation.groups.general.label');
    }

    public static function getNavigationLabel(): string
    {
        return __('tag.plural_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('tag.plural_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label(__('tag.form.name.label'))
                    ->minLength(2)
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('description')
                    ->label(__('tag.form.description.label'))
                    ->minLength(2)
                    ->maxLength(255),
                Fieldset::make()
                    ->schema([
                        DateTimePicker::make('created_at')
                            ->label('Created At'),
                
                        DateTimePicker::make('updated_at')
                            ->disabled()
                            ->label('Updated At'),
                    ]),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label(__('tag.form.name.label')),
                TextColumn::make('slug')->label(__('tag.form.slug.label')),
                TextColumn::make('description')->label(__('tag.form.description.label')),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('filament.form.created_at.label')
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
            'index' => Pages\ListTags::route('/'),
            'create' => Pages\CreateTag::route('/create'),
            'edit' => Pages\EditTag::route('/{record}/edit'),
        ];
    }
}
