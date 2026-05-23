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
        Schema::create('cursos', function (Blueprint $table) {
        $table->id();
        $table->string('titulo');
        $table->text('descricao')->nullable();
        $table->decimal('preco', 10, 2)->default(0.00);
        $table->integer('duracao_horas')->default(0);
        $table->enum('status', ['rascunho', 'publicado', 'inativo'])->default('rascunho');
        $table->string('foto')->nullable();

        $table->unsignedBigInteger('formador_id');
        $table->foreign('formador_id')->references('id')->on('formadors')->onDelete('cascade')->onUpdate('cascade');
        
        $table->unsignedBigInteger('categoria_id');
        $table->foreign('categoria_id')->references('id')->on('categorias')->onDelete('cascade')->onUpdate('cascade');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
