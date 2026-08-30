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
<header class="topbar">
    <h1><?= htmlspecialchars($titulo_pagina) ?></h1>

    <div class="user">
        <img src="<?= !empty($_SESSION['foto']) ? 'assets/' . htmlspecialchars($_SESSION['foto']) : 'https://i.imgur.com/vzaeaUi.png' ?>" alt="Avatar image">
        <div>
            <p class="name"><?= htmlspecialchars($_SESSION['nome_fantasia'] ?? 'Estabelecimento') ?></p>
            <p class="plan">Business</p>
        </div>
    </div>
</header>
