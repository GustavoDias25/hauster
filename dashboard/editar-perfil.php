<?php

$estabelecimento_id = $_SESSION['estabelecimento_id'];

    $stmt02 = $conexao->prepare("SELECT * FROM estabelecimento WHERE idestabelecimento = :id");
    $stmt02->execute([':id' => $estabelecimento_id]);
    $perfil = $stmt02->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_fantasia = trim($_POST['nome_fantasia']);
    $email = $_POST['email'];
    $senha_atual = $_POST['senha'] ?? '';
    $nova_senha = $_POST['nova_senha'] ?? '';
    $foto = $perfil['foto'];
    $erro = '';

    if ($nova_senha !== '' && !password_verify($senha_atual, $perfil['senha'])) {
        $erro = 'Senha atual errada';
    }

    if ($erro === '') {
        $senha_hash = ($nova_senha !== '')
            ? password_hash($nova_senha, PASSWORD_DEFAULT)
            : $perfil['senha'];

        if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $permitidos = ['image/jpeg', 'image/png', 'image/webp'];
            $tipo = mime_content_type($_FILES['foto']['tmp_name']);

            if (in_array($tipo, $permitidos, true)) {
                $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
                $novo_nome = uniqid('estab_', true) . '.' . $extensao;
                $destino = __DIR__ . '/../assets/' . $novo_nome;

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

            header('Location: dashboard.php?pagina=home');
            exit;
        } catch (PDOException $e) {
            $erro = 'Erro ao atualizar perfil: ' . $e->getMessage();
        }
    }

    if ($erro !== '') {
        $_SESSION['erro'] = $erro;
    }
}
?>

<main class="tabela content fundo">
    <h1 class="titulo">Editar Perfil</h1>
            <?php if (!empty($_SESSION['erro'])): ?>
                <script>
                    alert(<?= json_encode($_SESSION['erro']) ?>);
                </script>
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
                <label for="nova_senha">Nova Senha</label>
                <input type="password" id="nova_senha" name="nova_senha" placeholder="Digite uma nova senha">
            </div>
            <div class="acoes campo">
                <button type="submit">Salvar alterações</button>
                <a href="dashboard.php?pagina=home"><button type="button" id="cancelar">Cancelar</button></a>
            </div>
        </div>
    </form>
</main>