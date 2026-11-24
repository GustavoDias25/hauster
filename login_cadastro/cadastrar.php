<?php
include '../includes/conexao.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nome = trim($_POST['nome']);
$cnpj = trim($_POST['CNPJ']);
$email = trim($_POST['email']);
$senha = trim($_POST['senha']);
$conf_senha = trim($_POST['conf_senha']);

// deixa só os números do CNPJ
$cnpj = preg_replace('/\D/', '', $cnpj);

// 1) Confere se as senhas são iguais
if ($senha != $conf_senha) {
    $_SESSION['erro_cad'] = 'Senhas estão diferentes';
    header('Location: index.php');
    exit();
}

// 2) Confere se já existe CNPJ ou e-mail cadastrado
try {
    $stmt = $conexao->prepare(
        'SELECT cnpj, email FROM estabelecimento WHERE cnpj = :cnpj OR email = :email'
    );
    $stmt->bindParam(':cnpj', $cnpj);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $existentes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $cnpj_existe = false;
    $email_existe = false;

    foreach ($existentes as $linha) {
        if ($linha['cnpj'] === $cnpj) {
            $cnpj_existe = true;
        }
        if (strtolower($linha['email']) === strtolower($email)) {
            $email_existe = true;
        }
    }

    if ($cnpj_existe && $email_existe) {
        $_SESSION['erro_cad'] = 'Este CNPJ e este e-mail já estão cadastrados';
    } elseif ($cnpj_existe) {
        $_SESSION['erro_cad'] = 'Este CNPJ já está cadastrado';
    } elseif ($email_existe) {
        $_SESSION['erro_cad'] = 'Este e-mail já está cadastrado';
    }

    if ($cnpj_existe || $email_existe) {
        header('Location: index.php');
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['erro_cad'] = 'Erro ao verificar os dados: ' . $e->getMessage();
    header('Location: index.php');
    exit();
}

// 3) Só agora que os dados estão ok, trata a senha e a foto
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

$foto_nome = '../assets/foto_padrao.png';

if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $permitidos = ['image/jpeg', 'image/png', 'image/webp'];
    $tipo = mime_content_type($_FILES['foto']['tmp_name']);

    if (in_array($tipo, $permitidos, true)) {
        $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $novo_nome = uniqid('estab_', true) . '.' . $extensao;
        $pasta_destino = __DIR__ . '/../assets/';
        $destino = $pasta_destino . $novo_nome;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
            $foto_nome = $novo_nome;
        }
    }
}

// 4) Cadastra
try {
    // planos_idplanos é obrigatório no banco: começa no primeiro plano
    // e é trocado quando a pessoa escolhe e conclui em planos.php / pagamento.php
    $plano_inicial = $conexao->query('SELECT MIN(idplanos) FROM planos')->fetchColumn();

    $stmt = $conexao->prepare(
        'INSERT INTO estabelecimento (nome_fantasia, cnpj, email, senha, foto, planos_idplanos) 
         VALUES (:nome, :cnpj, :email, :senha, :foto, :plano)'
    );
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cnpj', $cnpj);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha_hash);
    $stmt->bindParam(':foto', $foto_nome);
    $stmt->bindParam(':plano', $plano_inicial);
    $stmt->execute();

    $_SESSION['estabelecimento_id'] = $conexao->lastInsertId();
    $_SESSION['nome_fantasia'] = $nome;
    $_SESSION['foto'] = $foto_nome;
    $_SESSION['logado'] = true;

    header('Location: planos.php');
    exit;
} catch (PDOException $e) {
    $_SESSION['erro_cad'] = 'Erro ao cadastrar usuário: ' . $e->getMessage();
    header('Location: index.php');
    exit();
}
?>
