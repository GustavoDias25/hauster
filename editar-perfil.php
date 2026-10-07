<?php

$estabelecimento_id = $_SESSION['estabelecimento_id'];

    $stmt02 = $conexao->prepare("SELECT * FROM estabelecimento WHERE idestabelecimento = :id");
    $stmt02->execute([':id' => $estabelecimento_id]);
    $perfil = $stmt02->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_fantasia = trim($_POST['nome_fantasia']);
    $email = $_POST['email'];
    $senha = !empty($_POST['conf_senha']) ? $_POST['conf_senha'] : $perfil['senha'];
    $foto = $perfil['foto']; // mantém a foto atual caso nenhuma nova seja enviada

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);


    if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $permitidos = ['image/jpeg', 'image/png', 'image/webp'];
        $tipo = mime_content_type($_FILES['foto']['tmp_name']);

        if (in_array($tipo, $permitidos, true)) {
            $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            $novo_nome = uniqid('estab_', true) . '.' . $extensao;
            $pasta_destino = __DIR__ . '/../assets/';
            $destino = $pasta_destino . $novo_nome;

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
                $foto = $novo_nome;
            }
        }
    }

    try {
        $sql = "UPDATE estabelecimento SET nome_fantasia = :nome_fantasia, email = :email, senha = :senha, foto = :foto WHERE idestabelecimento = :id";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(":nome_fantasia", $nome_fantasia);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":senha", $senha_hash);
        $stmt->bindParam(":foto", $foto);
        $stmt->bindParam(":id", $estabelecimento_id);
        $stmt->execute();

        $_SESSION['nome_fantasia'] = $nome_fantasia;
        $_SESSION['foto'] = $foto;

        header('Location: dashboard.php?pagina=produtos');
        exit;
    } catch (PDOException $e) {
        $_SESSION['erro'] = 'Erro ao atualizar perfil: ' . $e->getMessage();
    }
}

?>

<main class="tabela content fundo">
    <h1 class="titulo">Editar produto</h1>
            <?php if (!empty($_SESSION['erro'])): ?>    
                <p class="erro"><?= htmlspecialchars($_SESSION['erro']) ?></p>
                <?php unset($_SESSION['erro']); ?>
            <?php endif; ?>
   
<link rel="stylesheet" href="estoque/css/cadastrar.css">
    <form method="POST" action="dashboard.php?pagina=editar-perfil&idestabelecimento=<?=$perfil['idestabelecimento']?>" class="form-produto" enctype="multipart/form-data">
        <div class="campos">
            <div class="campo">
                <label for="nome_fantasia">Nome fantasia</label>
                <input type="text" id="nome_fantasia" name="nome_fantasia" required value="<?=htmlspecialchars($perfil['nome_fantasia'])?>">
            </div>
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?=htmlspecialchars($perfil['email'])?>">
            </div>
            <div class="campo">
                <label for="senha">Senha Atual</label>
                <input type="password" id="senha" name="senha" placeholder="Digite sua senha atual">
            </div>
            <div class="campo">
                <label for="foto">Foto</label>
                <input type="file" name="foto" id="foto" accept="image/png, image/jpeg, image/webp">
            </div>
            <div class="campo">
                <label for="conf_senha">Nova Senha</label>
                <input type="password" id="conf_senha" name="conf_senha" placeholder="Digite uma nova senha">
            </div>
            <div class="acoes campo">
                <button type="submit">Salvar alterações</button>
                <a href="dashboard.php?pagina=produtos"><button type="button">Cancelar</button></a>
            </div>
        </div>
    </form>
</main>