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
        'termos_aceites_em',
        'privacidade_aceite_em',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'termos_aceites_em' => 'datetime',
        'privacidade_aceite_em' => 'datetime',
        'password' => 'hashed',
    ];

    // RELAÇÃO COM PESSOA (1:1)
    public function pessoa()
    {
        return $this->hasOne(Pessoa::class);
    }

    public function getNomeCompletoAttribute(): string
    {
        $nomePessoa = trim(collect([
            $this->pessoa?->primeironome,
            $this->pessoa?->segundonome,
        ])->filter()->implode(' '));

        return $nomePessoa !== '' ? $nomePessoa : ($this->name ?? 'Utilizador');
    }

    public function getInicialNomeAttribute(): string
    {
        return strtoupper(substr($this->nome_completo, 0, 1));
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
