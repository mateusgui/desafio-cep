<?php

namespace App\Enums;

enum StatusFaixa: string
{
    case Pendente = 'pendente';
    case Processando = 'processando';
    case Concluida = 'concluida';
    case Erro = 'erro';

    /**
     * A faixa ainda está sendo sincronizada (job na fila ou rodando).
     */
    public function emAndamento(): bool
    {
        return in_array($this, [self::Pendente, self::Processando], true);
    }

    /**
     * Os status que contam como "em andamento", para usar em consultas (whereIn).
     */
    public static function valoresEmAndamento(): array
    {
        return [self::Pendente->value, self::Processando->value];
    }
}