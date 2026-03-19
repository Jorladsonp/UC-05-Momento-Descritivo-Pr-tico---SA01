<?php

enum Prioridade: string
{
    case BAIXA   = 'Baixa';
    case MEDIA   = 'Média';
    case ALTA    = 'Alta';
    case CRITICA = 'Crítica';

    public function isUrgente(): bool
    {
        return $this === self::ALTA || $this === self::CRITICA;
    }
}
