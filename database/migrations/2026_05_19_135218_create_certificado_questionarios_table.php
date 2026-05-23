<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificado_questionarios', function (Blueprint $table) {
            $table->id();

            $table->foreignId('matricula_id')->constrained('matriculas')->cascadeOnDelete();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->foreignId('solicitacao_id')->constrained('certificado_solicitacoes')->cascadeOnDelete();

            $table->text('perguntas')->nullable(); // JSON/texto simples

            $table->timestamp('criado_em')->nullable();
            $table->timestamp('fechado_em')->nullable();

            $table->timestamps();

            $table->unique(['matricula_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificado_questionarios');
    }
};

