<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'tipo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // RELAÇÃO COM PESSOA (1:1)
    public function pessoa()
    {
        return $this->hasOne(Pessoa::class);
    }

    // FORMADOR VIA PESSOA
    public function formador()
    {
        return $this->hasOneThrough(
            Formador::class,
            Pessoa::class
        );
    }

    public function notificacoes()
    {
        return $this->hasMany(Notificacao::class);
    }
}
