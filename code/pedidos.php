<?php
require 'includes/conexao.php';
require 'includes/funcoes.php';

$erros = [];
$sucesso = false;

$idPedido = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: null;
$editando = $idPedido !== null;
$titulo = $editando ? 'Editar pedido' : 'Cadastro de pedidos';

$pedido = [
    'medicamento' => '',
    'quantidade' => '',
    'categoria' => '',
    'id_funcionario' => '',
    'urgencia' => '',
    'status' => 'solicitado',
];

if ($editando) {
    $consulta = $pdo->prepare('SELECT * FROM pedido_reposicao WHERE id_pedido = ?');
    $consulta->execute([$idPedido]);
    $existente = $consulta->fetch();
    if (!$existente) {
        header('Location: index.php');
        exit;
    }
    $pedido = array_merge($pedido, $existente);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pedido['medicamento'] = trim($_POST['medicamento'] ?? '');
    $pedido['quantidade'] = trim($_POST['quantidade'] ?? '');
    $pedido['categoria'] = $_POST['categoria'] ?? '';
    $pedido['id_funcionario'] = $_POST['id_funcionario'] ?? '';
    $pedido['urgencia'] = $_POST['urgencia'] ?? '';
    if ($editando) {
        $pedido['status'] = $_POST['status'] ?? '';
    }

    if ($pedido['medicamento'] === '') {
        $erros[] = 'Informe o medicamento.';
    }
    if (!ctype_digit($pedido['quantidade']) || (int) $pedido['quantidade'] <= 0) {
        $erros[] = 'A quantidade deve ser um número inteiro maior que zero.';
    }
    if (!array_key_exists($pedido['categoria'], CATEGORIAS)) {
        $erros[] = 'Selecione a categoria.';
    }
    if (!array_key_exists($pedido['urgencia'], URGENCIAS)) {
        $erros[] = 'Selecione a urgência.';
    }
    if ($editando && !array_key_exists($pedido['status'], STATUS)) {
        $erros[] = 'Selecione o status.';
    }

    $consulta = $pdo->prepare('SELECT COUNT(*) FROM funcionario WHERE id_funcionario = ?');
    $consulta->execute([$pedido['id_funcionario']]);
    if ($consulta->fetchColumn() == 0) {
        $erros[] = 'Selecione o funcionário.';
    }

    if (!$erros) {
        if ($editando) {
            $salvar = $pdo->prepare(
                'UPDATE pedido_reposicao
                    SET medicamento = ?, quantidade = ?, categoria = ?, id_funcionario = ?, urgencia = ?, status = ?
                  WHERE id_pedido = ?'
            );
            $salvar->execute([
                $pedido['medicamento'], (int) $pedido['quantidade'], $pedido['categoria'],
                $pedido['id_funcionario'], $pedido['urgencia'], $pedido['status'], $idPedido,
            ]);
        } else {
            $salvar = $pdo->prepare(
                'INSERT INTO pedido_reposicao (medicamento, quantidade, categoria, id_funcionario, urgencia)
                 VALUES (?, ?, ?, ?, ?)'
            );
            $salvar->execute([
                $pedido['medicamento'], (int) $pedido['quantidade'], $pedido['categoria'],
                $pedido['id_funcionario'], $pedido['urgencia'],
            ]);
            $pedido = array_fill_keys(array_keys($pedido), '');
        }
        $sucesso = true;
    }
}

$funcionarios = $pdo->query('SELECT id_funcionario, nome FROM funcionario ORDER BY nome')->fetchAll();

require 'includes/cabecalho.php';
?>
<h2><?= e($titulo) ?></h2>

<?php if ($sucesso): ?>
    <div class="mensagem sucesso">
        <?= $editando ? 'alteração concluída com sucesso' : 'cadastro concluído com sucesso' ?>
    </div>
<?php endif; ?>

<?php if ($erros): ?>
    <div class="mensagem erro">
        <ul>
            <?php foreach ($erros as $erro): ?>
                <li><?= e($erro) ?></li>
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
        <input type="text" name="medicamento" maxlength="100" required value="<?= e($pedido['medicamento']) ?>">
    </label>
    <label>
        Quantidade
        <input type="number" name="quantidade" min="1" step="1" required value="<?= e($pedido['quantidade']) ?>">
    </label>
    <label>
        Categoria
        <select name="categoria" required>
            <option value="">Selecione...</option>
            <?php foreach (CATEGORIAS as $valor => $rotulo): ?>
                <option value="<?= e($valor) ?>" <?= $pedido['categoria'] === $valor ? 'selected' : '' ?>><?= e($rotulo) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>
        Funcionário
        <select name="id_funcionario" required>
            <option value="">Selecione...</option>
            <?php foreach ($funcionarios as $funcionario): ?>
                <option value="<?= e($funcionario['id_funcionario']) ?>" <?= (string) $pedido['id_funcionario'] === (string) $funcionario['id_funcionario'] ? 'selected' : '' ?>>
                    <?= e($funcionario['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>
        Urgência
        <select name="urgencia" required>
            <option value="">Selecione...</option>
            <?php foreach (URGENCIAS as $valor => $rotulo): ?>
                <option value="<?= e($valor) ?>" <?= $pedido['urgencia'] === $valor ? 'selected' : '' ?>><?= e($rotulo) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <?php if ($editando): ?>
        <label>
            Status
            <select name="status" required>
                <?php foreach (STATUS as $valor => $rotulo): ?>
                    <option value="<?= e($valor) ?>" <?= $pedido['status'] === $valor ? 'selected' : '' ?>><?= e($rotulo) ?></option>
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
