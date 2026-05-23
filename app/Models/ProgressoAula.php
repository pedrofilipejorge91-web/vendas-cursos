<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressoAula extends Model
{
    use HasFactory;

    protected $table = 'progresso_aulas';

    protected $fillable = [
        'matricula_id',
        'aula_id',
        'concluido_em',
    ];

    protected $casts = [
        'concluido_em' => 'datetime',
    ];
}
