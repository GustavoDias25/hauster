<?php

include '../conexao.php';

session_start();
 
// Bloqueia o acesso de quem não está logado/cadastrado
if (empty($_SESSION['logado']) || empty($_SESSION['estabelecimento_id'])) {
    header('Location: ../cadastro/login.php');
    exit;
}


$estabelecimento_id = $_SESSION['estabelecimento_id'];
$id_prod = isset($_GET['id_prod']) ? (int) $_GET['id_prod'] : 0;
$idcategoria = isset($_GET['categoria']) ? (int) $_GET['categoria'] : 0;

if ($id_prod > 0) {
    $stmt = $conexao->prepare("DELETE FROM `db_hauster`.`produtos` WHERE (`idprodutos` = :idprod) and (`categorias_idcategorias` = :idcategoria) and (`estabelecimento_idestabelecimento` = :idestabelecimento);");
    $stmt->bindParam(":idprod", $id_prod);
    $stmt->bindParam(":idcategoria", $idcategoria);
    $stmt->bindParam(":idestabelecimento", $estabelecimento_id);
    $stmt->execute();
}

header('Location: index.php');
exit;
