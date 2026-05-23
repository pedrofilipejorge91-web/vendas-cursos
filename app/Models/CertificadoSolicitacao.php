<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CertificadoSolicitacao extends Model
{
    protected $table = 'certificado_solicitacoes';

    protected $fillable = [
        'matricula_id',
        'curso_id',
        'estudante_id',
        'instrutor_id',
        'nota_curso',
        'status',
        'observacoes_admin',
        'enviado_em',
        'decidido_em',
    ];

    protected $casts = [
        'enviado_em' => 'datetime',
        'decidido_em' => 'datetime',
        'nota_curso' => 'decimal:2',
    ];

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class);
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function estudante(): BelongsTo
    {
        return $this->belongsTo(Estudante::class);
    }

    public function instrutor(): BelongsTo
    {
        return $this->belongsTo(Formador::class, 'instrutor_id');
    }

    public function questionario(): HasMany
    {
        // pode haver mais de um se no futuro você permitir reenvio
        return $this->hasMany(CertificadoQuestionario::class, 'solicitacao_id');
    }
}

