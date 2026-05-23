<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'curso_id',
        'pedido_id',
        'progresso',
        'concluido_em',
    ];

    protected $casts = [
        'concluido_em' => 'datetime',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function progressos()
    {
        return $this->hasMany(ProgressoAula::class);
    }

    public function certificado()
    {
        return $this->hasOne(Certificado::class);
    }
}
