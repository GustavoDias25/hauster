<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<?php
include '../includes/conexao.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nome = trim($_POST['nome']);
$cnpj = trim($_POST['CNPJ']);
$email = trim($_POST['email']);
$senha = trim($_POST['senha']);

// CNPJ no banco é INT UNSIGNED — mantém só dígitos
$cnpj = preg_replace('/\D/', '', $cnpj);

// Gera o hash da senha — NUNCA salve senha em texto puro
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// --- Upload da foto (opcional) ---
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
            $foto_nome = $novo_nome; // salva só o nome do arquivo no banco
        }
    }
}

if($conf_senha != $senha){
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

    // Guarda os dados do estabelecimento recém-cadastrado na sessão
    $_SESSION['estabelecimento_id'] = $conexao->lastInsertId();
    $_SESSION['nome_fantasia'] = $nome;
    $_SESSION['foto'] = $foto_nome;
    $_SESSION['logado'] = true;

    header('Location: ../dashboard.php?pagina=home');
    exit;
} catch (PDOException $e) {
    $_SESSION['erro_cad'] = 'Error ao cadastrar usuário' . $e->getMessage();
}

?>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
