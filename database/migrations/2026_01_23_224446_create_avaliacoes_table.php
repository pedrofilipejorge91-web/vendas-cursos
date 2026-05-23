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
        Schema::create('avaliacoes', function (Blueprint $table) {
        $table->id();
        $table->tinyInteger('nota')->unsigned()->checkBetween(1, 5);
        $table->text('comentario')->nullable();
        $table->timestamps();
         $table->unsignedBigInteger('curso_id');
        $table->foreign('curso_id')->references('id')->on('cursos')->onDelete('cascade')->onUpdate('cascade');
        $table->unsignedBigInteger('estudante_id');
        $table->foreign('estudante_id')->references('id')->on('estudantes')->onDelete('cascade')->onUpdate('cascade');
        $table->unique(['estudante_id', 'curso_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avaliacoes');
    }
};
