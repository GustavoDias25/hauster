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

$cnpj = preg_replace('/\D/', '', $cnpj);

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

if($senha != $conf_senha){
    $_SESSION['erro_cad'] = 'Senhas estão diferentes';
    header('Location: index.php');
    exit();
}

try {
    $stmt = $conexao->prepare(
        'INSERT INTO `db_hauster`.`estabelecimento` (`nome_fantasia`, `cnpj`, `email`, `senha`, `foto`) 
         VALUES (:nome, :cnpj, :email, :senha, :foto)'
    );
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cnpj', $cnpj);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha_hash);
    $stmt->bindParam(':foto', $foto_nome);
    $stmt->execute();

    $_SESSION['estabelecimento_id'] = $conexao->lastInsertId();
    $_SESSION['nome_fantasia'] = $nome;
    $_SESSION['foto'] = $foto_nome;
    $_SESSION['logado'] = true;

    header('Location: planos.php');
    exit;
} catch (PDOException $e) {
    $_SESSION['erro_cad'] = 'Error ao cadastrar usuário' . $e->getMessage();
}

?>
