<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Curso;
use App\Models\Formador;

class Curso extends Model
{

    protected $fillable = [
        'titulo',
        'created_at',
        'descricao',
        'formador_id',
        'preco',
        'status',
        'foto',
        'duracao_horas',
        'idioma',
        'categoria_id',
    ];

// Relacionamentos - segunda referência deve ser 'id' (PK da tabela relacionada)
public function formador()
{
    // ✅ belongsTo(Model, foreign_key_no_curso, primary_key_na_tabela_formador)
    return $this->belongsTo(Formador::class, 'formador_id', 'id');
}

public function categoria()
{
    return $this->belongsTo(Categoria::class, 'categoria_id', 'id');
}
public function inscricoes()
{
    return $this->hasMany(\App\Models\Inscricao::class);
}
 
public function aulas()
{
    return $this->hasMany(\App\Models\Aula::class);
}
public function matriculas()
{
    return $this->hasMany(Matricula::class);
}

public function avaliacoes()
{
    return $this->hasMany(Avaliacao::class);
}
 
public function estudantes()
{
    return $this->belongsToMany(
        Estudante::class,
        'curso_estudante'
    )
    ->withPivot('status', 'data_inscricao')
    ->withTimestamps();
}
 
}
