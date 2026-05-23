<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
    use HasFactory;

    protected $table = 'avaliacoes';

    protected $fillable = [
        'nota',
        'comentario',
        'resposta_instrutor',
        'respondido_em',
        'curso_id',
        'estudante_id',
    ];

    protected $casts = [
        'respondido_em' => 'datetime',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function estudante()
    {
        return $this->belongsTo(Estudante::class);
    }
}
