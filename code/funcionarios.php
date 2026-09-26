<?php
require 'includes/conexao.php';
require 'includes/exibicao.php';
require 'includes/funcionario.php';

$titulo = 'Cadastro de funcionários';
$erros = [];
$sucesso = false;
$nome = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $erros = validarFuncionario($pdo, $nome, $email);

    if (!$erros) {
        inserirFuncionario($pdo, $nome, $email);
        $sucesso = true;
        $nome = '';
        $email = '';
    }
}

$funcionarios = listarFuncionariosPorNome($pdo);

require 'includes/cabecalho.php';
?>
<h2>Cadastro de funcionários</h2>

<?php if ($sucesso): ?>
    <div class="mensagem sucesso">cadastro concluído com sucesso</div>
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

<form method="post" class="formulario">
    <label>
        Nome
        <input type="text" name="nome" maxlength="100" required value="<?= escaparHtml($nome) ?>">
    </label>
    <label>
        E-mail
        <input type="email" name="email" maxlength="150" required value="<?= escaparHtml($email) ?>">
    </label>
    <div>
        <button type="submit" class="botao">Salvar</button>
    </div>
</form>

<h2 style="margin-top: 32px;">Funcionários cadastrados</h2>
<?php if ($funcionarios): ?>
    <ul>
        <?php foreach ($funcionarios as $funcionario): ?>
            <li><?= escaparHtml($funcionario['nome']) ?> (<?= escaparHtml($funcionario['email']) ?>)</li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p class="vazio">Nenhum funcionário cadastrado.</p>
<?php endif; ?>

<?php require 'includes/rodape.php'; ?>
