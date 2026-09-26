<?php
function pedidoVazio()
{
    return [
        'medicamento' => '',
        'quantidade' => '',
        'categoria' => '',
        'id_funcionario' => '',
        'urgencia' => '',
        'status' => 'solicitado',
    ];
}

function lerPedidoDoFormulario(array $formulario, array $pedidoAtual, $editando)
{
    $pedido = $pedidoAtual;
    $pedido['medicamento'] = trim($formulario['medicamento'] ?? '');
    $pedido['quantidade'] = trim($formulario['quantidade'] ?? '');
    $pedido['categoria'] = $formulario['categoria'] ?? '';
    $pedido['id_funcionario'] = $formulario['id_funcionario'] ?? '';
    $pedido['urgencia'] = $formulario['urgencia'] ?? '';

    if ($editando) {
        $pedido['status'] = $formulario['status'] ?? '';
    }

    return $pedido;
}

function quantidadeEhInteiroPositivo($quantidade)
{
    return ctype_digit((string) $quantidade) && (int) $quantidade > 0;
}

function validarPedido(PDO $pdo, array $pedido, $editando)
{
    $erros = [];

    if ($pedido['medicamento'] === '') {
        $erros[] = 'Informe o medicamento.';
    }
    if (!quantidadeEhInteiroPositivo($pedido['quantidade'])) {
        $erros[] = 'A quantidade deve ser um número inteiro maior que zero.';
    }
    if (!array_key_exists($pedido['categoria'], CATEGORIAS)) {
        $erros[] = 'Selecione a categoria.';
    }
    if (!funcionarioExiste($pdo, $pedido['id_funcionario'])) {
        $erros[] = 'Selecione o funcionário.';
    }
    if (!array_key_exists($pedido['urgencia'], URGENCIAS)) {
        $erros[] = 'Selecione a urgência.';
    }
    if ($editando && !array_key_exists($pedido['status'], STATUS)) {
        $erros[] = 'Selecione o status.';
    }

    return $erros;
}

function buscarPedidoPorId(PDO $pdo, $idPedido)
{
    $consulta = $pdo->prepare('SELECT * FROM pedido_reposicao WHERE id_pedido = ?');
    $consulta->execute([$idPedido]);
    return $consulta->fetch();
}

function inserirPedidoComoSolicitadoNaDataAtual(PDO $pdo, array $pedido)
{
    $pdo->prepare(
        'INSERT INTO pedido_reposicao (medicamento, quantidade, categoria, id_funcionario, urgencia)
         VALUES (?, ?, ?, ?, ?)'
    )->execute([
        $pedido['medicamento'], (int) $pedido['quantidade'], $pedido['categoria'],
        $pedido['id_funcionario'], $pedido['urgencia'],
    ]);
}

function atualizarPedido(PDO $pdo, $idPedido, array $pedido)
{
    $pdo->prepare(
        'UPDATE pedido_reposicao
            SET medicamento = ?, quantidade = ?, categoria = ?, id_funcionario = ?, urgencia = ?, status = ?
          WHERE id_pedido = ?'
    )->execute([
        $pedido['medicamento'], (int) $pedido['quantidade'], $pedido['categoria'],
        $pedido['id_funcionario'], $pedido['urgencia'], $pedido['status'], $idPedido,
    ]);
}

function atualizarStatusDoPedido(PDO $pdo, $idPedido, $status)
{
    $pdo->prepare('UPDATE pedido_reposicao SET status = ? WHERE id_pedido = ?')->execute([$status, $idPedido]);
}

function excluirPedido(PDO $pdo, $idPedido)
{
    $pdo->prepare('DELETE FROM pedido_reposicao WHERE id_pedido = ?')->execute([$idPedido]);
}

function listarPedidosComFuncionarioDaMaiorParaMenorUrgencia(PDO $pdo)
{
    return $pdo->query(
        "SELECT p.*, f.nome AS funcionario
           FROM pedido_reposicao p
           JOIN funcionario f ON f.id_funcionario = p.id_funcionario
          ORDER BY FIELD(p.urgencia, 'alta', 'media', 'baixa'), p.data_solicitacao"
    )->fetchAll();
}

function agruparPedidosPorStatus(array $pedidos)
{
    $pedidosPorStatus = array_fill_keys(array_keys(STATUS), []);
    foreach ($pedidos as $pedido) {
        $pedidosPorStatus[$pedido['status']][] = $pedido;
    }
    return $pedidosPorStatus;
}
