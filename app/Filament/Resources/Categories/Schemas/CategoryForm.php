<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->maxLength(255)
                    ->live(debounce: 500)
                    ->afterStateUpdated(fn (callable $set, ?string $state) => $set('slug', str()->slug($state)))
                    ->required(),
                TextInput::make('slug')
                    ->label('Slug')
                    ->maxLength(255)
                    ->dehydrated(true)
                    ->unique(Category::class, 'slug', ignoreRecord: true),
                TextInput::make('description')
                    ->label('Descripción')
                    ->maxLength(255)
                    ->required(),
            ]);
    }
}
