<?php

include 'side-bar.php';
require '../conexao.php';

session_start();
 
// Bloqueia o acesso de quem não está logado/cadastrado
if (empty($_SESSION['logado']) || empty($_SESSION['estabelecimento_id'])) {
    header('Location: ../cadastro/login.php');
    exit;
}
 
$estabelecimento_id = $_SESSION['estabelecimento_id'];
$nome_fantasia = $_SESSION['nome_fantasia'];

$stmt = $conexao->prepare("SELECT * FROM produtos p, categorias c, estabelecimento e WHERE p.categorias_idcategorias = c.idcategorias and p.estabelecimento_idestabelecimento = e.idestabelecimento and p.estabelecimento_idestabelecimento = :estabelecimento_id ORDER BY idprodutos");
$stmt->bindParam(':estabelecimento_id', $estabelecimento_id);
$stmt->execute();
$produtos = $stmt->fetchAll();

foreach($produtos as $produto){
    if(new DateTime($produto['validade']) < new DateTime()){
        $stmt3 = $conexao->prepare("DELETE FROM `hauster_bd`.`produtos` WHERE (`idprodutos` = :id_prod) and (`categorias_idcategorias` = :idcategoria);");
        $stmt3->execute([':id_prod' => $produto['idprodutos'],
                    ':idcategoria' => $produto['categorias_idcategorias']]);
        $produtos = $stmt3->fetchAll();
        }
        }

$stmt2 = $conexao->query("SELECT * FROM categorias");
$categorias = $stmt2->fetchAll()



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
            <th class="celulas">Quantidade</th>
            <th class="celulas">Ações</th>
        </tr>

        <?php if (count($produtos) > 0): ?>
            <?php foreach ($produtos as $produto): ?>
                <?php
                    $quantidade = (int) $produto['estoque'];

                    if ($quantidade > 10) {
                        $textoEstoque = 'Alto';
                        $cor_fundo = '#6CD486';
                    } elseif ($quantidade <= 3) {
                        $textoEstoque = 'Baixo';
                        $cor_fundo = '#FF6366';

                    } else {
                        $textoEstoque = 'Médio';
                        $cor_fundo = '#FFCE52';
                    }
                ?>
                <tr class="linha">
                    <td class="celulas"><?= htmlspecialchars($produto['idprodutos']) ?></td>
                    <td class="celulas"><?= htmlspecialchars($produto['nome_prod']) ?></td>
                    <td class="celulas"><?= htmlspecialchars($produto['marca']) ?></td>
                    <td class="celulas"><?= htmlspecialchars($produto['nome']) ?></td>
                    <td class="celulas"><?= htmlspecialchars($produto['preco']) ?></td>
                    <td class="celulas"><?=  date('d/m/Y', strtotime($produto['validade'])) ?></td>
                    <td><button style="background-color:<?=$cor_fundo?>" class="estoque"><?= $textoEstoque?></button></td>
                    <td class="celulas">
                        <div class="acoes">
                            <a href="editar.php?id_prod=<?= $produto['idprodutos'] ?>&categoria=<?= $produto['nome'] ?>">
                                <button type="button" class="editar">Editar</button>
                            </a>
                            <a href="excluir.php?id_prod=<?= $produto['idprodutos'] ?>&categoria=<?= $produto['categorias_idcategorias']?>&estabelecimento<?=$produto['estabelecimento_idestabelecimento']?>" onclick="return confirm('Tem certeza que deseja excluir este produto?');">
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
