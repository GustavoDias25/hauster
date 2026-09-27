<?php
$estabelecimento_id = $_SESSION['estabelecimento_id'];

    $stmt = $conexao->prepare(
        "DELETE FROM estabelecimento
         WHERE idestabelecimento = :idestabelecimento"
    );
    $stmt->bindParam(":idestabelecimento", $estabelecimento_id);
    $stmt->execute();
    session_destroy();

header('Location: login_cadastro/index.php');
exit;
