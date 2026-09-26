<?php
require 'includes/conexao.php';
require 'includes/funcoes.php';

$titulo = 'Cadastro de funcionários';
$erros = [];
$sucesso = false;
$nome = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($nome === '') {
        $erros[] = 'Informe o nome.';
    }
    if ($email === '') {
        $erros[] = 'Informe o e-mail.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'Informe um e-mail em formato válido.';
    } else {
        $consulta = $pdo->prepare('SELECT COUNT(*) FROM funcionario WHERE email = ?');
        $consulta->execute([$email]);
        if ($consulta->fetchColumn() > 0) {
            $erros[] = 'Este e-mail já está cadastrado.';
        }
    }

    if (!$erros) {
        $inserir = $pdo->prepare('INSERT INTO funcionario (nome, email) VALUES (?, ?)');
        $inserir->execute([$nome, $email]);
        $sucesso = true;
        $nome = '';
        $email = '';
    }
}

$funcionarios = $pdo->query('SELECT nome, email FROM funcionario ORDER BY nome')->fetchAll();

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
                <li><?= e($erro) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" class="formulario">
    <label>
        Nome
        <input type="text" name="nome" maxlength="100" required value="<?= e($nome) ?>">
    </label>
    <label>
        E-mail
        <input type="email" name="email" maxlength="150" required value="<?= e($email) ?>">
    </label>
    <div>
        <button type="submit" class="botao">Salvar</button>
    </div>
</form>

<h2 style="margin-top: 32px;">Funcionários cadastrados</h2>
<?php if ($funcionarios): ?>
    <ul>
        <?php foreach ($funcionarios as $funcionario): ?>
            <li><?= e($funcionario['nome']) ?> (<?= e($funcionario['email']) ?>)</li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p class="vazio">Nenhum funcionário cadastrado.</p>
<?php endif; ?>

<?php require 'includes/rodape.php'; ?>
