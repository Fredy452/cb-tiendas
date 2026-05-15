<?php

namespace App\Filament\Resources\Stores\Schemas;

use App\Models\Store;
use Fahiem\FilamentPinpoint\Pinpoint;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Group;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class StoreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Tienda')
                            ->description('Informacion principal de la tienda')
                            ->icon('heroicon-o-building-storefront')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nombre')
                                    ->columnSpanFull()
                                    ->live(debounce: 500)
                                    ->afterStateUpdated(
                                        fn (callable $set, ?string $state) =>
                                        $set('slug', str()->slug($state))
                                    )
                                    ->required(),

                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->required(true)
                                    ->columnSpanFull()
                                    ->dehydrated(true)
                                    ->unique(Store::class, 'slug', ignoreRecord: true),

                                Select::make('categories')
                                    ->relationship('categories', 'name')
                                    ->required(true)
                                    ->searchable()
                                    ->label('Seleccionar categorias')
                                    ->columnSpanFull()
                                    ->preload()
                                    ->multiple(),

                                RichEditor::make('description')
                                    ->label('Descripción')
                                    ->columnSpanFull()
                                    ->required(true),

                                TextInput::make('phone')
                                    ->label('Teléfono')
                                    ->tel(),

                                TextInput::make('email')
                                    ->label('Correo electrónico')
                                    ->email(),

                                TextInput::make('website')
                                    ->label('Sitio web')
                                    ->url(),
                            ])
                            ->columns(2),
                Section::make('Imagenes')
                    ->description('Favor agregar un logo y una imagen de portada para la tienda')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        FileUpload::make('logo_path')
                            ->label('Logo')
                            ->directory('store-logos')
                            ->acceptedFileTypes(['image/*'])
                            ->disk('public')
                            ->image()
                            ->getUploadedFileNameForStorageUsing(
                                fn (TemporaryUploadedFile $file, $record = null): string => sprintf(
                                    'store-logo-%s-%s.%s',
                                    $record?->getKey() ?? 'new',
                                    now()->format('Ymd-His'),
                                    $file->getClientOriginalExtension(),
                                ),
                            ),

                        FileUpload::make('img_path')
                            ->label('Imagen de portada')
                            ->directory('store-images')
                            ->acceptedFileTypes(['image/*'])
                            ->disk('public')
                            ->image()
                            ->getUploadedFileNameForStorageUsing(
                                fn (TemporaryUploadedFile $file, $record = null): string => sprintf(
                                    'store-image-%s-%s.%s',
                                    $record?->getKey() ?? 'new',
                                    now()->format('Ymd-His'),
                                    $file->getClientOriginalExtension(),
                                ),
                            ),
                    ]),
                ]),

                Group::make()
                    ->schema([
                        Section::make('Ubicación')
                            ->description('Busca la dirección, mueve el pin o haz clic en el mapa para fijar la tienda')
                            ->icon('heroicon-o-map-pin')
                            ->schema([
                                Pinpoint::make('location')
                                    ->label('Mapa de la tienda')
                                    ->provider('leaflet')
                                    ->defaultLocation(-27.1605297, -56.2414071)
                                    ->defaultZoom(15)
                                    ->height(420)
                                    ->searchable()
                                    ->draggable()
                                    ->latField('latitude')
                                    ->lngField('longitude')
                                    ->addressField('address')
                                    ->dehydrated(false)
                                    ->columnSpanFull(),

                                TextInput::make('address')
                                    ->label('Dirección')
                                    ->columnSpanFull(),

                                TextInput::make('latitude')
                                    ->label('Latitud')
                                    ->readOnly(),

                                TextInput::make('longitude')
                                    ->label('Longitud')
                                    ->readOnly(),
                            ])
                            ->columns(2),

                        Section::make('Configuración')
                            ->schema([
                            // TODO: Deberia ser un campo tipo enum con opciones 'pending', 'approved', 'rejected'
                            // y un badge con colores segun el estado
                                Select::make('status')
                                    ->label('Estado')
                                    ->options([
                                        'pending' => 'Pendiente',
                                        'approved' => 'Aprobada',
                                        'rejected' => 'Rechazada',
                                        'active' => 'Activa',
                                        'inactive' => 'Inactiva',
                                    ])
                                    ->required()
                                    ->default('active'),

                                Toggle::make('is_featured')
                                    ->label('Es destacada')
                                    ->required(),

                            ])
                    ])
                    ->columns(1),
            ]);
    }
}
