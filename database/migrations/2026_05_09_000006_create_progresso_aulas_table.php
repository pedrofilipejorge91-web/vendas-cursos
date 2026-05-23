<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progresso_aulas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matricula_id')->constrained('matriculas')->cascadeOnDelete();
            $table->foreignId('aula_id')->constrained('aulas')->cascadeOnDelete();
            $table->timestamp('concluido_em')->nullable();
            $table->timestamps();

            $table->unique(['matricula_id', 'aula_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progresso_aulas');
    }
};
