<?php
require 'includes/conexao.php';
require 'includes/exibicao.php';
require 'includes/funcionario.php';
require 'includes/pedido.php';

$erros = [];
$sucesso = false;

$idPedidoEmEdicao = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: null;
$editando = $idPedidoEmEdicao !== null;
$titulo = $editando ? 'Editar pedido' : 'Cadastro de pedidos';
$pedido = pedidoVazio();

if ($editando) {
    $pedidoSalvo = buscarPedidoPorId($pdo, $idPedidoEmEdicao);
    if (!$pedidoSalvo) {
        header('Location: index.php');
        exit;
    }
    $pedido = array_merge($pedido, $pedidoSalvo);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pedido = lerPedidoDoFormulario($_POST, $pedido, $editando);
    $erros = validarPedido($pdo, $pedido, $editando);

    if (!$erros && $editando) {
        atualizarPedido($pdo, $idPedidoEmEdicao, $pedido);
    }

    if (!$erros && !$editando) {
        inserirPedidoComoSolicitadoNaDataAtual($pdo, $pedido);
        $pedido = pedidoVazio();
    }

    $sucesso = !$erros;
}

$funcionarios = listarFuncionariosPorNome($pdo);

require 'includes/cabecalho.php';
?>
<h2><?= escaparHtml($titulo) ?></h2>

<?php if ($sucesso): ?>
    <div class="mensagem sucesso">
        <?= $editando ? 'alteração concluída com sucesso' : 'cadastro concluído com sucesso' ?>
    </div>
<?php endif; ?>

<?php if ($erros): ?>
    <div class="mensagem erro">
        <ul>
            <?php foreach ($erros as $erro): ?>
                <li><?= escaparHtml($erro) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if (!$funcionarios): ?>
    <div class="mensagem erro">
        Nenhum funcionário cadastrado. <a href="funcionarios.php">Cadastre um funcionário</a> antes de registrar pedidos.
    </div>
<?php endif; ?>

<form method="post" class="formulario">
    <label>
        Medicamento
        <input type="text" name="medicamento" maxlength="100" required value="<?= escaparHtml($pedido['medicamento']) ?>">
    </label>
    <label>
        Quantidade
        <input type="number" name="quantidade" min="1" step="1" required value="<?= escaparHtml($pedido['quantidade']) ?>">
    </label>
    <label>
        Categoria
        <select name="categoria" required>
            <option value="">Selecione...</option>
            <?php foreach (CATEGORIAS as $valor => $rotulo): ?>
                <option value="<?= escaparHtml($valor) ?>" <?= $pedido['categoria'] === $valor ? 'selected' : '' ?>><?= escaparHtml($rotulo) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>
        Funcionário
        <select name="id_funcionario" required>
            <option value="">Selecione...</option>
            <?php foreach ($funcionarios as $funcionario): ?>
                <option value="<?= escaparHtml($funcionario['id_funcionario']) ?>" <?= (string) $pedido['id_funcionario'] === (string) $funcionario['id_funcionario'] ? 'selected' : '' ?>>
                    <?= escaparHtml($funcionario['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>
        Urgência
        <select name="urgencia" required>
            <option value="">Selecione...</option>
            <?php foreach (URGENCIAS as $valor => $rotulo): ?>
                <option value="<?= escaparHtml($valor) ?>" <?= $pedido['urgencia'] === $valor ? 'selected' : '' ?>><?= escaparHtml($rotulo) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <?php if ($editando): ?>
        <label>
            Status
            <select name="status" required>
                <?php foreach (STATUS as $valor => $rotulo): ?>
                    <option value="<?= escaparHtml($valor) ?>" <?= $pedido['status'] === $valor ? 'selected' : '' ?>><?= escaparHtml($rotulo) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    <?php endif; ?>
    <div>
        <button type="submit" class="botao"><?= $editando ? 'Salvar alterações' : 'Salvar' ?></button>
        <?php if ($editando): ?>
            <a href="index.php" class="botao botao-secundario">Voltar ao painel</a>
        <?php endif; ?>
    </div>
</form>

<?php require 'includes/rodape.php'; ?>
