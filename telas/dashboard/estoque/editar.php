<?php

include 'side-bar.php';
include '../conexao.php';

$erro = '';
$id = isset($_GET['id_prod']) ? (int) $_GET['id_prod'] : 0;

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $categoria = $_POST['categorias'];
    $marca = trim($_POST['marca']);
    $quantidade_estoque = (int) $_POST['quantidade_estoque'];
    $preco = str_replace(',', '.', $_POST['preco']);

    if ($nome === '') {
        $erro = 'O nome do produto é obrigatório.';
    } else {
        try {
            $stmt = $conexao->prepare(
                "UPDATE produtos SET nome_prod = :nome, categorias_idcategorias = :categoria, marca = :marca, estoque = :quantidade_estoque, preco = :preco WHERE idprodutos = :id"
            );
            $stmt->execute([
                ':nome' => $nome,
                ':categoria' => $categoria,
                ':marca' => $marca,
                ':quantidade_estoque' => $quantidade_estoque,
                ':preco' => $preco,
                ':id' => $id,
            ]);

            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $erro = 'Erro ao atualizar produto: ' . $e->getMessage();
        }
    }
}

// Busca os dados atuais do produto para preencher o formulário
$stmt = $conexao->prepare("SELECT * FROM produtos p, categorias c WHERE p.categorias_idcategorias = c.idcategorias and p.idprodutos = :id ORDER BY idprodutos");
$stmt->execute([':id' => $id]);
$produto = $stmt->fetch();

if (!$produto) {
    header('Location: index.php');
    exit;
}

$stmt2 = $conexao->query("SELECT * FROM categorias");
$categorias = $stmt2->fetchAll();

?>
<link rel="stylesheet" href="css/cadastrar.css">
<main class="tabela content fundo">
    <h1 class="titulo">Editar produto</h1>

    <?php if ($erro): ?>
        <p class="erro"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <form method="POST" action="editar.php?id_prod=<?= $produto['idprodutos'] ?>" class="form-produto">
        <div class="campos">
            <div class="campo">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($produto['nome_prod']) ?>" required>
            </div>
            <div class="campo">
                <label for="validade">Validade</label>
                <input type="text" id="preco" name="preco" value="<?= htmlspecialchars($produto['validade']) ?>">
            </div>
            <div class="campo">
                <label for="marca">Marca</label>
                <input type="text" id="marca" name="marca" value="<?= htmlspecialchars($produto['marca']) ?>">
            </div>
            <div class="campo">
                <label for="quantidade_estoque">Quantidade em estoque</label>
                <input type="number" id="quantidade_estoque" name="quantidade_estoque" min="0" value="<?= htmlspecialchars($produto['estoque']) ?>" required>
            </div>
            <div class="campo">
                <label for="preco">Preço</label>
                <input type="text" id="preco" name="preco" value="<?= htmlspecialchars($produto['preco']) ?>">
            </div>
            <div class="campo">
                <label for="categorias">Categorias</label>
                <select name="categorias" id="categorias">
                    <?php if (count($categorias) > 0): ?>
                    <?php foreach ($categorias as $categoria): ?>
                    <option value="<?=$categoria['idcategorias'];?>" id="opcao"><?=$categoria['nome'];?></option>
                     <?php endforeach; ?>
                     <?php endif; ?>
                </select>
            </div>
            <div class="acoes campo">
                <button type="submit">Salvar alterações</button>
                <a href="index.php"><button type="button">Cancelar</button></a>
            </div>
        </div>
    </form>
</main>
