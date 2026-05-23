<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificadoResposta extends Model
{
    protected $table = 'certificado_respostas';

    protected $fillable = [
        'questionario_id',
        'estudante_id',
        'respostas',
        'enviado_em',
    ];

    protected $casts = [
        'enviado_em' => 'datetime',
    ];

    public function questionario(): BelongsTo
    {
        return $this->belongsTo(CertificadoQuestionario::class, 'questionario_id');
    }

    public function estudante(): BelongsTo
    {
        return $this->belongsTo(Estudante::class, 'estudante_id');
    }
}

