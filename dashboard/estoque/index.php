<?php
$estabelecimento_id = $_SESSION['estabelecimento_id'];

$stmt = $conexao->prepare(
    "SELECT p.*, c.nome AS nome_categoria
     FROM produtos p
     JOIN categorias c ON p.categorias_idcategorias = c.idcategorias
     WHERE p.estabelecimento_idestabelecimento = :estabelecimento_id
     ORDER BY p.idprodutos"
);
$stmt->bindParam(':estabelecimento_id', $estabelecimento_id);
$stmt->execute();
$produtos = $stmt->fetchAll();

// Remove produtos vencidos
foreach ($produtos as $produto) {
    if (new DateTime($produto['validade']) < new DateTime()) {
        $stmt2 = $conexao->prepare(
            "DELETE FROM produtos WHERE idprodutos = :id_prod AND categorias_idcategorias = :idcategoria"
        );
        $stmt2->bindParam(':id_prod', $produto['idprodutos']);
        $stmt2->bindParam(':idcategoria', $produto['categorias_idcategorias']);
        $stmt2->execute();
    }
}

// Recarrega a lista já sem os vencidos
$stmt->execute();
$produtos = $stmt->fetchAll();
?>
<main class="tabela content fundo">
    <div class="cabecalho-tabela">
        <h1 class="titulo">Lista de produtos</h1>
        <a href="dashboard.php?pagina=produtos_cadastrar" class="btn-cadastrar">Cadastrar produto</a>
    </div>

    <table class="table">
        <tr class="linha">
            <th class="celulas">ID</th>
            <th class="celulas">Nome</th>
            <th class="celulas">Marca</th>
            <th class="celulas">Categoria</th>
            <th class="celulas">Preço</th>
            <th class="celulas">Validade</th>
            <th class="celulas">Estoque</th>
            <th class="celulas">Ações</th>
        </tr>

        <?php if (count($produtos) > 0): ?>
            <?php foreach ($produtos as $produto): ?>
                <?php
                    $quantidade = (int) $produto['estoque'];

                    if ($quantidade > 10) {
                        $textoEstoque = 'Alto';
                        $cor_fundo = '#6CD486';
                    } elseif ($quantidade > 3) {
                        $textoEstoque = 'Médio';
                        $cor_fundo = '#FFCE52';
                    } elseif ($quantidade > 0) {
                        $textoEstoque = 'Baixo';
                        $cor_fundo = '#FF6366';
                    } else {
                        $textoEstoque = 'Zerado';
                        $cor_fundo = '#ff0004';
                    }
                ?>
                <tr class="linha">
                    <td class="celulas"><?= htmlspecialchars($produto['idprodutos']) ?></td>
                    <td class="celulas"><?= htmlspecialchars($produto['nome_prod']) ?></td>
                    <td class="celulas"><?= htmlspecialchars($produto['marca']) ?></td>
                    <td class="celulas"><?= htmlspecialchars($produto['nome_categoria']) ?></td>
                    <td class="celulas"><?= htmlspecialchars($produto['preco']) ?></td>
                    <td class="celulas"><?= date('d/m/Y', strtotime($produto['validade'])) ?></td>
                    <td><button style="background-color:<?= $cor_fundo ?>" class="estoque"><?= $textoEstoque ?></button></td>
                    <td class="celulas">
                        <div class="acoes">
                            <a href="dashboard.php?pagina=produtos_editar&id_prod=<?= $produto['idprodutos'] ?>">
                                <button type="button" class="editar">Editar</button>
                            </a>
                            <a href="dashboard.php?pagina=produtos_excluir&id_prod=<?= $produto['idprodutos'] ?>&categoria=<?= $produto['categorias_idcategorias'] ?>"
                               onclick="return confirm('Tem certeza que deseja excluir este produto?');">
                                <button type="button" class="excluir">Excluir</button>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr class="linha">
                <td class="celulas" colspan="8">Nenhum produto cadastrado.</td>
            </tr>
        <?php endif; ?>
    </table>
</main>
