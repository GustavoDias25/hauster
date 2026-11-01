<?php
include_once "includes/helpers_funcionarios.php";

$estabelecimento_id = $_SESSION['estabelecimento_id'];
$id = isset($_GET['id_func']) ? (int) $_GET['id_func'] : 0;

if ($id <= 0) {
    header('Location: dashboard.php?pagina=funcionarios');
    exit;
}

// Dados do funcionário (só se for do estabelecimento logado)
$stmt = $conexao->prepare(
    "SELECT f.*, c.nome AS nome_cargo
     FROM funcionarios f
     JOIN cargos c ON c.idcargos = f.cargos_idcargos
     WHERE f.idfuncionarios = :id AND f.estabelecimento_idestabelecimento = :estab"
);
$stmt->execute([':id' => $id, ':estab' => $estabelecimento_id]);
$func = $stmt->fetch();

if (!$func) {
    header('Location: dashboard.php?pagina=funcionarios');
    exit;
}

// Quantidade de vendas, itens vendidos e total vendido por esse funcionário
$stmt = $conexao->prepare(
    "SELECT COUNT(*)                    AS total_vendas,
            COALESCE(SUM(quantidade),0) AS total_itens,
            COALESCE(SUM(valor),0)      AS total_valor
     FROM itens_pedido
     WHERE funcionarios_idfuncionarios = :id
       AND estabelecimento_idestabelecimento = :estab"
);
$stmt->execute([':id' => $id, ':estab' => $estabelecimento_id]);
$resumo = $stmt->fetch();

// Lista das vendas dele (LEFT JOIN: se o produto foi excluído, a venda continua aparecendo)
$stmt = $conexao->prepare(
    "SELECT ip.id_pedido, ip.dt_pedido, ip.valor, ip.quantidade, ip.form_pagamento,
            p.nome_prod AS produto
     FROM itens_pedido ip
     LEFT JOIN produtos p ON p.idprodutos = ip.produtos_idprodutos
     WHERE ip.funcionarios_idfuncionarios = :id
       AND ip.estabelecimento_idestabelecimento = :estab
     ORDER BY ip.dt_pedido DESC, ip.id_pedido DESC"
);
$stmt->execute([':id' => $id, ':estab' => $estabelecimento_id]);
$vendas = $stmt->fetchAll();
?>
<main class="tabela content fundo">
    <div class="cabecalho-tabela">
        <h1 class="titulo">Detalhes do funcionário</h1>
        <div style="display:flex; gap:10px;">
            <a href="dashboard.php?pagina=funcionarios_editar&id_func=<?= $func['idfuncionarios'] ?>" class="btn-cadastrar">Editar</a>
            <a href="dashboard.php?pagina=funcionarios" class="btn-cadastrar">Voltar</a>
        </div>
    </div>

    <div class="dados-func">
        <!-- Dados de vendas: mesmo bloco das outras informações, só que em destaque (amarelo da paleta) -->
        <div class="destaque-func"><span>Vendas realizadas</span><strong><?= (int) $resumo['total_vendas'] ?></strong></div>
        <div class="destaque-func"><span>Itens vendidos</span><strong><?= (int) $resumo['total_itens'] ?></strong></div>
        <div class="destaque-func"><span>Total vendido</span><strong><?= formatar_dinheiro($resumo['total_valor']) ?></strong></div>
        <div><span>Nome</span><strong><?= htmlspecialchars($func['nome']) ?></strong></div>
        <div><span>Cargo</span><strong><?= htmlspecialchars($func['nome_cargo']) ?></strong></div>
        <div><span>CPF</span><strong><?= htmlspecialchars(formatar_cpf($func['cpf'])) ?></strong></div>
        <div><span>Data de nascimento</span><strong><?= date('d/m/Y', strtotime($func['dt_nasc'])) ?></strong></div>
        <div><span>Salário</span><strong><?= formatar_dinheiro($func['salario']) ?></strong></div>
        <div><span>Telefone</span><strong><?= htmlspecialchars(formatar_telefone($func['telefone'])) ?></strong></div>
        <div><span>E-mail</span><strong><?= htmlspecialchars($func['email']) ?></strong></div>
    </div>

    <h2 class="subtitulo-func">Vendas registradas por <?= htmlspecialchars($func['nome']) ?></h2>

    <table class="table-sb">
        <tr class="linha">
            <th class="celulas">ID</th>
            <th class="celulas">Produto</th>
            <th class="celulas">Data do pedido</th>
            <th class="celulas">Quantidade</th>
            <th class="celulas">Valor do pedido</th>
            <th class="celulas">Forma de pagamento</th>
        </tr>

        <?php if (count($vendas) > 0): ?>
            <?php foreach ($vendas as $venda): ?>
                <tr class="linha">
                    <td class="celulas"><?= htmlspecialchars($venda['id_pedido']) ?></td>
                    <td class="celulas"><?= htmlspecialchars($venda['produto'] ?? 'Produto removido') ?></td>
                    <td class="celulas"><?= date('d/m/Y', strtotime($venda['dt_pedido'])) ?></td>
                    <td class="celulas"><?= (int) $venda['quantidade'] ?></td>
                    <td class="celulas destaque"><?= formatar_dinheiro($venda['valor']) ?></td>
                    <td class="celulas destaque"><?= htmlspecialchars($venda['form_pagamento']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr class="linha">
                <td class="celulas" colspan="6">Este funcionário ainda não registrou nenhuma venda.</td>
            </tr>
        <?php endif; ?>
    </table>
</main>