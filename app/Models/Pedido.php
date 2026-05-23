<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cupom_id',
        'referencia',
        'subtotal',
        'desconto',
        'total',
        'status',
        'expira_em',
    ];

    protected $casts = [
        'expira_em' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function itens()
    {
        return $this->hasMany(PedidoItem::class);
    }

    public function pagamento()
    {
        return $this->hasOne(Pagamento::class);
    }
}
