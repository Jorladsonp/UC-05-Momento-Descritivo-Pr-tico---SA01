<?php

require_once __DIR__ . '/RegraPrioridade.php';
require_once __DIR__ . '/Prioridade.php';

class RegraPrioridadeGeral implements RegraPrioridade
{
    public function definirPrioridade(Solicitacao $s): Prioridade
    {
        $desc   = strtolower($s->getDescricao());
        $titulo = strtolower($s->getTitulo());

        if (str_contains($desc, 'urgente') || str_contains($desc, 'parado') || str_contains($titulo, 'urgente')) {
            return Prioridade::ALTA;
        }
        if (str_contains($desc, 'lento') || str_contains($desc, 'dificuldade') || strlen($desc) >= 100) {
            return Prioridade::MEDIA;
        }
        return Prioridade::BAIXA;
    }

    public function descricaoRegra(): string
    {
        return "Regra Geral: analisa palavras-chave no título e descrição.";
    }
}
