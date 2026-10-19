<?php
$id_pedido = isset($_GET['id_pedido']) ? (int) $_GET['id_pedido'] : 0;
$estabelecimento_id = $_SESSION['estabelecimento_id'];

if ($id_pedido > 0) {
    try {
        $stmt = $conexao->prepare(
            "DELETE FROM itens_pedido
             WHERE id_pedido = :id_pedido
               AND estabelecimento_idestabelecimento = :estab"
        );
        $stmt->execute([':id_pedido' => $id_pedido, ':estab' => $estabelecimento_id]);
    } catch (PDOException $e) {
        $erro = 'Erro ao excluir pedido: ' . $e->getMessage();
    }
}

header('Location: dashboard.php?pagina=pedidos');
exit;