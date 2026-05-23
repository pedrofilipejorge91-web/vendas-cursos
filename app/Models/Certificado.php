<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificado extends Model
{
    use HasFactory;

    protected $fillable = [
        'matricula_id',
        'codigo',
        'emitido_em',
    ];

    protected $casts = [
        'emitido_em' => 'datetime',
    ];

    public function matricula()
    {
        return $this->belongsTo(Matricula::class);
    }
}
