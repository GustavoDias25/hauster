<?php
include_once "includes/permissoes.php";

// Mantém o item do menu marcado nas subpáginas (pedidos_cadastrar -> pedidos, etc.)
$grupo_ativo = secao_da_pagina($pagina);

// secao => [ícone, texto]
$menu = [
    'home'         => ['home', 'Dashboard'],
    'produtos'     => ['local_mall', 'Produtos'],
    'funcionarios' => ['group', 'Funcionários'],
    'vendas'      => ['attach_money', 'Vendas'],
    'pets'         => ['pets', 'Pets'],
    'clientes'     => ['groups', 'Clientes'],
    'servicos'     => ['assignment', 'Serviços'],
];
?>
<style>
    /* Item do menu que o cargo não pode acessar */
    .sidebar a.desativado {
        opacity: 0.4;
        cursor: not-allowed;
        pointer-events: none;
    }
</style>
<aside class="sidebar">
    <div class="container logo">
        <a href="dashboard.php?pagina=<?= pode_acessar('home') ? 'home' : '' ?>">
            <img src="assets/Logo-hauster.png" alt="Logo Hauster">
        </a>
    </div>

    <div class="container">
        <?php foreach ($menu as $secao => [$icone, $texto]): ?>
            <?php if (pode_acessar($secao)): ?>
                <a href="dashboard.php?pagina=<?= $secao ?>" class="<?= $grupo_ativo === $secao ? 'active' : '' ?>">
                    <span class="material-symbols-outlined"><?= $icone ?></span> <?= $texto ?>
                </a>
            <?php else: ?>
                <a class="desativado" aria-disabled="true" tabindex="-1">
                    <span class="material-symbols-outlined"><?= $icone ?></span> <?= $texto ?>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</aside>