<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('avaliacoes')) {
            return;
        }

        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            $this->dropMysqlNotaChecks();
            DB::statement('ALTER TABLE avaliacoes MODIFY nota TINYINT UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE avaliacoes ADD CONSTRAINT avaliacoes_nota_0_10_check CHECK (nota BETWEEN 0 AND 10)');
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE avaliacoes DROP CONSTRAINT IF EXISTS avaliacoes_nota_check');
            DB::statement('ALTER TABLE avaliacoes ADD CONSTRAINT avaliacoes_nota_check CHECK (nota BETWEEN 0 AND 10)');
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('avaliacoes')) {
            return;
        }

        DB::table('avaliacoes')->where('nota', '>', 5)->update(['nota' => 5]);

        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            $this->dropMysqlNotaChecks();
            DB::statement('ALTER TABLE avaliacoes MODIFY nota TINYINT UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE avaliacoes ADD CONSTRAINT avaliacoes_nota_1_5_check CHECK (nota BETWEEN 1 AND 5)');
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE avaliacoes DROP CONSTRAINT IF EXISTS avaliacoes_nota_check');
            DB::statement('ALTER TABLE avaliacoes ADD CONSTRAINT avaliacoes_nota_check CHECK (nota BETWEEN 1 AND 5)');
        }
    }

    private function dropMysqlNotaChecks(): void
    {
        $constraints = DB::select("
            SELECT tc.CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS tc
            JOIN information_schema.CHECK_CONSTRAINTS cc
                ON cc.CONSTRAINT_SCHEMA = tc.CONSTRAINT_SCHEMA
                AND cc.CONSTRAINT_NAME = tc.CONSTRAINT_NAME
            WHERE tc.CONSTRAINT_SCHEMA = DATABASE()
                AND tc.TABLE_NAME = 'avaliacoes'
                AND tc.CONSTRAINT_TYPE = 'CHECK'
                AND cc.CHECK_CLAUSE LIKE '%nota%'
        ");

        foreach ($constraints as $constraint) {
            $name = str_replace('`', '``', $constraint->CONSTRAINT_NAME);
            DB::statement("ALTER TABLE avaliacoes DROP CHECK `{$name}`");
        }
    }
};
