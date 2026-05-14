<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                    Section::make('Categoria')
                    ->description('Información de la categoría')
                    ->icon('heroicon-o-tag')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->live(debounce: 500)
                            ->afterStateUpdated(
                                fn (callable $set, ?string $state) =>
                                $set('slug', str()->slug($state))
                            )
                            ->required(),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->dehydrated(true)
                            ->unique(Category::class, 'slug', ignoreRecord: true),

                        Textarea::make('description')
                            ->label('Descripción')
                            ->required(),
                    ]),

                    Section::make('Info')
                    ->schema([
                        Toggle::make('is_active')
                        ->label('¿Está activa?'),

                        TextInput::make('display_order')
                            ->label('Orden de visualización')
                            ->numeric()
                            ->default(0)
                    ])
            ]);
    }
}
