<?php
session_start();
require_once '../includes/conexao.php';

// Só processa se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$cnpj = trim($_POST['CNPJ'] ?? '');
$senha = trim($_POST['senha'] ?? '');

// Validação básica de entrada
if ($cnpj === '' || $senha === '') {
    $_SESSION['erro_login'] = 'Preencha CNPJ e senha.';
    header('Location: login.php');
    exit;
}

$cnpj = preg_replace('/\D/', '', $cnpj);

try {
    $sql = "SELECT idestabelecimento, cnpj, senha, nome_fantasia, foto 
        FROM estabelecimento 
        WHERE cnpj = :cnpj";

    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':cnpj', $cnpj, PDO::PARAM_STR);
    $stmt->execute();

    $estabelecimento = $stmt->fetch();

    if ($estabelecimento and password_verify($senha, $estabelecimento['senha'])) {
    session_regenerate_id(true);

    $_SESSION['logado'] = true;
    $_SESSION['estabelecimento_id'] = $estabelecimento['idestabelecimento'];
    $_SESSION['nome_fantasia'] = $estabelecimento['nome_fantasia'];
    $_SESSION['foto'] = $estabelecimento['foto'];
    header('Location: ../dashboard.php?pagina=home');
    exit;
}
else {
    $_SESSION['erro_login'] = "CNPJ ou senha incorretos";
    header('Location: login.php');
    exit;
}
    }
catch (PDOException $e) {
    $_SESSION['erro_login'] = "Erro ao logar";
    header('Location: login.php');
    exit;
}

?>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>