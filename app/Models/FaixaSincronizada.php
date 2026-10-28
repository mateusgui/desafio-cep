<?php

namespace App\Models;

use App\Enums\StatusFaixa;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FaixaSincronizada extends Model
{
    public const TAMANHO_MINIMO = 5;

    protected $table = 'faixas_sincronizadas';

    protected $fillable = [
        'prefixo', 'status', 'total_consultado', 'total_encontrado',
        'iniciado_em', 'concluido_em', 'erro',
    ];

    protected function casts(): array
    {
        return [
            'status' => StatusFaixa::class,
            'iniciado_em' => 'datetime',
            'concluido_em' => 'datetime',
        ];
    }

    /**
     * Todos os prefixos que cobrem o prefixo informado.
     * Ex.: '7900215' => ['79002', '790021', '7900215'].
     */
    public static function prefixosQueCobrem(string $prefixo): array
    {
        if (strlen($prefixo) < self::TAMANHO_MINIMO) {
            return [];
        }

        return array_map(
            fn (int $tamanho) => substr($prefixo, 0, $tamanho),
            range(self::TAMANHO_MINIMO, strlen($prefixo))
        );
    }

    /**
     * Faixas que cobrem o prefixo (ele mesmo ou um prefixo menor que o contém).
     */
    public function scopeCobrindo(Builder $query, string $prefixo): Builder
    {
        return $query->whereIn('prefixo', self::prefixosQueCobrem($prefixo));
    }
}