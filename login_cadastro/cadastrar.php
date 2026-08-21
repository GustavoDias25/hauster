<?php
session_start();
require 'conexao.php';

$nome = trim($_POST['nome']);
$cnpj = trim($_POST['CNPJ']);
$email = trim($_POST['email']);
$senha = trim($_POST['senha']);

// CNPJ no banco é INT UNSIGNED — mantém só dígitos
$cnpj = preg_replace('/\D/', '', $cnpj);

// Gera o hash da senha — NUNCA salve senha em texto puro
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

try {
    $stmt = $conexao->prepare(
        'INSERT INTO `db_hauster`.`estabelecimento` (`nome_fantasia`, `cnpj`, `email`, `senha`) 
         VALUES (:nome, :cnpj, :email, :senha)'
    );
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cnpj', $cnpj);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha_hash);
    $stmt->execute();

    // Guarda os dados do estabelecimento recém-cadastrado na sessão
    $_SESSION['estabelecimento_id'] = $conexao->lastInsertId();
    $_SESSION['nome_fantasia'] = $nome;
    $_SESSION['logado'] = true;

    header('Location: ../dashboard/index.php');
    exit;
} catch (PDOException $e) {
    echo 'Erro ao cadastrar estabelecimento: ' . $e->getMessage();
}