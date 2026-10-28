<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ViaCepClient
{
    /**
     * Busca um CEP completo (8 dígitos) no ViaCEP.
     * Retorna os dados do endereço, ou null se o CEP não existir.
     * Lança exceção se o ViaCEP estiver fora do ar, para o job tentar de novo.
     */
    public function buscar(string $cep): ?array
    {
        $resposta = Http::baseUrl(config('viacep.base_url'))
            ->timeout(config('viacep.timeout'))
            ->retry(2, config('viacep.retry_ms'), throw: false)
            ->get("/{$cep}/json/");

        if ($resposta->serverError()) {
            $resposta->throw();
        }

        if ($resposta->failed() || $resposta->json('erro')) {
            return null;
        }

        return $resposta->json();
    }
}