<?php
function listarFuncionariosPorNome(PDO $pdo)
{
    return $pdo->query('SELECT id_funcionario, nome, email FROM funcionario ORDER BY nome')->fetchAll();
}

function funcionarioExiste(PDO $pdo, $idFuncionario)
{
    $consulta = $pdo->prepare('SELECT COUNT(*) FROM funcionario WHERE id_funcionario = ?');
    $consulta->execute([$idFuncionario]);
    return $consulta->fetchColumn() > 0;
}

function emailJaCadastrado(PDO $pdo, $email)
{
    $consulta = $pdo->prepare('SELECT COUNT(*) FROM funcionario WHERE email = ?');
    $consulta->execute([$email]);
    return $consulta->fetchColumn() > 0;
}

function inserirFuncionario(PDO $pdo, $nome, $email)
{
    $pdo->prepare('INSERT INTO funcionario (nome, email) VALUES (?, ?)')->execute([$nome, $email]);
}

function validarFuncionario(PDO $pdo, $nome, $email)
{
    $erros = [];

    if ($nome === '') {
        $erros[] = 'Informe o nome.';
    }

    if ($email === '') {
        $erros[] = 'Informe o e-mail.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'Informe um e-mail em formato válido.';
    } elseif (emailJaCadastrado($pdo, $email)) {
        $erros[] = 'Este e-mail já está cadastrado.';
    }

    return $erros;
}
