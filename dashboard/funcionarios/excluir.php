<?php
$estabelecimento_id = $_SESSION['estabelecimento_id'];
$id_func = isset($_GET['id_func']) ? (int) $_GET['id_func'] : 0;

if ($id_func > 0) {
    try {
        // As vendas dele continuam existindo: o banco só limpa o campo "funcionário" delas (ON DELETE SET NULL)
        $stmt = $conexao->prepare(
            "DELETE FROM funcionarios
             WHERE idfuncionarios = :id_func
               AND estabelecimento_idestabelecimento = :estab"
        );
        $stmt->execute([':id_func' => $id_func, ':estab' => $estabelecimento_id]);
    } catch (PDOException $e) {
        $_SESSION['erro'] = 'Erro ao excluir funcionário: ' . $e->getMessage();
    }
}

header('Location: dashboard.php?pagina=funcionarios');
exit;
