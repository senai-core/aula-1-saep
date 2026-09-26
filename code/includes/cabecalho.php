<?php $paginaAtual = basename($_SERVER['PHP_SELF']); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escaparHtml($titulo) ?> - Reposição de Medicamentos</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <header class="topo">
        <h1>Reposição de Medicamentos</h1>
        <nav class="menu">
            <a href="index.php" class="<?= $paginaAtual === 'index.php' ? 'ativo' : '' ?>">Painel de reposição</a>
            <a href="funcionarios.php" class="<?= $paginaAtual === 'funcionarios.php' ? 'ativo' : '' ?>">Cadastro de funcionários</a>
            <a href="pedidos.php" class="<?= $paginaAtual === 'pedidos.php' ? 'ativo' : '' ?>">Cadastro de pedidos</a>
        </nav>
    </header>
    <main class="conteudo">
