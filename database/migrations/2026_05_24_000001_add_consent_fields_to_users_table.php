<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('termos_aceites_em')->nullable()->after('tipo');
            $table->timestamp('privacidade_aceite_em')->nullable()->after('termos_aceites_em');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['termos_aceites_em', 'privacidade_aceite_em']);
        });
    }
};
