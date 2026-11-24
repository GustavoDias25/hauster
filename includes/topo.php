<?php
$titulos = [
    'home'               => 'Dashboard',
    'produtos'           => 'Produtos',
    'produtos_cadastrar' => 'Cadastrar produto',
    'produtos_editar'    => 'Editar produto',
    'pets'               => 'Pets',
    'clientes'           => 'Clientes',
    'funcionarios'       => 'Funcionários',
    'funcionarios_cadastrar' => 'Cadastrar funcionário',
    'funcionarios_editar'    => 'Editar funcionário',
    'funcionarios_detalhar'  => 'Detalhes do funcionário',
    'servicos'           => 'Serviços',
    'vendas'             => 'Vendas',
    'pedidos'            => 'Vendas',
    'pedidos_cadastrar'  => 'Cadastrar venda',
    'pedidos_editar'     => 'Editar venda',
];
$titulo_pagina = $titulos[$pagina] ?? 'Dashboard';

// Quem está logado: funcionário (nome + cargo) ou o próprio estabelecimento (dono)
include_once "includes/permissoes.php";
$usuario_func = funcionario_logado();
$nome_exibido = $usuario_func['nome'] ?? ($_SESSION['nome_fantasia'] ?? 'Estabelecimento');
$cargo_exibido = $usuario_func['cargo'] ?? 'Gerente';
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<header class="topbar">
    <h1><?= htmlspecialchars($titulo_pagina) ?></h1>

    <div class="mt-3 perfil-menu">
        <button class="d-flex justify-content-between align-items-center p-3 select" type="button"
                data-bs-toggle="collapse" data-bs-target="#perfil" aria-expanded="false" aria-controls="perfil"
                style="background-color:white;">
            <div class="user d-flex align-items-center gap-2">
                <img src="<?= !empty($_SESSION['foto']) ? 'assets/' . htmlspecialchars($_SESSION['foto']) : 'https://i.imgur.com/vzaeaUi.png' ?>" alt="Avatar image">
                <div>
                    <p class="name mb-0"><?= htmlspecialchars($nome_exibido) ?></p>
                    <p class="plan mb-0"><?= htmlspecialchars($cargo_exibido) ?></p>
                </div>
            </div>
        </button>
        <div class="collapse" id="perfil">
            <ul>
                <?php if (pode_acessar('conta')): ?>
                    <li><a href="dashboard.php?pagina=editar-perfil&idestabelecimento=<?=$_SESSION['estabelecimento_id']?>">Editar perfil <span class="material-symbols-outlined icon-perfil editar">edit_square</span></a></li>
                <?php endif; ?>
                <li><a href="dashboard.php?pagina=logout">Sair<span class="material-symbols-outlined icon-perfil sair">exit_to_app</span></a></li>
                
            </ul>
        </div>
    </div>
</header>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
