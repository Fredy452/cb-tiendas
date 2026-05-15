<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\Section;

class CategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns([
                'default' => 1,
                'lg' => 3,
            ])
            ->components([
                Section::make([
                    TextEntry::make('name')
                        ->label('Nombre de la categoría'),
                    ImageEntry::make('image_path')
                        ->label('Imagen')
                        ->disk('public')
                        ->imageWidth('100%')
                        ->imageHeight('180px')
                        ->extraImgAttributes([
                            'style' => 'width: 100%; height: 350px; object-fit: cover; object-position: center; border-radius: 12px; display: block;',
                        ])
                        ->defaultImageUrl(asset('img/placeholders/store_placeholder.jpg')),
                    TextEntry::make('slug')
                        ->label('Slug'),
                    TextEntry::make('description')
                        ->label('Descripción'),
                ])->columnSpan([
                    'default' => 1,
                    'lg' => 2,
                ]),
                Section::make([
                    TextEntry::make('is_active')
                        ->label('Estado')
                        ->badge()
                        ->formatStateUsing(fn ($state): string => $state ? 'Activa' : 'Inactiva')
                        ->color(fn ($state): string => $state ? 'success' : 'danger'),
                    TextEntry::make('created_at')
                        ->label('Creado el')
                        ->since()
                        ->placeholder('-'),
                    TextEntry::make('updated_at')
                        ->label('Actualizado en')
                        ->since()
                        ->placeholder('-'),
                ])->columnSpan(1),

            ]);
    }
}
