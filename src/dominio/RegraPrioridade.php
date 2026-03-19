<?php

require_once __DIR__ . '/Solicitacao.php';

interface RegraPrioridade
{
    public function definirPrioridade(Solicitacao $solicitacao): Prioridade;
    public function descricaoRegra(): string;
}
