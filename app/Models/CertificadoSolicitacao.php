<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CertificadoSolicitacao extends Model
{
    public const STATUS_AGUARDANDO_QUESTIONARIO = 'aguardando_questionario';
    public const STATUS_AGUARDANDO_RESPOSTA = 'aguardando_resposta';
    public const STATUS_AGUARDANDO_CORRECAO = 'aguardando_correcao';
    public const STATUS_AGUARDANDO_ADMIN = 'aguardando_admin';
    public const STATUS_APROVADO = 'aprovado';
    public const STATUS_REJEITADO = 'rejeitado';

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

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_AGUARDANDO_QUESTIONARIO => 'Aguardando questionario',
            self::STATUS_AGUARDANDO_RESPOSTA => 'Aguardando resposta do aluno',
            self::STATUS_AGUARDANDO_CORRECAO => 'Aguardando correcao do formador',
            self::STATUS_AGUARDANDO_ADMIN, 'pendente' => 'Aguardando aprovacao do admin',
            self::STATUS_APROVADO => 'Aprovado',
            self::STATUS_REJEITADO => 'Rejeitado',
            default => ucfirst((string) $this->status),
        };
    }

    public function statusBadge(): string
    {
        return match ($this->status) {
            self::STATUS_APROVADO => 'success',
            self::STATUS_REJEITADO => 'danger',
            self::STATUS_AGUARDANDO_ADMIN, 'pendente' => 'warning',
            self::STATUS_AGUARDANDO_CORRECAO => 'info',
            default => 'secondary',
        };
    }
}

