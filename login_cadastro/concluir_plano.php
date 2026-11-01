<?php
// Chamado pelo botão "Concluir" do pagamento.php: salva o plano escolhido no estabelecimento
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../includes/conexao.php';

if (empty($_SESSION['logado']) || empty($_SESSION['estabelecimento_id'])) {
    header('Location: index.php');
    exit;
}

$id_plano = (int) ($_GET['plano'] ?? 0);

// Confere se o plano existe antes de salvar
$stmt = $conexao->prepare('SELECT idplanos FROM planos WHERE idplanos = :id');
$stmt->execute([':id' => $id_plano]);

if (!$stmt->fetch()) {
    header('Location: planos.php');
    exit;
}

$stmt = $conexao->prepare('UPDATE estabelecimento SET planos_idplanos = :plano WHERE idestabelecimento = :id');
$stmt->execute([
    ':plano' => $id_plano,
    ':id'    => $_SESSION['estabelecimento_id'],
]);

header('Location: ../dashboard.php?pagina=home');
exit;
