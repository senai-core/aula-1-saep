<?php
require 'includes/conexao.php';
require 'includes/exibicao.php';
require 'includes/pedido.php';

$titulo = 'Painel de reposição';
$pedidosPorStatus = agruparPedidosPorStatus(listarPedidosComFuncionarioDaMaiorParaMenorUrgencia($pdo));

require 'includes/cabecalho.php';
?>
<h2>Painel de reposição</h2>

<div class="painel">
    <?php foreach ($pedidosPorStatus as $status => $pedidosDoStatus): ?>
        <section class="coluna">
            <h3><?= escaparHtml(STATUS[$status]) ?> <span><?= count($pedidosDoStatus) ?></span></h3>

            <?php if (!$pedidosDoStatus): ?>
                <p class="vazio">Nenhum pedido.</p>
            <?php endif; ?>

            <?php foreach ($pedidosDoStatus as $pedido): ?>
                <article class="card urgencia-<?= escaparHtml($pedido['urgencia']) ?>">
                    <h4><?= escaparHtml($pedido['medicamento']) ?></h4>
                    <p><strong>Quantidade:</strong> <?= escaparHtml($pedido['quantidade']) ?></p>
                    <p><strong>Categoria:</strong> <?= escaparHtml(CATEGORIAS[$pedido['categoria']] ?? $pedido['categoria']) ?></p>
                    <p><strong>Urgência:</strong> <?= escaparHtml(URGENCIAS[$pedido['urgencia']]) ?></p>
                    <p><strong>Funcionário:</strong> <?= escaparHtml($pedido['funcionario']) ?></p>

                    <div class="acoes">
                        <form method="post" action="acoes_pedido.php">
                            <input type="hidden" name="acao" value="status">
                            <input type="hidden" name="id_pedido" value="<?= escaparHtml($pedido['id_pedido']) ?>">
                            <select name="status" aria-label="Novo status">
                                <?php foreach (STATUS as $valor => $rotulo): ?>
                                    <option value="<?= escaparHtml($valor) ?>" <?= $pedido['status'] === $valor ? 'selected' : '' ?>><?= escaparHtml($rotulo) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="botao">Alterar status</button>
                        </form>
                    </div>
                    <div class="acoes">
                        <a href="pedidos.php?id=<?= escaparHtml($pedido['id_pedido']) ?>" class="botao botao-secundario">Editar</a>
                        <form method="post" action="acoes_pedido.php" onsubmit="return confirm('Deseja realmente excluir este pedido?');">
                            <input type="hidden" name="acao" value="excluir">
                            <input type="hidden" name="id_pedido" value="<?= escaparHtml($pedido['id_pedido']) ?>">
                            <button type="submit" class="botao botao-excluir">Excluir</button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endforeach; ?>
</div>

<?php require 'includes/rodape.php'; ?>
