<?php
session_start();
require_once 'conexao.php';

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
    $sql = "SELECT idestabelecimento, cnpj, senha 
            FROM estabelecimento 
            WHERE cnpj = :cnpj";

    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':cnpj', $cnpj, PDO::PARAM_STR);
    $stmt->execute();

    $estabelecimento = $stmt->fetch();

// var_dump($cnpj);           // o que realmente está sendo buscado
// var_dump($estabelecimento); // o que voltou do banco
// exit; // pra parar aqui e você ver o resultado


    if ($estabelecimento and password_verify($senha, $estabelecimento['senha'])) {
        // Login OK — regenera o ID de sessão por segurança
        session_regenerate_id(true);

        $_SESSION['estabelecimento_id'] = $estabelecimento['idestabelecimento'];
        $_SESSION['logado'] = true;

        header('Location: ../dashboard/index.php');


        exit;
    } else {
        $_SESSION['erro_login'] = 'CNPJ ou senha incorretos.';
        header('Location: login.php');
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['erro_login'] = 'Erro ao processar o login. Tente novamente.';
    header('Location: login.php');
    exit;
}