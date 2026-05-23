<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedido_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->cascadeOnDelete();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->string('titulo');
            $table->decimal('preco', 12, 2);
            $table->integer('quantidade')->default(1);
            $table->timestamps();

            $table->unique(['pedido_id', 'curso_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_items');
    }
};
