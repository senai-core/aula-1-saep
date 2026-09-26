<?php
require 'includes/conexao.php';
require 'includes/funcoes.php';

$titulo = 'Painel de reposição';

$pedidos = $pdo->query(
    "SELECT p.*, f.nome AS funcionario
       FROM pedido_reposicao p
       JOIN funcionario f ON f.id_funcionario = p.id_funcionario
      ORDER BY FIELD(p.urgencia, 'alta', 'media', 'baixa'), p.data_solicitacao"
)->fetchAll();

$colunas = array_fill_keys(array_keys(STATUS), []);
foreach ($pedidos as $pedido) {
    $colunas[$pedido['status']][] = $pedido;
}

require 'includes/cabecalho.php';
?>
<h2>Painel de reposição</h2>

<div class="painel">
    <?php foreach ($colunas as $status => $lista): ?>
        <section class="coluna">
            <h3><?= e(STATUS[$status]) ?> <span><?= count($lista) ?></span></h3>

            <?php if (!$lista): ?>
                <p class="vazio">Nenhum pedido.</p>
            <?php endif; ?>

            <?php foreach ($lista as $pedido): ?>
                <article class="card urgencia-<?= e($pedido['urgencia']) ?>">
                    <h4><?= e($pedido['medicamento']) ?></h4>
                    <p><strong>Quantidade:</strong> <?= e($pedido['quantidade']) ?></p>
                    <p><strong>Categoria:</strong> <?= e(CATEGORIAS[$pedido['categoria']] ?? $pedido['categoria']) ?></p>
                    <p><strong>Urgência:</strong> <?= e(URGENCIAS[$pedido['urgencia']]) ?></p>
                    <p><strong>Funcionário:</strong> <?= e($pedido['funcionario']) ?></p>

                    <div class="acoes">
                        <form method="post" action="acoes.php">
                            <input type="hidden" name="acao" value="status">
                            <input type="hidden" name="id_pedido" value="<?= e($pedido['id_pedido']) ?>">
                            <select name="status" aria-label="Novo status">
                                <?php foreach (STATUS as $valor => $rotulo): ?>
                                    <option value="<?= e($valor) ?>" <?= $pedido['status'] === $valor ? 'selected' : '' ?>><?= e($rotulo) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="botao">Alterar status</button>
                        </form>
                    </div>
                    <div class="acoes">
                        <a href="pedidos.php?id=<?= e($pedido['id_pedido']) ?>" class="botao botao-secundario">Editar</a>
                        <form method="post" action="acoes.php" onsubmit="return confirm('Deseja realmente excluir este pedido?');">
                            <input type="hidden" name="acao" value="excluir">
                            <input type="hidden" name="id_pedido" value="<?= e($pedido['id_pedido']) ?>">
                            <button type="submit" class="botao botao-excluir">Excluir</button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endforeach; ?>
</div>

<?php require 'includes/rodape.php'; ?>
