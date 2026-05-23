<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('curso_estudante', function (Blueprint $table) {

            $table->id();

            $table->foreignId('curso_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('estudante_id')
                ->constrained()
                ->onDelete('cascade');

            $table->date('data_inscricao')->nullable();

            $table->enum('status', [
                'activo',
                'concluido',
                'cancelado'
            ])->default('activo');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curso_estudante');
    }
};
