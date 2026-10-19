<?php
$estabelecimento_id = $_SESSION['estabelecimento_id'];
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $categoria = trim($_POST['categorias']);
    $validade = $_POST['validade'];
    $marca = trim($_POST['marca']);
    $estoque = (int) $_POST['quantidade_estoque'];
    $preco = str_replace(',', '.', $_POST['preco']);

    if ($nome === '') {
        $erro = 'O nome do produto é obrigatório.';
    } else {
        try {
            $stmt = $conexao->prepare(
                "INSERT INTO produtos (nome_prod, categorias_idcategorias, validade, marca, estoque, preco, estabelecimento_idestabelecimento)
                 VALUES (:nome, :categoria, :validade, :marca, :estoque, :preco, :idestabelecimento)"
            );
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':categoria', $categoria);
            $stmt->bindParam(':validade', $validade);
            $stmt->bindParam(':marca', $marca);
            $stmt->bindParam(':estoque', $estoque);
            $stmt->bindParam(':preco', $preco);
            $stmt->bindParam(':idestabelecimento', $estabelecimento_id);
            $stmt->execute();

            header('Location: dashboard.php?pagina=produtos');
            exit;
        } catch (PDOException $e) {
            $erro = 'Erro ao cadastrar produto: ' . $e->getMessage();
        }
    }
}

$stmt2 = $conexao->query("SELECT * FROM categorias");
$categorias = $stmt2->fetchAll();
?>
<main class="fundo content">

    <?php if ($erro): ?>
        <p class="erro"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <h1 class="titulo">Cadastrar produto</h1>
    <form method="POST" action="dashboard.php?pagina=produtos_cadastrar" class="form-produto">
        <div class="campos">
            <div class="campo">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" placeholder="Nome do produto" required>
            </div>
            <div class="campo">
                <label for="validade">Validade</label>
                <input type="date" id="validade" min="<?= date('Y-m-d') ?>" name="validade" required>
            </div>
            <div class="campo">
                <label for="marca">Marca</label>
                <input type="text" id="marca" name="marca" placeholder="Marca do produto" required>
            </div>
            <div class="campo">
                <label for="quantidade_estoque">Quantidade em estoque</label>
                <input type="number" id="quantidade_estoque" name="quantidade_estoque" min="0" placeholder="Quantidade em estoque" required>
            </div>
            <div class="campo">
                <label for="preco">Preço</label>
                <input type="number" step="0.01" id="preco" name="preco" placeholder="Preço do produto" required>
            </div>
            <div class="campo">
                <label for="categorias">Categorias</label>
                <select name="categorias" id="categorias">
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= $categoria['idcategorias'] ?>"><?= htmlspecialchars($categoria['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="acoes campo">
                <button type="submit">Cadastrar</button>
                <a href="dashboard.php?pagina=produtos"><button type="button" id="cancelar">Cancelar</button></a>
            </div>
        </div>
    </form>
</main>
