<?php

namespace App\Filament\Resources\ActivityLog;

use App\Filament\Resources\ActivityLog\Pages\ListActivityLogs;
use App\Filament\Resources\ActivityLog\Pages\ViewActivityLog;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;
use Filament\Forms\Components\DatePicker;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Auditoría';

    protected static ?string $pluralModelLabel = 'Registros de auditoría';

    protected static ?string $modelLabel = 'Auditoría';

    protected static string|\UnitEnum|null $navigationGroup = 'Auditoría';

    protected static ?int $navigationSort = 1;

    public static function table(Table $table): Table
    {
        return $table
            ->query(Activity::query()->with(['causer', 'subject'])->latest())
            ->columns([
                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->since()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('causer.name')
                    ->label('Usuario')
                    ->default('Sistema')
                    ->searchable(),

                TextColumn::make('log_name')
                    ->label('Módulo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'tiendas'    => 'warning',
                        'categorias' => 'success',
                        'usuarios'   => 'info',
                        default      => 'gray',
                    })
                    ->searchable(),

                TextColumn::make('description')
                    ->label('Evento')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        default   => 'gray',
                    }),

                // ID del registro afectado
                TextColumn::make('subject_id')
                    ->label('ID registro')
                    ->sortable(),

                TextColumn::make('properties')
                    ->label('Valores anteriores')
                    ->state(fn (Activity $record): string => self::formatProperties($record, 'old'))
                    ->wrap()
                    ->limit(200)
                    ->tooltip(fn (Activity $record): ?string => self::formatProperties($record, 'old', full: true)),

                TextColumn::make('properties_new')
                    ->label('Valores nuevos')
                    ->state(fn (Activity $record): string => self::formatProperties($record, 'attributes'))
                    ->wrap()
                    ->limit(200)
                    ->tooltip(fn (Activity $record): ?string => self::formatProperties($record, 'attributes', full: true)),
            ])
            ->filters([
                SelectFilter::make('log_name')
                    ->label('Módulo')
                    ->options([
                        'tiendas'    => 'Tiendas',
                        'categorias' => 'Categorías',
                        'usuarios'   => 'Usuarios',
                    ]),

                SelectFilter::make('description')
                    ->label('Evento')
                    ->options([
                        'created' => 'Creado',
                        'updated' => 'Actualizado',
                        'deleted' => 'Eliminado',
                    ]),

                SelectFilter::make('causer_id')
                    ->label('Usuario')
                    ->options(fn (): array => User::query()->pluck('name', 'id')->toArray())
                    ->searchable(),

                Filter::make('created_at')
                    ->label('Rango de fechas')
                    ->schema([
                        DatePicker::make('from')->label('Desde'),
                        DatePicker::make('until')->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'],  fn (Builder $q) => $q->whereDate('created_at', '>=', $data['from']))
                            ->when($data['until'], fn (Builder $q) => $q->whereDate('created_at', '<=', $data['until']));
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->recordAction('view');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivityLogs::route('/'),
            'view'  => ViewActivityLog::route('/{record}'),
        ];
    }

    /**
     * Formatea el array old/attributes de los cambios del registro en texto legible.
     */
    private static function formatProperties(Activity $record, string $key, bool $full = false): string
    {
        $data = self::getChangeSet($record, $key);

        if (empty($data) || !is_array($data)) {
            return '-';
        }

        $lines = [];
        foreach ($data as $field => $value) {
            $displayValue = is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value;
            $lines[] = "{$field}: {$displayValue}";
        }

        $text = implode("\n", $lines);

        if (!$full && strlen($text) > 200) {
            return substr($text, 0, 200) . '…';
        }

        return $text;
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
