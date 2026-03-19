# SA1 – Módulo OO para Controle de Solicitações Internas

**UC:** Programação de Aplicativos
**Tema em foco:** Programação Orientada a Objetos (POO)
**Turma/Turno:** [TEC.00076]
**Data:** [18/03/2026]

## 1. Objetivo da entrega

Entregar um módulo (core) para registro e acompanhamento de solicitações internas de uma planta industrial, modelado com Programação Orientada a Objetos em PHP, permitindo evoluções futuras (novas categorias, novas regras de prioridade e novos perfis de atendimento) com baixo impacto no código existente.

## 2. Tecnologias e requisitos

- Linguagem: PHP 8.1+
- Execução: Console (menu interativo) via `src/app/main.php`
- Sem dependências externas (apenas PHP puro)

## 3. Como executar

1. Confirmar que o PHP 8.1 ou superior está instalado:
   ```bash
   php -v
   ```
2. Navegar até a raiz do projeto:
   ```bash
   cd SA1_ModuloOO_Solicitacoes
   ```
3. Executar o sistema:
   ```bash
   php src/app/main.php
   ```
4. Resultado esperado: menu interativo no terminal com as opções de cadastro, atendimento e consulta de solicitações.

## 4. Estrutura do projeto

```
SA1_ModuloOO_Solicitacoes/
├── src/
│   ├── app/
│   │   └── main.php               (ponto de entrada / menu interativo)
│   └── dominio/
│       ├── Atendente.php
│       ├── Categoria.php
│       ├── Prioridade.php
│       ├── RegraPrioridade.php
│       ├── RegraPrioridadeGeral.php
│       ├── RegraPrioridadeManutencao.php
│       ├── RepositorioSolicitacao.php
│       ├── Solicitacao.php
│       ├── Solicitante.php
│       ├── StatusSolicitacao.php
│       └── Usuario.php
├── docs/
│   ├── README.md
│   └── DECISOES_DE_MODELAGEM.md
└── evidencias/
    ├── execucao_01.png
    ├── execucao_02.png
    ├── execucao_03.png
    ├── execucao_04.png
    ├── execucao_05.png
    └── execucao_06.png
```

## 5. Modelagem orientada a objetos (resumo)

### 5.1 Entidades/Classes principais

- **Usuario** *(classe abstrata)*: concentra id, nome e e-mail comuns a todos os usuários; força subclasses a implementar `getPerfil()`.
- **Solicitante** *(extends Usuario)*: representa quem abre a solicitação; possui setor e ramal.
- **Atendente** *(extends Usuario)*: representa quem atende; possui cargo e especialidade (Categoria).
- **Solicitacao**: entidade central do domínio; mantém título, descrição, categoria, status, prioridade e as transições de ciclo de vida.
- **RepositorioSolicitacao**: armazenamento em memória com consultas por status, prioridade e categoria.
- **Categoria** *(enum)*: MANUTENCAO, TI, UTILIDADES, APOIO_OPERACIONAL, SEGURANCA.
- **Prioridade** *(enum)*: BAIXA, MEDIA, ALTA, CRITICA.
- **StatusSolicitacao** *(enum)*: ABERTA, EM_ATENDIMENTO, PAUSADA, CONCLUIDA, CANCELADA.
- **RegraPrioridade** *(interface)*: contrato para estratégias de cálculo de prioridade.
- **RegraPrioridadeGeral**: implementação para solicitações gerais (analisa palavras-chave na descrição).
- **RegraPrioridadeManutencao**: implementação específica para manutenção (prioriza riscos à segurança e paradas de produção, podendo atingir nível CRITICA).

### 5.2 Regras do domínio implementadas

- **Dados obrigatórios:** título e descrição não podem ser vazios; solicitante e categoria são obrigatórios.
- **Ciclo de vida/status:**
  - ABERTA → EM_ATENDIMENTO ou CANCELADA
  - EM_ATENDIMENTO → PAUSADA, CONCLUIDA ou CANCELADA
  - PAUSADA → EM_ATENDIMENTO ou CANCELADA
  - CONCLUIDA e CANCELADA não permitem novas transições
- **Consistência:** não é possível concluir sem atendente associado; transições inválidas lançam exceção com mensagem descritiva.

### 5.3 Técnicas de POO evidenciadas

- **Encapsulamento:** atributos de `Solicitacao` são privados; status só muda via métodos (`iniciarAtendimento`, `pausar`, `concluir`, `cancelar`) que validam a transição internamente.
- **Abstração:** `Usuario` abstrata concentra o que é comum a Solicitante e Atendente, expondo apenas o necessário.
- **Herança/classe abstrata:** `Solicitante` e `Atendente` herdam de `Usuario` e implementam `getPerfil()`.
- **Interface:** `RegraPrioridade` define o contrato `definirPrioridade()` e `descricaoRegra()`, desacoplando a regra da entidade `Solicitacao`.
- **Polimorfismo:** `recalcularPrioridade(RegraPrioridade $regra)` aceita qualquer implementação da interface; a mesma chamada produz resultados diferentes conforme a regra injetada (Geral vs Manutenção).

## 6. Demonstração do funcionamento

Fluxo demonstrado nas evidências:

1. Cadastro de solicitação válida (status ABERTA, prioridade calculada automaticamente).
2. Tentativa de cadastro com título vazio (validação do domínio, exceção exibida).
3. Início do atendimento por atendente (status → EM_ATENDIMENTO).
4. Pausa e retomada do atendimento (PAUSADA ↔ EM_ATENDIMENTO).
5. Conclusão e tentativa de nova operação após CONCLUIDA (bloqueio).
6. Demonstração de polimorfismo: mesma solicitação avaliada por `RegraPrioridadeGeral` e `RegraPrioridadeManutencao`, produzindo prioridades distintas.

## 7. Evidências

- No arquivo `Prints.docx` possuem prints com as evidências das operações realizadas:
- Cadastro de solicitação válida (status ABERTA).
- Validação: cadastro com título vazio.
- Início do atendimento (EM_ATENDIMENTO).
- Pausa e retomada (PAUSADA ↔ EM_ATENDIMENTO).
- Conclusão e bloqueio de operação após CONCLUIDA.
- Polimorfismo: RegraPrioridadeGeral vs RegraPrioridadeManutencao.

## 8. Referências

- Material da UC (Programação de Aplicativos / POO) – AVA
- Documentação oficial PHP 8.1 – https://www.php.net/manual/pt_BR/
