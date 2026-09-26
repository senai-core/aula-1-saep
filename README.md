# SAEP: Controle de reposição de medicamentos

Resolução do teste prático SAEP (Aula 1). Uma rede de farmácias precisa de um sistema único para registrar pedidos de reposição e acompanhar, num painel visual, em que etapa cada pedido está.

## Entregas

| Nº | Entrega | Arquivo |
|---|---|---|
| 1 | Diagrama ER (DER) | [`docs/der.jpg`](docs/der.jpg) |
| 2 | Criação do banco de dados | [`docs/criacao_do_banco_de_dados.sql`](docs/criacao_do_banco_de_dados.sql) |
| 3 | Caso de uso | [`docs/caso_de_uso.jpg`](docs/caso_de_uso.jpg) |
| 4 | Tela de cadastro de funcionários | [`code/funcionarios.php`](code/funcionarios.php) |
| 5 | Tela de cadastro de pedidos | [`code/pedidos.php`](code/pedidos.php) |
| 6 | Painel de reposição | [`code/index.php`](code/index.php) |

## Decisões tomadas

- **Categorias** fixas numa lista: genérico, referência, controlado e higiene (os exemplos da prova).
- **E-mail** do funcionário é único no banco.
- **Excluir funcionário** não existe no sistema. O banco bloqueia (`ON DELETE RESTRICT`) para não deixar pedido sem dono.
- **Status e urgência** são gravados sem acento (`em_separacao`, `media`). A tela mostra o texto com acento.
- **Caso de uso**: "alterar urgência" está dentro de "Editar pedido", porque é nessa tela que a urgência muda.
- O **diagrama conceitual** que o avaliador entrega não veio junto do enunciado. O DER foi feito direto a partir dos dados e regras da prova.
