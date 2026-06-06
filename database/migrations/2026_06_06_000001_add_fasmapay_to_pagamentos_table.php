<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->json('gateway_payload')->nullable()->after('comprovativo');
        });

        DB::statement("ALTER TABLE pagamentos MODIFY metodo ENUM('multicaixa_express', 'transferencia_bancaria', 'fasmapay') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pagamentos MODIFY metodo ENUM('multicaixa_express', 'transferencia_bancaria') NOT NULL");

        Schema::table('pagamentos', function (Blueprint $table) {
            $table->dropColumn('gateway_payload');
        });
    }
};
