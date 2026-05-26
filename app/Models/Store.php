<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Store extends Model
{
    use SoftDeletes, LogsActivity;

    // Note 1: fillable controla que campos se pueden escribir por asignacion masiva
    // Note 2: esto protege el modelo frente a entradas no deseadas desde formularios
    // Note 3: y deja explicito que atributos forman parte del ciclo normal de alta/edicion
    protected $fillable = [
        'name',
        'slug',
        'description',
        'address',
        'phone',
        'email',
        'website',
        'logo_path',
        'img_path',
        'status',
        'is_featured',
        'latitude',
        'longitude',
        'approval_status',
        'approval_date',
        'approval_user_id',
    ];

    public function scopePublicVisible(Builder $query): Builder
    {
        return $query
            ->where('status', 'approved');
    }

    // Note 4: una tienda puede ser aprobada por un usuario concreto
    // Note 5: esta relacion belongsTo apunta a users.id usando approval_user_id
    public function approvalUser()
    {
        return $this->belongsTo(User::class, 'approval_user_id');
    }

    // Note 6: una tienda puede pertenecer a muchas categorias
    // Note 7: y una categoria puede contener muchas tiendas
    // Note 8: por eso se modela con belongsToMany y una tabla pivote intermedia
    public function categories(): BelongsToMany
    {
        // Note 9: category_store conecta store_id con category_id
        // Note 10: aqui se centraliza la definicion de esa union para todo el dominio
        return $this->belongsToMany(Category::class, 'category_store');
    }

    public function getCoverUrlAttribute(): ?string
    {
        return $this->resolveMediaUrl($this->img_path);
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->resolveMediaUrl($this->logo_path);
    }

    public function getApprovalStatusBadgeColor(): string
    {
        return match ($this->approval_status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('tiendas')
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    private function resolveMediaUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '/'])) {
            return $path;
        }

        if (Str::startsWith($path, 'storage/')) {
            return asset($path);
        }

        return Storage::url($path);
    }
}
