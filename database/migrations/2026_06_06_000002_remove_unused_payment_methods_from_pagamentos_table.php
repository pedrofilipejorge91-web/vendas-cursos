<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('pagamentos')
            ->whereIn('metodo', ['mbway_angola', 'pagamento_presencial'])
            ->update(['metodo' => 'transferencia_bancaria']);

        DB::statement("ALTER TABLE pagamentos MODIFY metodo ENUM('multicaixa_express', 'transferencia_bancaria', 'fasmapay') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pagamentos MODIFY metodo ENUM('multicaixa_express', 'transferencia_bancaria', 'fasmapay', 'mbway_angola', 'pagamento_presencial') NOT NULL");
    }
};
