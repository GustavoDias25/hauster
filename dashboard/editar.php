<?php
$erro = '';
$id = isset($_GET['id_pedido']) ? (int) $_GET['id_pedido'] : 0;
$estabelecimento_id = $_SESSION['estabelecimento_id'];

if ($id <= 0) {
    header('Location: dashboard.php?pagina=pedidos');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_prod        = isset($_POST['produto_id']) ? (int) $_POST['produto_id'] : 0;
    $dt_pedido      = trim($_POST['dt_pedido']);
    $valor          = str_replace(',', '.', $_POST['valor']);
    $quantidade     = (int) $_POST['quantidade'];
    $form_pagamento = trim($_POST['forma_pagamento']);

    try {
        $sql = "UPDATE itens_pedido
                SET produtos_idprodutos = :id_prod, dt_pedido = :dt_pedido,
                    valor = :valor, quantidade = :quantidade, form_pagamento = :form_pagamento
                WHERE id_pedido = :id_pedido
                  AND estabelecimento_idestabelecimento = :estab";
        $stmt = $conexao->prepare($sql);
        $stmt->execute([
            ':id_prod'        => $id_prod,
            ':dt_pedido'      => $dt_pedido,
            ':valor'          => $valor,
            ':quantidade'     => $quantidade,
            ':form_pagamento' => $form_pagamento,
            ':id_pedido'      => $id,
            ':estab'          => $estabelecimento_id,
        ]);

        header('Location: dashboard.php?pagina=pedidos');
        exit;
    } catch (PDOException $e) {
        $erro = 'Erro ao atualizar pedido: ' . $e->getMessage();
    }
}

$stmt = $conexao->prepare(
    "SELECT * FROM itens_pedido
     WHERE id_pedido = :id AND estabelecimento_idestabelecimento = :estab"
);
$stmt->execute([':id' => $id, ':estab' => $estabelecimento_id]);
$pedido = $stmt->fetch();

if (!$pedido) {
    header('Location: dashboard.php?pagina=pedidos');
    exit;
}

$stmt3 = $conexao->prepare(
    "SELECT * FROM produtos
     WHERE estabelecimento_idestabelecimento = :estab
     ORDER BY idprodutos"
);
$stmt3->execute([':estab' => $estabelecimento_id]);
$produtos = $stmt3->fetchAll();
?>
<main class="tabela content fundo">
    <h1 class="titulo">Editar produto</h1>

    <?php if ($erro): ?>
        <p class="erro"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <form method="POST" action="dashboard.php?pagina=pedidos_editar&id_pedido=<?=$pedido['id_pedido']?>" class="form-produto">
        <div class="campos">

            <div class="campo">
                <label for="produto_id">Produto</label>
                <select name="produto_id" id="produto_id" required>
                    <option value="">Selecione um produto</option>
                        <?php foreach ($produtos as $produto): ?>
                            <option value="<?= $produto['idprodutos'] ?>"
                                data-preco="<?= $produto['preco'] ?>"
                                <?= $produto['idprodutos'] == $pedido['produtos_idprodutos'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($produto['nome_prod']) ?>
                                (estoque: <?= $produto['estoque'] ?>)
                            </option>   
                        <?php endforeach; ?>
                </select>
            </div>

            <div class="campo">
                <label for="quantidade">Quantidade</label>
                <input type="number" id="quantidade" name="quantidade" value="<?=$pedido['quantidade']?>">
            </div>

            <div class="campo">
                <label for="valor">Valor</label>
                <input type="number" id="valor" name="valor" step="0.01" min="0" required readonly value="<?=$pedido['valor']?>">
            </div>
            <div class="campo">
                <label for="dt_pedido">Data do pedido</label>
                <input type="date" id="dt_pedido" name="dt_pedido" value="<?= $pedido['dt_pedido'] ?>">
            </div>

            <div class="campo">
                <label for="forma_pagamento">Forma de pagamento</label>
                <select name="forma_pagamento" id="forma_pagamento" required>
                    <option value="">Selecione</option>

                    <option value="Crédito" <?= $pedido['form_pagamento'] == 'Crédito' ? 'selected' : '' ?>>
                        Crédito
                    </option>

                    <option value="Débito" <?= $pedido['form_pagamento'] == 'Débito' ? 'selected' : '' ?>>
                        Débito
                    </option>

                    <option value="Pix" <?= $pedido['form_pagamento'] == 'Pix' ? 'selected' : '' ?>>
                        Pix
                    </option>

                    <option value="Dinheiro" <?= $pedido['form_pagamento'] == 'Dinheiro' ? 'selected' : '' ?>>
                        Dinheiro
                    </option>
                </select>
            </div>

            <div class="acoes campo">
                <button type="submit">Salvar alterações</button>
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