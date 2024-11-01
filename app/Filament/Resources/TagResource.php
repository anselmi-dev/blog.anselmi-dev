<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TagResource\Pages;
use App\Filament\Resources\TagResource\RelationManagers;
use App\Models\Tag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                TextInput::make('name')->label(__('tag.form.name.label'))->unique(ignoreRecord: true)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label(__('tag.form.name.label')),
                TextColumn::make('slug')->label(__('tag.form.slug.label')),
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
