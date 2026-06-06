<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'pedido_id',
        'referencia',
        'metodo',
        'valor',
        'status',
        'telefone',
        'comprovativo',
        'gateway_payload',
        'confirmado_em',
    ];

    protected $casts = [
        'gateway_payload' => 'array',
        'confirmado_em' => 'datetime',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }
}
