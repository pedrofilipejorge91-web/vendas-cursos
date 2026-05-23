<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificado_solicitacoes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('matricula_id')->constrained('matriculas')->cascadeOnDelete();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->foreignId('estudante_id')->constrained('estudantes')->cascadeOnDelete();

            // Instrutor (dono do curso)
            $table->foreignId('instrutor_id')->constrained('formadors')->cascadeOnDelete();

            $table->decimal('nota_curso', 5, 2)->nullable();

            $table->enum('status', ['pendente', 'aprovado', 'rejeitado'])->default('pendente');
            $table->text('observacoes_admin')->nullable();

            $table->timestamp('enviado_em')->nullable();
            $table->timestamp('decidido_em')->nullable();
            $table->timestamps();

            $table->unique(['matricula_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificado_solicitacoes');
    }
};

