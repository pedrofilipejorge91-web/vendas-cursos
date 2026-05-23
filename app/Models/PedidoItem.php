<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'pedido_id',
        'curso_id',
        'titulo',
        'preco',
        'quantidade',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }
}
