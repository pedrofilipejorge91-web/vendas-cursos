<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // O nome da tabela é explicitamente 'formadores' (plural em português)
        Schema::create('formadors', function (Blueprint $table) {
    $table->id();

    $table->foreignId('pessoa_id')
        ->constrained()
        ->onDelete('cascade');

    $table->string('especialidade')->nullable();
    $table->string('foto_perfil')->nullable();
    $table->text('biografia')->nullable();
    $table->integer('anos_experiencia')->default(0);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove a tabela se necessário (rollback)
        Schema::dropIfExists('formadors');
    }
};