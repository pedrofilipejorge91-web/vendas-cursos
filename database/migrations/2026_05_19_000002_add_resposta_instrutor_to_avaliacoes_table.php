<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('avaliacoes', function (Blueprint $table) {
            $table->text('resposta_instrutor')->nullable()->after('comentario');
            $table->timestamp('respondido_em')->nullable()->after('resposta_instrutor');
        });
    }

    public function down(): void
    {
        Schema::table('avaliacoes', function (Blueprint $table) {
            $table->dropColumn(['resposta_instrutor', 'respondido_em']);
        });
    }
};
