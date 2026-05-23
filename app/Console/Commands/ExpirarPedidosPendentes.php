<?php

namespace App\Console\Commands;

use App\Models\Pedido;
use Illuminate\Console\Command;

class ExpirarPedidosPendentes extends Command
{
    protected $signature = 'pedidos:expirar-pendentes';

    protected $description = 'Expira pedidos pendentes cujo prazo de pagamento terminou.';

    public function handle(): int
    {
        $pedidos = Pedido::with('pagamento')
            ->where('status', 'pendente')
            ->whereNotNull('expira_em')
            ->where('expira_em', '<=', now())
            ->get();

        foreach ($pedidos as $pedido) {
            $pedido->update(['status' => 'expirado']);
            $pedido->pagamento?->update(['status' => 'expirado']);
        }

        $this->info($pedidos->count().' pedido(s) expirado(s).');

        return self::SUCCESS;
    }
}
