<?php
$estabelecimento_id = $_SESSION['estabelecimento_id'];
$id_prod = isset($_GET['id_prod']) ? (int) $_GET['id_prod'] : 0;
$id_categoria = isset($_GET['categoria']) ? (int) $_GET['categoria'] : 0;

if ($id_prod > 0) {
    $stmt = $conexao->prepare(
        "DELETE FROM produtos
         WHERE idprodutos = :idprod
           AND categorias_idcategorias = :idcategoria
           AND estabelecimento_idestabelecimento = :idestabelecimento"
    );
    $stmt->bindParam(":idprod", $id_prod);
    $stmt->bindParam(":idcategoria", $id_categoria);
    $stmt->bindParam(":idestabelecimento", $estabelecimento_id);
    $stmt->execute();
}

header('Location: dashboard.php?pagina=produtos');
exit;
