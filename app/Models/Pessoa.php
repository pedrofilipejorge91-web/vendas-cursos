<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'primeironome',
        'segundonome',
        'BI',
        'genero',
        'nacionalidade',
        'data_nascimento',
        'rua',
        'bairro',
        'contacto',
    ];

    // pertence ao user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // tem formador
    public function formador()
    {
        return $this->hasOne(Formador::class);
    }

    public function estudante()
{
    return $this->hasOne(Estudante::class);
}
}