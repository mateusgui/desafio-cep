<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faixas_sincronizadas', function (Blueprint $table) {
            $table->id();
            $table->string('prefixo', 8)->unique();
            $table->string('status', 20)->default('pendente');
            $table->unsignedInteger('total_consultado')->default(0);
            $table->unsignedInteger('total_encontrado')->default(0);
            $table->timestamp('iniciado_em')->nullable();
            $table->timestamp('concluido_em')->nullable();
            $table->text('erro')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faixas_sincronizadas');
    }
};