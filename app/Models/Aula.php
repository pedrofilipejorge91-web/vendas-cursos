<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Curso;

class Aula extends Model
{
    use HasFactory;

    protected $fillable = [
   'titulo',
   'descricao',
   'tipo',
   'numero_aula',
   'duracao_minutos',
   'url_conteudo',
   'arquivo_video',
   'permite_download',
   'curso_id',

    ];

    protected $casts = [
      'permite_download' => 'boolean',
    ];
    public function curso(){
      return $this->belongsTo(curso::class, 'curso_id','id');  
    }
}
