<?php

namespace App\Filament\Resources\ActivityLog\Pages;

use App\Filament\Resources\ActivityLog\ActivityLogResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Spatie\Activitylog\Models\Activity;

class ViewActivityLog extends ViewRecord
{
    protected static string $resource = ActivityLogResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
        ->columns([
                'default' => 1,
                'lg' => 3,
            ])
        ->components([
            Grid::make()->schema([
                // Columna izquierda: datos generales
                Section::make('Información del evento')
                    ->columnSpan(3)
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Fecha y hora')
                            ->dateTime('d/m/Y H:i:s'),

                        TextEntry::make('log_name')
                            ->label('Módulo')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'tiendas'    => 'warning',
                                'categorias' => 'success',
                                'usuarios'   => 'info',
                                default      => 'gray',
                            }),

                        TextEntry::make('description')
                            ->label('Evento')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'created' => 'success',
                                'updated' => 'warning',
                                'deleted' => 'danger',
                                default   => 'gray',
                            }),

                        TextEntry::make('subject_type')
                            ->label('Tipo de registro'),

                        TextEntry::make('subject_id')
                            ->label('ID del registro'),

                        TextEntry::make('causer.name')
                            ->label('Modificado por')
                            ->default('Sistema'),

                        TextEntry::make('causer.email')
                            ->label('Email del usuario')
                            ->default('-'),
                    ]),

                    ]),
                    // Columna central: valores anteriores
                    Section::make('Valores anteriores')
                        ->schema([
                            TextEntry::make('old_values')
                                ->label('')
                                ->state(fn (Activity $record): string => self::formatSection($record, 'old'))
                                ->html()
                                ->columnSpanFull(),
                        ]),

                    // Columna derecha: valores nuevos
                    Section::make('Valores nuevos')
                        ->schema([
                            TextEntry::make('new_values')
                                ->label('')
                                ->state(fn (Activity $record): string => self::formatSection($record, 'attributes'))
                                ->html()
                                ->columnSpanFull(),
                        ]),
        ]);
    }

    private static function formatSection(Activity $record, string $key): string
    {
        $data = self::getChangeSet($record, $key);

        if (empty($data) || !is_array($data)) {
            return '<span class="text-gray-400 text-sm italic">Sin datos</span>';
        }

        $rows = '';
        foreach ($data as $field => $value) {
            $displayValue = is_array($value)
                ? '<code class="text-xs bg-gray-100 dark:bg-gray-800 rounded px-1">' . e(json_encode($value, JSON_UNESCAPED_UNICODE)) . '</code>'
                : '<span>' . e((string) $value) . '</span>';

            $rows .= "<tr class='border-b border-gray-100 dark:border-gray-700'>
                <td class='py-1 pr-3 font-medium text-sm text-gray-600 dark:text-gray-300 whitespace-nowrap'>" . e($field) . "</td>
                <td class='py-1 text-sm text-gray-800 dark:text-gray-100 break-all'>{$displayValue}</td>
            </tr>";
        }

        return "<table class='w-full text-left'><tbody>{$rows}</tbody></table>";
    }

    private static function getChangeSet(Activity $record, string $key): ?array
    {
        $data = data_get($record, "attribute_changes.{$key}");

        if ($data === null) {
            $data = data_get($record, "properties.{$key}");
        }

        return is_array($data) ? $data : null;
    }
}
