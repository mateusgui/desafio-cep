<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cep extends Model
{
    /** @use HasFactory<\Database\Factories\CepFactory> */
    use HasFactory;

    protected $fillable = [
        'cep', 'logradouro', 'complemento', 'unidade', 'bairro', 'localidade',
        'uf', 'estado', 'regiao', 'ibge', 'ddd', 'gia', 'siafi', 'sincronizado_em',
    ];

    protected function casts(): array
    {
        return [
            'sincronizado_em' => 'datetime',
        ];
    }

    /**
     * Converte a resposta do ViaCEP nas colunas da tabela.
     * O ViaCEP devolve "79002-100"; no banco guardamos só os dígitos.
     */
    public static function atributosDoViaCep(array $dados): array
    {
        return [
            'cep' => preg_replace('/\D/', '', $dados['cep']),
            'logradouro' => $dados['logradouro'] ?: null,
            'complemento' => $dados['complemento'] ?? null ?: null,
            'unidade' => $dados['unidade'] ?? null ?: null,
            'bairro' => $dados['bairro'] ?: null,
            'localidade' => $dados['localidade'],
            'uf' => $dados['uf'],
            'estado' => $dados['estado'] ?? null,
            'regiao' => $dados['regiao'] ?? null,
            'ibge' => $dados['ibge'],
            'ddd' => $dados['ddd'] ?? null ?: null,
            'gia' => $dados['gia'] ?? null ?: null,
            'siafi' => $dados['siafi'] ?? null ?: null,
            'sincronizado_em' => now(),
        ];
    }

    /**
     * CEPs que começam com o prefixo, buscando como intervalo para usar o índice.
     * Ex.: '79002' => BETWEEN '79002000' AND '79002999'.
     */
    public function scopeDoPrefixo(Builder $query, string $prefixo): Builder
    {
        return $query->whereBetween('cep', [
            str_pad($prefixo, 8, '0'),
            str_pad($prefixo, 8, '9'),
        ]);
    }

    /**
     * Filtros opcionais e combináveis da listagem.
     */
    public function scopeFiltrar(Builder $query, array $filtros): Builder
    {
        return $query
            ->when($filtros['uf'] ?? null, fn ($q, $uf) => $q->where('uf', strtoupper($uf)))
            ->when($filtros['cidade'] ?? null, fn ($q, $cidade) => $q->where('localidade', $cidade))
            ->when($filtros['ibge'] ?? null, fn ($q, $ibge) => $q->where('ibge', $ibge))
            ->when($filtros['bairro'] ?? null, fn ($q, $bairro) => $q->where('bairro', $bairro))
            ->when($filtros['logradouro'] ?? null, fn ($q, $trecho) => $q->where('logradouro', 'like', "%{$trecho}%"))
            ->when($filtros['ddd'] ?? null, fn ($q, $ddd) => $q->where('ddd', $ddd));
    }
}