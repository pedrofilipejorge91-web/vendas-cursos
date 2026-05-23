<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cupom extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'descricao',
        'tipo',
        'valor',
        'valido_ate',
        'limite_uso',
        'usos',
        'ativo',
    ];

    protected $casts = [
        'valido_ate' => 'date',
        'ativo' => 'boolean',
    ];

    public function estaDisponivel(): bool
    {
        if (! $this->ativo) {
            return false;
        }

        if ($this->valido_ate && $this->valido_ate->endOfDay()->isPast()) {
            return false;
        }

        if ($this->limite_uso !== null && $this->usos >= $this->limite_uso) {
            return false;
        }

        return true;
    }

    public function calcularDesconto(float $subtotal): float
    {
        if (! $this->estaDisponivel() || $subtotal <= 0) {
            return 0;
        }

        $desconto = $this->tipo === 'percentual'
            ? $subtotal * ((float) $this->valor / 100)
            : (float) $this->valor;

        return min($desconto, $subtotal);
    }
}
