<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->cascadeOnDelete();
            $table->string('referencia')->unique();
            $table->enum('metodo', ['multicaixa_express', 'transferencia_bancaria', 'mbway_angola', 'pagamento_presencial']);
            $table->decimal('valor', 12, 2);
            $table->enum('status', ['pendente', 'confirmado', 'rejeitado', 'expirado'])->default('pendente');
            $table->string('telefone')->nullable();
            $table->string('comprovativo')->nullable();
            $table->timestamp('confirmado_em')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagamentos');
    }
};
