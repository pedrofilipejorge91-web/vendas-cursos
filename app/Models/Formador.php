<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formador extends Model
{
    use HasFactory;

    protected $table = 'formadors';

    protected $fillable = [
        'pessoa_id',
        'especialidade',
        'foto_perfil',
        'biografia',
        'anos_experiencia',
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function cursos()
    {
        return $this->hasMany(Curso::class);
    }
}