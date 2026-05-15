<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Category extends Model
{
    use SoftDeletes, LogsActivity;

    // Note 1: fillable define los atributos que se pueden asignar en masa
    // Note 2: por ejemplo, cuando se usa Category::create([...]) Laravel solo
    // Note 3: escribira en DB las claves listadas aqui para evitar mass assignment
    // Note 4: esto agrega una capa de seguridad y tambien documenta el contrato
    // Note 5: de datos permitidos para crear o actualizar una categoria
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'display_order',
        'image_path'
    ];

    /**
     * Note 6: esta relacion es many-to-many entre categorias y tiendas
     * Note 7: Eloquent usa la tabla pivote category_store para resolver
     * Note 8: que tiendas pertenecen a esta categoria sin guardar un FK directo
     * Note 9: en la tabla categories
     *
     * @return BelongsToMany
     */
    public function stores(): BelongsToMany
    {
        // Note 10: belongsToMany devuelve un builder de relacion para poder
        // Note 11: consultar, adjuntar y sincronizar tiendas asociadas
        return $this->belongsToMany(Store::class, 'category_store');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('categorias')
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

}
