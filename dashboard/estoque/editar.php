<?php
$erro = '';
$id = isset($_GET['id_prod']) ? (int) $_GET['id_prod'] : 0;

if ($id <= 0) {
    header('Location: dashboard.php?pagina=produtos');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $validade = $_POST['validade'];
    $categoria = $_POST['categorias'];
    $marca = trim($_POST['marca']);
    $quantidade_estoque = (int) $_POST['quantidade_estoque'];
    $preco = str_replace(',', '.', $_POST['preco']);

    if ($nome === '') {
        $erro = 'O nome do produto é obrigatório.';
    } else {
        try {
            $sql = "UPDATE produtos SET nome_prod = :nome, validade = :validade, categorias_idcategorias = :categoria, marca = :marca, estoque = :quantidade_estoque, preco = :preco WHERE idprodutos = :id";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(":nome", $nome);
            $stmt->bindParam(":validade", $validade);
            $stmt->bindParam(":categoria", $categoria);
            $stmt->bindParam(":marca", $marca);
            $stmt->bindParam(":quantidade_estoque", $quantidade_estoque);
            $stmt->bindParam(":preco", $preco);
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            header('Location: dashboard.php?pagina=produtos');
            exit;
        } catch (PDOException $e) {
            $erro = 'Erro ao atualizar produto: ' . $e->getMessage();
        }
    }
}

$stmt = $conexao->prepare(
    "SELECT p.*, c.nome AS nome_categoria
     FROM produtos p
     JOIN categorias c ON p.categorias_idcategorias = c.idcategorias
     WHERE p.idprodutos = :id"
);
$stmt->execute([':id' => $id]);
$produto = $stmt->fetch();

if (!$produto) {
    header('Location: dashboard.php?pagina=produtos');
    exit;
}

$stmt2 = $conexao->query("SELECT * FROM categorias");
$categorias = $stmt2->fetchAll();
?>
<main class="tabela content fundo">
    <h1 class="titulo">Editar produto</h1>

    <?php if ($erro): ?>
        <p class="erro"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <form method="POST" action="dashboard.php?pagina=produtos_editar&id_prod=<?= $produto['idprodutos'] ?>" class="form-produto">
        <div class="campos">
            <div class="campo">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($produto['nome_prod']) ?>" required>
            </div>
            <div class="campo">
                <label for="validade">Validade</label>
                <input type="date" id="validade" name="validade" value="<?= htmlspecialchars($produto['validade']) ?>">
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
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= $categoria['idcategorias'] ?>" <?= $categoria['idcategorias'] == $produto['categorias_idcategorias'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($categoria['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="acoes campo">
                <button type="submit">Salvar alterações</button>
                <a href="dashboard.php?pagina=produtos"><button type="button">Cancelar</button></a>
            </div>
        </div>
    </form>
</main>
