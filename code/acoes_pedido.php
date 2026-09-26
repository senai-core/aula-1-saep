<?php
require 'includes/conexao.php';
require 'includes/exibicao.php';
require 'includes/pedido.php';

function voltarAoPainel()
{
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    voltarAoPainel();
}

$acao = $_POST['acao'] ?? '';
$idPedido = filter_input(INPUT_POST, 'id_pedido', FILTER_VALIDATE_INT);
$novoStatus = $_POST['status'] ?? '';

if ($idPedido && $acao === 'excluir') {
    excluirPedido($pdo, $idPedido);
}

if ($idPedido && $acao === 'status' && array_key_exists($novoStatus, STATUS)) {
    atualizarStatusDoPedido($pdo, $idPedido, $novoStatus);
}

voltarAoPainel();
