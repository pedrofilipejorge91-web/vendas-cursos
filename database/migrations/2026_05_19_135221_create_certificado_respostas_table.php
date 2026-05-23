<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificado_respostas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('questionario_id')->constrained('certificado_questionarios')->cascadeOnDelete();
            $table->foreignId('estudante_id')->constrained('estudantes')->cascadeOnDelete();

            $table->text('respostas')->nullable(); // JSON/texto simples
            $table->timestamp('enviado_em')->nullable();

            $table->timestamps();

            $table->unique(['questionario_id', 'estudante_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificado_respostas');
    }
};

