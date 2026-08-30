<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "includes/conexao.php";

$pagina = $_GET["pagina"] ?? "home";

// Bloqueia acesso de quem não está logado - manda pro app de login separado
if (empty($_SESSION['logado']) || empty($_SESSION['estabelecimento_id'])) {
    header('Location: login_cadastro/login.php');
    exit;
}

// Cada página carrega só o CSS que ela realmente usa,
// pra classes com o mesmo nome (.tabela, .acoes, .fundo...) não colidirem entre telas
$css_por_pagina = [
    'home'               => ['dashboard/css/principal.css'],
    'produtos'           => ['dashboard/estoque/css/estoque.css'],
    'produtos_cadastrar' => ['dashboard/estoque/css/cadastrar.css'],
    'produtos_editar'    => ['dashboard/estoque/css/cadastrar.css'],
];
$css_da_pagina = $css_por_pagina[$pagina] ?? [];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hauster - Dashboard</title>
    <link rel="stylesheet" href="css/side-bar.css">
    <?php foreach ($css_da_pagina as $css): ?>
        <link rel="stylesheet" href="<?= htmlspecialchars($css) ?>">
    <?php endforeach; ?>
</head>
<body class="dashboard">

<?php include "includes/side-bar.php"; ?>
<?php include "includes/topo.php"; ?>

<?php
switch ($pagina) {
    case "home":
        include "dashboard/index.php";
        break;

    case "produtos":
        include "dashboard/estoque/index.php";
        break;

    case "produtos_cadastrar":
        include "dashboard/estoque/cadastrar.php";
        break;

    case "produtos_editar":
        include "dashboard/estoque/editar.php";
        break;

    case "produtos_excluir":
        include "dashboard/estoque/excluir.php";
        break;

    default:
        echo "<main class='content'><div class='alert alert-danger'>Página não encontrada!</div></main>";
}
?>

</body>
</html>
