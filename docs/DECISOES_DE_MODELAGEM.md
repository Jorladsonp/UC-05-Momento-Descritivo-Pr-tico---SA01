# Decisões de Modelagem – SA1 (POO)

## 1. Problema e recorte adotado

### Escopo implementado
- Cadastro de solicitações internas com categoria, título, descrição e solicitante.
- Cálculo automático de prioridade via regra injetada no momento do cadastro.
- Acompanhamento por status com transições controladas (ciclo de vida).
- Associação de atendente no início do atendimento.
- Listagem/consulta em memória por status, prioridade e categoria.

### Fora do escopo
- Persistência em banco de dados (evolução futura).
- Interface gráfica/web (será outro módulo).
- Autenticação e controle de acesso (apenas simulado por objetos).
- Histórico de mudanças de status (auditoria – melhoria futura).

## 2. Principais decisões OO

### 2.1 Responsabilidades e coesão

- `Solicitacao` concentra as regras do domínio: validação de dados obrigatórios e controle das transições de status. Isso evita "anemia de domínio" — a entidade conhece e protege seu próprio estado.
- `RepositorioSolicitacao` cuida exclusivamente de armazenar e consultar objetos, separando a responsabilidade de persistência da lógica de negócio.
- `Usuario` (abstrata) elimina duplicação de código entre `Solicitante` e `Atendente`, centralizando validação de id, nome e e-mail.

### 2.2 Diferencial em relação ao gabarito

- Adicionado `Categoria` (enum) como atributo obrigatório da solicitação, permitindo segmentação real das demandas por setor.
- `Prioridade` ganhou o nível `CRITICA`, necessário para cenários de manutenção com risco à segurança.
- `Usuario` recebeu campo `email` e método abstrato `getPerfil()`, tornando o polimorfismo visível também na camada de usuários.
- `Solicitante` recebeu `ramal` e `Atendente` recebeu `cargo` e `especialidade` (Categoria), tornando o modelo mais aderente ao contexto industrial.
- `RegraPrioridade` ganhou o método `descricaoRegra()` no contrato, permitindo que o sistema exiba qual estratégia está sendo utilizada.
- `RegraPrioridadeManutencao` substitui `RegraPrioridadeTI` do gabarito, com lógica própria para o contexto de manutenção industrial.
- `main.php` implementa menu interativo em vez de script linear, demonstrando o fluxo completo de forma mais realista.

### 2.3 Extensibilidade (evolução)

- A interface `RegraPrioridade` permite criar novas estratégias (ex.: `RegraPrioridadeSeguranca`, `RegraPrioridadeUtilidades`) sem alterar `Solicitacao` ou o fluxo principal.
- Os enums (`Categoria`, `Prioridade`, `StatusSolicitacao`) centralizam os valores válidos do domínio, evitando strings soltas e facilitando manutenção.
- Novas especializações de `Usuario` (ex.: `Supervisor`, `Coordenador`) podem ser adicionadas herdando de `Usuario` sem impactar o restante do sistema.

### 2.4 Polimorfismo — estratégia de prioridade

- O método `recalcularPrioridade(RegraPrioridade $regra)` em `Solicitacao` aceita qualquer implementação da interface.
- `RegraPrioridadeGeral` usa palavras-chave genéricas (urgente, parado, lento) e produz no máximo `ALTA`.
- `RegraPrioridadeManutencao` identifica riscos à segurança (vazamento, curto, incêndio) e pode atingir `CRITICA`.
- A mesma chamada com objetos diferentes produz resultados distintos — demonstração direta de polimorfismo por substituição (Liskov).

## 3. Pontos de melhoria futuros

- Persistência em banco de dados (PDO/MySQL ou SQLite).
- Histórico de transições de status com timestamp (auditoria).
- Testes automatizados (PHPUnit).
- Relatórios: tempo médio de atendimento, volume por categoria, solicitações pendentes.
- Validação de e-mail com filtro nativo do PHP.
