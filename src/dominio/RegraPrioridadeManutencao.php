<?php

require_once __DIR__ . '/RegraPrioridade.php';
require_once __DIR__ . '/Prioridade.php';

class RegraPrioridadeManutencao implements RegraPrioridade
{
    public function definirPrioridade(Solicitacao $s): Prioridade
    {
        $desc   = strtolower($s->getDescricao());
        $titulo = strtolower($s->getTitulo());

        $riscoSeguranca   = str_contains($desc, 'vazamento') || str_contains($desc, 'curto') || str_contains($desc, 'incêndio') || str_contains($titulo, 'emergência');
        $producaoParada   = str_contains($desc, 'produção parada') || str_contains($desc, 'linha parada') || str_contains($desc, 'equipamento parado');
        $degradacao       = str_contains($desc, 'barulho') || str_contains($desc, 'aquecendo') || str_contains($desc, 'instável');

        if ($riscoSeguranca) return Prioridade::CRITICA;
        if ($producaoParada) return Prioridade::ALTA;
        if ($degradacao)     return Prioridade::MEDIA;
        return Prioridade::BAIXA;
    }

    public function descricaoRegra(): string
    {
        return "Regra Manutenção: prioriza riscos à segurança e paradas de produção.";
    }
}
