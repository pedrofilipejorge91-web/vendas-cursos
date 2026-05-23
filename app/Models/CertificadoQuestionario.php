<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CertificadoQuestionario extends Model
{
    protected $table = 'certificado_questionarios';

    protected $fillable = [
        'matricula_id',
        'curso_id',
        'solicitacao_id',
        'perguntas',
        'criado_em',
        'fechado_em',
    ];

    protected $casts = [
        'criado_em' => 'datetime',
        'fechado_em' => 'datetime',
    ];

    public function solicitacao(): BelongsTo
    {
        return $this->belongsTo(CertificadoSolicitacao::class, 'solicitacao_id');
    }

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class, 'matricula_id');
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    public function respostas(): HasMany
    {
        return $this->hasMany(CertificadoResposta::class, 'questionario_id');
    }
}

