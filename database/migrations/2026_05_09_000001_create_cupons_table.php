<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cupons', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('descricao')->nullable();
            $table->enum('tipo', ['percentual', 'valor'])->default('percentual');
            $table->decimal('valor', 10, 2);
            $table->date('valido_ate')->nullable();
            $table->integer('limite_uso')->nullable();
            $table->integer('usos')->default(0);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cupons');
    }
};
