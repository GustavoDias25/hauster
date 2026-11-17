<?php

$estabelecimento_id = $_SESSION['estabelecimento_id'];

    $stmt02 = $conexao->prepare("SELECT * FROM estabelecimento WHERE idestabelecimento = :id");
    $stmt02->execute([':id' => $estabelecimento_id]);
    $perfil = $stmt02->fetch();

    // Se o id da sessão não existe mais na tabela (ex.: banco recriado), avisa em vez de dar erro
    if (!$perfil) {
        echo '<main class="content"><h1 class="perfil-titulo">Editar Perfil</h1>'
           . '<p>Não encontrei este estabelecimento no banco. Saia e entre novamente.</p>'
           . '<a href="dashboard.php?pagina=logout">Sair</a></main>';
        return;
    }

    // Planos disponíveis (para o select de plano)
    $planos = $conexao->query("SELECT idplanos, nome, preco FROM planos ORDER BY preco")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_fantasia = trim($_POST['nome_fantasia']);
    $email = trim($_POST['email']);
    $senha_atual = trim($_POST['senha']) ?? '';
    $nova_senha = trim($_POST['nova_senha']) ?? '';
    $foto = $perfil['foto'];
    $plano_id = (int) ($_POST['plano'] ?? 0);
    $erro = '';

    // O plano escolhido precisa existir na tabela planos
    if (!in_array($plano_id, array_column($planos, 'idplanos'))) {
        $erro = 'Plano inválido';
    }

    if ($erro === '' && $nova_senha !== '' && !password_verify($senha_atual, $perfil['senha'])) {
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
            $sql = "UPDATE estabelecimento SET nome_fantasia = :nome_fantasia, email = :email, senha = :senha, foto = :foto, planos_idplanos = :plano WHERE idestabelecimento = :id";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(":nome_fantasia", $nome_fantasia);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":senha", $senha_hash);
            $stmt->bindParam(":foto", $foto);
            $stmt->bindParam(":plano", $plano_id);
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

// Nome do plano atual, para mostrar na lateral
$plano_atual = '';
foreach ($planos as $p) {
    if ($p['idplanos'] == $perfil['planos_idplanos']) {
        $plano_atual = $p['nome'];
    }
}
$foto_perfil = !empty($perfil['foto']) ? $perfil['foto'] : 'foto_padrao.png';
?>

<main class="content">
    <div class="perfil-pagina">
        <h1 class="perfil-titulo">Editar Perfil</h1>
        <?php if (!empty($_SESSION['erro'])): ?>
            <script>
                alert(<?= json_encode($_SESSION['erro']) ?>);
            </script>
            <?php unset($_SESSION['erro']); ?>
        <?php endif; ?>

        <form method="POST" action="dashboard.php?pagina=editar-perfil&idestabelecimento=<?= $perfil['idestabelecimento'] ?>" enctype="multipart/form-data">

            <div class="perfil-grid">
            <div class="perfil-secao">
                <div class="perfil-secao-info">
                    <h3>Foto</h3>
                    <p>Aparece no canto superior do sistema.</p>
                </div>
                <div class="perfil-secao-campos">
                    <div class="perfil-foto-linha">
                        <img src="assets/<?= htmlspecialchars($foto_perfil) ?>" alt="Foto do estabelecimento" class="perfil-foto">
                        <input type="file" name="foto" id="foto" accept="image/png, image/jpeg, image/webp">
                    </div>
                </div>
            </div>

            <div class="perfil-secao">
                <div class="perfil-secao-info">
                    <h3>Dados</h3>
                    <p>Nome e e-mail do estabelecimento.</p>
                </div>
                <div class="perfil-secao-campos">
                    <div class="perfil-campo">
                        <label for="nome_fantasia">Nome fantasia</label>
                        <input type="text" id="nome_fantasia" name="nome_fantasia" required value="<?= htmlspecialchars($perfil['nome_fantasia']) ?>">
                    </div>
                    <div class="perfil-campo">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($perfil['email']) ?>">
                    </div>
                </div>
            </div>

            <div class="perfil-secao">
                <div class="perfil-secao-info">
                    <h3>Plano</h3>
                    <p>Plano atual: <?= htmlspecialchars($plano_atual) ?></p>
                </div>
                <div class="perfil-secao-campos">
                    <div class="perfil-campo">
                        <label for="plano">Escolha o plano</label>
                        <select id="plano" name="plano" required>
                            <?php foreach ($planos as $p): ?>
                                <option value="<?= $p['idplanos'] ?>" <?= $p['idplanos'] == $perfil['planos_idplanos'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($p['nome']) ?> - R$ <?= number_format($p['preco'], 2, ',', '.') ?>/mês
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="perfil-secao">
                <div class="perfil-secao-info">
                    <h3>Senha</h3>
                    <p>Deixe em branco para manter a senha atual.</p>
                </div>
                <div class="perfil-secao-campos">
                    <div class="perfil-campo">
                        <label for="senha">Senha atual</label>
                        <input type="password" id="senha" name="senha" placeholder="Digite sua senha atual">
                    </div>
                    <div class="perfil-campo">
                        <label for="nova_senha">Nova senha</label>
                        <input type="password" id="nova_senha" name="nova_senha" placeholder="Digite uma nova senha">
                    </div>
                </div>
            </div>

            </div>

            <div class="perfil-barra">
                <button type="submit" class="perfil-salvar">Salvar alterações</button>
                <a href="dashboard.php?pagina=home" class="perfil-cancelar">Cancelar</a>
            </div>
        </form>
    </div>
</main>