<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lote extends Model
{
    protected $fillable = [
        'nombre',
        'direccion',
        'identificador'
    ];

    /**
     * Relación: Un Lote tiene muchos Vendedores.
     */
    public function vendedores(): HasMany
    {
        return $this->hasMany(Vendedor::class, 'lote_id');
    }
}