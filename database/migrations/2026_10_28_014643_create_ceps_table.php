<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ceps', function (Blueprint $table) {
            $table->id();
            $table->char('cep', 8)->unique();
            $table->string('logradouro')->nullable();
            $table->string('complemento')->nullable();
            $table->string('unidade')->nullable();
            $table->string('bairro')->nullable();
            $table->string('localidade');
            $table->char('uf', 2);
            $table->string('estado', 50)->nullable();
            $table->string('regiao', 20)->nullable();
            $table->char('ibge', 7);
            $table->char('ddd', 2)->nullable();
            $table->string('gia', 10)->nullable();
            $table->string('siafi', 10)->nullable();
            $table->timestamp('sincronizado_em');
            $table->timestamps();

            $table->index(['uf', 'localidade']);
            $table->index('bairro');
            $table->index('ibge');
            $table->index('ddd');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ceps');
    }
};