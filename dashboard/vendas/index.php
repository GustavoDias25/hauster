<?php
$estabelecimento_id = $_SESSION['estabelecimento_id'];

$stmt = $conexao->prepare(
    "SELECT ip.id_pedido, ip.dt_pedido, ip.valor, ip.produtos_idprodutos, ip.form_pagamento, p.idprodutos, p.nome_prod as produto
     FROM itens_pedido ip, produtos p 
     WHERE p.idprodutos = ip.produtos_idprodutos and ip.estabelecimento_idestabelecimento = :estabelecimento_id ORDER BY ip.id_pedido"
);
$stmt->bindParam(':estabelecimento_id', $estabelecimento_id);
$stmt->execute();
$pedidos = $stmt->fetchAll();

// Recarrega a lista já sem os vencidos
$stmt->execute();
$pedidos = $stmt->fetchAll();
?>
<main class="tabela content fundo">
    <?php if (!empty($_SESSION['erro'])): ?>
        <p class="erro"><?= htmlspecialchars($_SESSION['erro']) ?></p>
        <?php unset($_SESSION['erro']); ?>
    <?php endif; ?>
    <div class="cabecalho-tabela">
        <h1 class="titulo">Lista de pedidos</h1>
        <div style="display:flex; gap:10px;">
            <a href="dashboard/vendas/exportar_pedidos.php" class="btn-cadastrar">Gerar Relatório</a>
            <a href="dashboard.php?pagina=pedidos_cadastrar" class="btn-cadastrar">Cadastrar Pedido</a>
        </div>
    </div>

    <table class="table-sb">
        <tr class="linha">
            <th class="celulas">ID</th>
            <th class="celulas">Produto</th>
            <th class="celulas">Data do pedido</th>
            <th class="celulas">Valor do pedido</th>
            <th class="celulas">Forma de pagamento</th>
            <th class="celulas">Ações</th>
        </tr>

        <?php if (count($pedidos) > 0): ?>
            <?php foreach ($pedidos as $pedido): ?>
                <tr class="linha">
                    <td class="celulas"><?= htmlspecialchars($pedido['id_pedido']) ?></td>
                    <td class="celulas"><?= htmlspecialchars($pedido['produto']) ?></td>
                    <td class="celulas"><?= date('d/m/Y', strtotime($pedido['dt_pedido'])) ?></td>
                    <td class="celulas destaque"><?= htmlspecialchars($pedido['valor']) ?></td>
                    <td class="celulas destaque"><?= htmlspecialchars($pedido['form_pagamento']) ?></td>
                    <td class="celulas">
                        <div class="acoes">
                            <a href="dashboard.php?pagina=pedidos_editar&id_pedido=<?= $pedido['id_pedido'] ?>">
                                <span class="material-symbols-outlined icon editar">edit_square</span>
                            </a>
                            <a href="dashboard.php?pagina=pedidos_excluir&id_pedido=<?= $pedido['id_pedido'] ?>"
                               onclick="return confirm('Tem certeza que deseja excluir este pedido?');">
                                <span class="material-symbols-outlined icon excluir">delete</span>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr class="linha">
                <td class="celulas" colspan="8">Nenhum pedido cadastrado.</td>
            </tr>
        <?php endif; ?>
    </table>
</main>