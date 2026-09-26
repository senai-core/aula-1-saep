<?php
require 'includes/conexao.php';
require 'includes/funcoes.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$acao = $_POST['acao'] ?? '';
$idPedido = filter_input(INPUT_POST, 'id_pedido', FILTER_VALIDATE_INT);

if ($idPedido) {
    if ($acao === 'excluir') {
        $excluir = $pdo->prepare('DELETE FROM pedido_reposicao WHERE id_pedido = ?');
        $excluir->execute([$idPedido]);
    } elseif ($acao === 'status' && array_key_exists($_POST['status'] ?? '', STATUS)) {
        $atualizar = $pdo->prepare('UPDATE pedido_reposicao SET status = ? WHERE id_pedido = ?');
        $atualizar->execute([$_POST['status'], $idPedido]);
    }
}

header('Location: index.php');
exit;
