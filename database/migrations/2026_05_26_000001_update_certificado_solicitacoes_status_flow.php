<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('certificado_solicitacoes')) {
            return;
        }

        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE certificado_solicitacoes MODIFY status ENUM('pendente','aguardando_questionario','aguardando_resposta','aguardando_correcao','aguardando_admin','aprovado','rejeitado') NOT NULL DEFAULT 'aguardando_questionario'");
            DB::table('certificado_solicitacoes')
                ->where('status', 'pendente')
                ->whereNull('nota_curso')
                ->update(['status' => 'aguardando_questionario']);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('certificado_solicitacoes')) {
            return;
        }

        if (DB::getDriverName() === 'mysql') {
            DB::table('certificado_solicitacoes')
                ->whereNotIn('status', ['pendente', 'aprovado', 'rejeitado'])
                ->update(['status' => 'pendente']);

            DB::statement("ALTER TABLE certificado_solicitacoes MODIFY status ENUM('pendente','aprovado','rejeitado') NOT NULL DEFAULT 'pendente'");
        }
    }
};
