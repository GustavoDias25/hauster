<?php

include '../conexao.php';

$id_prod = isset($_GET['id_prod']) ? (int) $_GET['id_prod'] : 0;
$idcategoria = isset($_GET['categoria']) ? (int) $_GET['categoria'] : 0;

if ($id_prod > 0) {
    $stmt = $conexao->prepare("DELETE FROM `hauster_bd`.`produtos` WHERE (`idprodutos` = :id_prod) and (`categorias_idcategorias` = :idcategoria);");
    $stmt->execute([':id_prod' => $id_prod,
                    ':idcategoria' => $idcategoria]);
}

header('Location: index.php');
exit;
