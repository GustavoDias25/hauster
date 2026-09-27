<?php
$titulos = [
    'home'               => 'Dashboard',
    'produtos'           => 'Produtos',
    'produtos_cadastrar' => 'Cadastrar produto',
    'produtos_editar'    => 'Editar produto',
    'pets'               => 'Pets',
    'clientes'           => 'Clientes',
    'funcionarios'       => 'Funcionários',
    'servicos'           => 'Serviços',
    'vendas'             => 'Vendas',
];
$titulo_pagina = $titulos[$pagina] ?? 'Dashboard';
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
                    <p class="name mb-0"><?= htmlspecialchars($_SESSION['nome_fantasia'] ?? 'Estabelecimento') ?></p>
                    <p class="plan mb-0">Business</p>
                </div>
            </div>
        </button>
        <div class="collapse" id="perfil">
    <ul>
        <li><a href="dashboard.php?pagina=editar-perfil&idestabelecimento=<?=$_SESSION['estabelecimento_id']?>">Editar perfil</a></li>
        <li><a href="dashboard.php?pagina=logout">Sair</a></li>
        <li>
            <a href="dashboard.php?pagina=excluir-conta" class="text-danger">Excluir conta</a>
        </li>
    </ul>
</div>
    </div>
</header>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

