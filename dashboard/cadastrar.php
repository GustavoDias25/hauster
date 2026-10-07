<?php
$estabelecimento_id = $_SESSION['estabelecimento_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produto_id      = $_POST['produto_id'];
    $quantidade      = !empty($_POST['quantidade']) ? (int) $_POST['quantidade'] : 0;
    $valor           = $_POST['valor'];
    $forma_pagamento = $_POST['forma_pagamento'];
    $dt_pedido       = empty($_POST['dt_pedido']) ? date('Y-m-d'): $_POST['dt_pedido'];

    try {
        $conexao->beginTransaction();

        $stmtEstoque = $conexao->prepare(
            "SELECT estoque, preco
             FROM produtos 
             WHERE idprodutos = :produto_id AND estabelecimento_idestabelecimento = :estabelecimento_id FOR UPDATE"
        );
        $stmtEstoque->execute([
            ':produto_id' => $produto_id,
            ':estabelecimento_id' => $estabelecimento_id
        ]);
        $produto = $stmtEstoque->fetch();

        if (!$produto) {
        $erro = 'Produto não encontrado neste estabelecimento.';
    } elseif ($produto['estoque'] < $quantidade) {
    $erro = 'Estoque insuficiente para este produto.';
    }

if ($erro != '') {
    echo $erro;
}

        $categoria_id = $produto['categorias_idcategorias'];

        $stmtPedido = $conexao->prepare(
            "INSERT INTO itens_pedido 
                (produtos_idprodutos, 
                 dt_pedido, valor, quantidade, form_pagamento, estabelecimento_idestabelecimento)
             VALUES 
                (:produto_id, :dt_pedido, :valor, :quantidade, :forma_pagamento, :estabelecimento_id)"
        );
        $stmtPedido->execute([
            ':produto_id'         => $produto_id,
            ':dt_pedido'          => $dt_pedido,
            ':valor'              => $valor,
            ':quantidade'         => $quantidade,
            ':forma_pagamento'    => $forma_pagamento,
            ':estabelecimento_id' => $estabelecimento_id
        ]);

        $stmtEstoqueUpdate = $conexao->prepare(
            "UPDATE produtos 
             SET estoque = estoque - :quantidade 
             WHERE idprodutos = :produto_id 
               AND estabelecimento_idestabelecimento = :estabelecimento_id"
        );
        $stmtEstoqueUpdate->execute([
            ':quantidade'         => $quantidade,
            ':produto_id'         => $produto_id,
            ':estabelecimento_id' => $estabelecimento_id
        ]);

        $conexao->commit();

        header('Location: dashboard.php?pagina=pedidos');
        exit;

    } catch (Exception $e) {
        $conexao->rollBack();
        $_SESSION['erro'] = 'Erro ao registrar pedido: ' . $e->getMessage();
    }
}

$stmtProdutos = $conexao->prepare(
    "SELECT idprodutos, nome_prod, estoque, preco
     FROM produtos
     WHERE estabelecimento_idestabelecimento = :estabelecimento_id
     ORDER BY nome_prod ASC"
);
$stmtProdutos->execute([':estabelecimento_id' => $estabelecimento_id]);
$produtos = $stmtProdutos->fetchAll();

?>

<main class="tabela content fundo">
    <h1 class="titulo">Cadastrar Venda</h1>

    <?php if (!empty($_SESSION['erro'])): ?>
        <p class="erro"><?= htmlspecialchars($_SESSION['erro']) ?></p>
        <?php unset($_SESSION['erro']); ?>
    <?php endif; ?>

    <link rel="stylesheet" href="estoque/css/cadastrar.css">
    <form method="POST" action="dashboard.php?pagina=pedidos_cadastrar" class="form-produto">
        <div class="campos">

            <div class="campo">
                <label for="produto_id">Produto</label>
                <select name="produto_id" id="produto_id" required>
                    <option value="">Selecione um produto</option>
                    <?php foreach ($produtos as $produto): ?>
                        <option value="<?= $produto['idprodutos'] ?>" data-preco="<?= $produto['preco'] ?>">
                            <?= htmlspecialchars($produto['nome_prod']) ?> (estoque: <?= $produto['estoque'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="campo">
                <label for="quantidade">Quantidade</label>
                <input type="number" id="quantidade" name="quantidade" min="0" required placeholder="Quantidade de produtos no pedido">
            </div>

            <div class="campo">
                <label for="valor">Valor</label>
                <input type="number" id="valor" name="valor" step="0.01" min="0" required readonly placeholder="Valor do pedido">
            </div>
            <div class="campo">
                <label for="dt_pedido">Data do pedido</label>
                <input type="date" id="dt_pedido" name="dt_pedido" value="<?= date('Y-m-d') ?>">
            </div>

            <div class="campo">
                <label for="forma_pagamento">Forma de pagamento</label>
                <select name="forma_pagamento" id="forma_pagamento" required>
                    <option value="">Selecione</option>
                    <option value="credito">Crédito</option>
                    <option value="debito">Débito</option>
                    <option value="pix">Pix</option>
                    <option value="dinheiro">Dinheiro</option>
                </select>
            </div>

            <div class="acoes campo">
                <button type="submit">Registrar pedido</button>
                <a href="dashboard.php?pagina=pedidos"><button type="button">Cancelar</button></a>
            </div>

        </div>
    </form>
</main>
<script>
const selectProduto = document.getElementById('produto_id');
const inputQuantidade = document.getElementById('quantidade');
const inputValor = document.getElementById('valor');

function calcularValor() {
    const option = selectProduto.options[selectProduto.selectedIndex];
    const preco = parseFloat(option.dataset.preco);
    const quantidade = parseInt(inputQuantidade.value, 10);

    if (!isNaN(preco) && !isNaN(quantidade) && quantidade > 0) {
        inputValor.value = (preco * quantidade).toFixed(2);
    } else {
        inputValor.value = '';
    }
}

selectProduto.addEventListener('change', calcularValor);
inputQuantidade.addEventListener('input', calcularValor);
calcularValor() 
</script>