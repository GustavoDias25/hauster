<?php

include 'side-bar.php';
require '../conexao.php';

$stmt = $conexao->query("SELECT * FROM produtos p, categorias c WHERE p.categorias_idcategorias = c.idcategorias ORDER BY idprodutos");
$produtos = $stmt->fetchAll();

$stmt2 = $conexao->query("SELECT * FROM categorias");
$categorias = $stmt2->fetchAll();

?>
<link rel="stylesheet" href="css/estoque.css">
<main class="tabela content fundo">
    <div class="cabecalho-tabela">
        <h1 class="titulo">Lista de produtos</h1>
        <a href="cadastrar.php" class="btn-cadastrar">Cadastrar produto</a>
    </div>

    <table class="table">
        <tr class="linha">
            <th class="celulas">ID</th>
            <th class="celulas">Nome</th>
            <th class="celulas">Marca</th>
            <th class="celulas">Categoria</th>
            <th class="celulas">Preço</th>
            <th class="celulas">Validade</th>
            <th class="celulas">Ações</th>
        </tr>

        <?php if (count($produtos) > 0): ?>
            <?php foreach ($produtos as $produto): ?>
                <?php
                    $quantidade = (int) $produto['estoque'];

                    if ($quantidade > 10) {
                        $textoEstoque = 'Alto';
                    } elseif ($quantidade <= 3) {
                        $textoEstoque = 'Baixo';
                    } else {
                        $textoEstoque = 'Médio';
                    }
                ?>
                <tr class="linha">
                    <td class="celulas"><?= htmlspecialchars($produto['idprodutos']) ?></td>
                    <td class="celulas"><?= htmlspecialchars($produto['nome_prod']) ?></td>
                    <td class="celulas"><?= htmlspecialchars($produto['marca']) ?></td>
                    <td class="celulas"><?= htmlspecialchars($produto['nome']) ?></td>
                    <td class="celulas"><?= htmlspecialchars($produto['preco']) ?></td>
                    <td class="celulas"><?= htmlspecialchars($produto['validade']) ?></td>
                    <td class="celulas">
                        </td>
                        <td class="acoes celulas">
                        <button>
                            <?= $textoEstoque?>
                        </button>
                        <a href="editar.php?id_prod=<?= $produto['idprodutos'] ?>&categoria=<?= $produto['nome'] ?>">
                            <button type="button">Editar</button>
                        </a>
                        <a href="excluir.php?id_prod=<?= $produto['idprodutos'] ?>&categoria=<?= $produto['categorias_idcategorias'] ?>" onclick="return confirm('Tem certeza que deseja excluir este produto?');">
                            <button type="button">Excluir</button>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr class="linha">
                <td class="celulas" colspan="6">Nenhum produto cadastrado.</td>
            </tr>
        <?php endif; ?>
    </table>
</main>
