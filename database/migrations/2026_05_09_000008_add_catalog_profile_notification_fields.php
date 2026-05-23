<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cursos', function (Blueprint $table) {
            $table->string('idioma')->default('pt-AO')->after('duracao_horas');
        });

        Schema::table('estudantes', function (Blueprint $table) {
            $table->string('localizacao')->nullable()->after('escola_actual');
            $table->string('area_interesse')->nullable()->after('localizacao');
            $table->string('formacao')->nullable()->after('area_interesse');
        });

        Schema::create('notificacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('canal')->default('email');
            $table->string('titulo');
            $table->text('mensagem');
            $table->timestamp('lida_em')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificacoes');

        Schema::table('estudantes', function (Blueprint $table) {
            $table->dropColumn(['localizacao', 'area_interesse', 'formacao']);
        });

        Schema::table('cursos', function (Blueprint $table) {
            $table->dropColumn('idioma');
        });
    }
};
