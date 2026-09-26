# Código-fonte: Controle de reposição de medicamentos

Sistema web em **PHP + MySQL** para a prova prática SAEP. Registra pedidos de reposição de medicamentos e mostra cada pedido num painel de três colunas: **Solicitado**, **Em separação** e **Recebido**.

## Requisitos

- PHP 8 ou superior com a extensão `pdo_mysql` (o XAMPP já vem com ela)
- MySQL ou MariaDB

## Como rodar (XAMPP)

1. Copie a pasta `code/` para `C:\xampp\htdocs\` e renomeie para `reposicao`.
2. No painel do XAMPP, inicie o **Apache** e o **MySQL**.
3. Crie o banco: abra o phpMyAdmin (`http://localhost/phpmyadmin`), vá em **Importar** e selecione `docs/criacao_do_banco_de_dados.sql`.
4. Abra `http://localhost/reposicao/`. A primeira tela é o painel de reposição.

Sem XAMPP, pelo terminal:

```bash
mysql -u root -p < ../docs/criacao_do_banco_de_dados.sql
php -S localhost:8000
```

Depois abra `http://localhost:8000`.

A conexão usa `root` sem senha. Se o seu banco tiver outro usuário ou senha, ajuste `includes/conexao.php`.

## Estrutura

```
code/
├── index.php            # Painel de reposição (tela inicial)
├── funcionarios.php     # Cadastro de funcionários
├── pedidos.php          # Cadastro de pedidos; com ?id=N vira a tela de edição
├── acoes_pedido.php     # Ações dos cards: excluir e alterar status
├── css/estilo.css       # Cores obrigatórias e fonte Segoe UI
└── includes/
    ├── conexao.php      # Conexão PDO com o banco
    ├── exibicao.php     # Rótulos (categoria, urgência, status) e escaparHtml()
    ├── funcionario.php  # Consultas e validação de funcionário
    ├── pedido.php       # Consultas e validação de pedido
    ├── cabecalho.php    # Topo e menu principal
    └── rodape.php
```

## O que cada tela faz

| Tela | Como funciona |
|---|---|
| **Painel de reposição** | Tela inicial. Três colunas, um card por pedido. Pedidos de urgência alta aparecem primeiro em cada coluna. Cada card tem: selecionar novo status e confirmar no botão, **Editar** e **Excluir** (pede confirmação). Não tem campo de inserção. |
| **Cadastro de funcionários** | Nome e e-mail, os dois obrigatórios. Valida o formato do e-mail e não deixa repetir e-mail. Ao salvar, mostra "cadastro concluído com sucesso". |
| **Cadastro de pedidos** | Medicamento, quantidade, categoria, funcionário (lista vinda do banco) e urgência. Quantidade tem que ser inteira e maior que zero. A data é gravada pelo banco e o status começa como "solicitado". Ao salvar, mostra "cadastro concluído com sucesso". |
| **Editar pedido** | Abre pelo botão **Editar** do card. É o mesmo formulário, já preenchido, e com o campo status a mais. Salvar atualiza o pedido existente, não cria outro. Aqui dá para mudar o status, a urgência ou os dois. |

## Validação

Toda regra é validada duas vezes:

- **No navegador**: `required`, `type="email"`, `min="1"` e `step="1"`.
- **No servidor (PHP)**: repete as mesmas checagens. Assim os dados continuam corretos mesmo se alguém burlar o formulário.

O banco também protege: `NOT NULL` em todos os campos, `CHECK (quantidade > 0)` e chave estrangeira do pedido para o funcionário.

## Padrões visuais

- Fonte: Segoe UI
- Cores: `#FFFFFF`, `#0A7D5A` e `#000000`
