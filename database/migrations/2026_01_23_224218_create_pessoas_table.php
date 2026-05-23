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
       Schema::create('pessoas', function (Blueprint $table) {
    $table->id();

    $table->foreignId('user_id')
        ->constrained()
        ->onDelete('cascade');

    $table->string('primeironome');
    $table->string('segundonome');
    $table->string('BI')->unique()->nullable();
    $table->enum('genero', ['M', 'F'])->nullable();
    $table->string('nacionalidade')->nullable();
    $table->date('data_nascimento')->nullable();
    $table->string('rua')->nullable();
    $table->string('bairro')->nullable();
    $table->string('contacto')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pessoas');
    }
};
