<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\ImageColumn;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    ImageColumn::make('image_path')
                        ->label('Imagen')
                        ->imageWidth('100%')
                        ->imageHeight('160px')
                        ->defaultImageUrl(asset('img/placeholders/store_placeholder.jpg'))
                        ->extraImgAttributes([
                            'style' => 'object-fit: cover; object-position: center; border-radius: 0.375rem;',
                        ])
                        ->toggleable(isToggledHiddenByDefault: true),
                    TextColumn::make('name')
                        ->label('Nombre')
                        ->weight(FontWeight::SemiBold)
                        ->searchable(),
                    TextColumn::make('description')
                        ->label('Descripción')
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: false),
                    ]),
                ])->contentGrid([
                    'md' => 2,
                    'xl' => 3,
                ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
