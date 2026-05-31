<?php

namespace App\Filament\Resources\Stores\Schemas;

use App\Models\Store;
use Fahiem\FilamentPinpoint\PinpointEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StoreInfolist
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
                        ->label('Nombre de la tienda'),

                    TextEntry::make('categories.name')
                        ->label('Categorías')
                        ->badge()
                        ->listWithLineBreaks()
                        ->placeholder('-'),

                    ImageEntry::make('logo_path')
                        ->label('Logo')
                        ->imageHeight(80)
                        ->circular()
                        ->defaultImageUrl(asset('img/placeholders/store_placeholder.jpg')),

                    ImageEntry::make('img_path')
                        ->label('Portada')
                        ->disk('public')
                        ->imageWidth('100%')
                        ->imageHeight('220px')
                        ->extraImgAttributes([
                            'style' => 'width: 100%; height: 220px; object-fit: cover; object-position: center; border-radius: 12px; display: block;',
                        ])
                        ->defaultImageUrl(asset('img/placeholders/store_placeholder.jpg')),

                    TextEntry::make('slug')
                        ->label('Slug')
                        ->placeholder('-'),

                    TextEntry::make('description')
                        ->label('Descripción')
                        ->placeholder('-'),

                    TextEntry::make('address')
                        ->label('Dirección')
                        ->placeholder('-'),

                    TextEntry::make('phone')
                        ->label('Teléfono')
                        ->placeholder('-'),

                    TextEntry::make('email')
                        ->label('Correo electrónico')
                        ->placeholder('-'),

                    TextEntry::make('website')
                        ->label('Sitio web')
                        ->placeholder('-')
                        ->url(fn (?string $state): ?string => filled($state) ? $state : null)
                        ->openUrlInNewTab(),
                ])->columnSpan([
                    'default' => 1,
                    'lg' => 2,
                ]),

                Section::make([
                    PinpointEntry::make('location')
                        ->label('Ubicación')
                        ->provider('leaflet')
                        ->latField('latitude')
                        ->lngField('longitude')
                        ->defaultLocation(-27.1605297, -56.2414071)
                        ->defaultZoom(15)
                        ->height(240)
                        ->columnSpanFull(),

                    TextEntry::make('address')
                        ->label('Dirección')
                        ->placeholder('-'),

                    TextEntry::make('status')
                        ->label('Estado')
                        ->badge()
                        ->formatStateUsing(fn (?string $state): string => match ($state) {
                            'approved' => 'Aprobado',
                            'pending' => 'Pendiente',
                            'rejected' => 'Rechazado',
                            'inactive' => 'Inactivo',
                            default => $state ?? '-',
                        })
                        ->color(fn (?string $state): string => match ($state) {
                            'approved' => 'success',
                            'pending' => 'warning',
                            'rejected' => 'danger',
                            'inactive' => 'gray',
                            default => 'gray',
                        }),

                    TextEntry::make('is_featured')
                        ->label('Es destacada')
                        ->badge()
                        ->formatStateUsing(fn (bool $state): string => $state ? 'Sí' : 'No')
                        ->color(fn (bool $state): string => $state ? 'warning' : 'gray'),

                    TextEntry::make('approval_date')
                        ->label('Fecha de aprobación')
                        ->since()
                        ->placeholder('-'),

                    TextEntry::make('created_at')
                        ->label('Creado el')
                        ->since()
                        ->placeholder('-'),

                    TextEntry::make('updated_at')
                        ->label('Actualizado en')
                        ->since()
                        ->placeholder('-'),

                    TextEntry::make('deleted_at')
                        ->label('Eliminado en')
                        ->since()
                        ->visible(fn (Store $record): bool => $record->trashed()),
                ])->columnSpan(1),
            ]);
    }
}
