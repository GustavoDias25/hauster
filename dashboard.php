<link rel="shortcut icon" href="assets/favicon.png" type="image/x-icon">

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

// Controle de acesso por cargo: se o cargo não pode ver essa página, redireciona
include_once "includes/permissoes.php";
exigir_permissao($pagina);

// Cada página carrega só o CSS que ela realmente usa,
// pra classes com o mesmo nome (.tabela, .acoes, .fundo...) não colidirem entre telas
$css_por_pagina = [
    'home'               => ['dashboard/css/principal.css'],
    'produtos'           => ['dashboard/estoque/css/estoque.css'],
    'produtos_cadastrar' => ['dashboard/estoque/css/cadastrar.css'],
    'editar-perfil'      => ['dashboard/css/editar-perfil.css'],
    'produtos_editar'    => ['dashboard/estoque/css/cadastrar.css'],
    'vendas'             => ['dashboard/vendas/css/pedidos.css'],
    'vendas_cadastrar'   => ['dashboard/vendas/css/cadastrar.css'],
    'vendas_editar'    => ['dashboard/vendas/css/cadastrar.css'],
    'funcionarios'            => ['dashboard/estoque/css/estoque.css', 'dashboard/funcionarios/css/funcionarios.css'],
    'funcionarios_detalhar'   => ['dashboard/estoque/css/estoque.css', 'dashboard/funcionarios/css/funcionarios.css'],
    'funcionarios_cadastrar'  => ['dashboard/estoque/css/cadastrar.css', 'dashboard/funcionarios/css/funcionarios.css'],
    'funcionarios_editar'     => ['dashboard/estoque/css/cadastrar.css', 'dashboard/funcionarios/css/funcionarios.css'],
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
    <!-- Responsividade (carregar por último) -->
    <link rel="stylesheet" href="css/responsivo-dashboard.css">
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
    case "logout":
        include "dashboard/logout.php";
        break;
    case "editar-perfil":
        include "dashboard/editar-perfil.php";
        break;
    case "excluir-conta":
        include "dashboard/excluir-conta.php";
        break;
    case "vendas":
        include "dashboard/vendas/index.php";
        break;
    case "vendas_cadastrar":
        include "dashboard/vendas/cadastrar.php";
        break;
    case "vendas_editar":
        include "dashboard/vendas/editar.php";
        break;
    case "vendas_excluir":
        include "dashboard/vendas/excluir.php";
        break;
    case "funcionarios":
        include "dashboard/funcionarios/index.php";
        break;
    case "funcionarios_cadastrar":
        include "dashboard/funcionarios/cadastrar.php";
        break;
    case "funcionarios_editar":
        include "dashboard/funcionarios/editar.php";
        break;
    case "funcionarios_excluir":
        include "dashboard/funcionarios/excluir.php";
        break;
    case "funcionarios_detalhar":
        include "dashboard/funcionarios/detalhar.php";
        break;

    default:
        echo "<main class='content'><div class='alert alert-primary'>Em desenvolvimento!</div></main>";
}
?>

</body>
</html>