<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    use HasFactory;

    protected $table = 'vendedores';

    protected $fillable = [
        'nombre',
        'username',
        'email',
        'telefono',
        'website',
        'calle',
        'ciudad',
        'zipcode',
        'latitud',
        'longitud',
        'empresa_nombre',
        'lote_id'
    ];

    public function lote()
    {
        return $this->belongsTo(Lote::class);
    }
}