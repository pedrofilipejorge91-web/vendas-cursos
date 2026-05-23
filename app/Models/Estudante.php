<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudante extends Model
{
    use HasFactory;

    protected $fillable = [
        'pessoa_id',
        'escola_actual',
        'localizacao',
        'area_interesse',
        'formacao',
        'status',
        'data_inscricao',
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }
    public function cursos()
{
    return $this->belongsToMany(
        Curso::class,
        'curso_estudante'
    )
    ->withPivot('status', 'data_inscricao')
    ->withTimestamps();
}
}
