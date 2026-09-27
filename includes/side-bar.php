<?php
$grupo_ativo = match (true) {
    str_starts_with($pagina, 'produtos') => 'produtos',
    default => $pagina,
};
?>
<aside class="sidebar">
    <div class="container logo">
        <a href="login_cadastro/login.php">
            <img src="assets/Logo-hauster.png" alt="Logo Hauster">
        </a>
    </div>

    <div class="container">
        <p class="legend">Principal</p>

        <a href="dashboard.php?pagina=home" class="<?= $grupo_ativo === 'home' ? 'active' : '' ?>">
            <span class="material-symbols-outlined">home</span> Dashboard
        </a>
        <a href="dashboard.php?pagina=produtos" class="<?= $grupo_ativo === 'produtos' ? 'active' : '' ?>">
            <span class="material-symbols-outlined">local_mall</span> Produtos
        </a>
        <a href="dashboard.php?pagina=pets" class="<?= $grupo_ativo === 'pets' ? 'active' : '' ?>">
            <span class="material-symbols-outlined">pets</span> Pets
        </a>
        <a href="dashboard.php?pagina=clientes" class="<?= $grupo_ativo === 'clientes' ? 'active' : '' ?>">
            <span class="material-symbols-outlined">groups</span> Clientes
        </a>
        <a href="dashboard.php?pagina=funcionarios" class="<?= $grupo_ativo === 'funcionarios' ? 'active' : '' ?>">
            <span class="material-symbols-outlined">group</span> Funcionários
        </a>
        <a href="dashboard.php?pagina=servicos" class="<?= $grupo_ativo === 'servicos' ? 'active' : '' ?>">
            <span class="material-symbols-outlined">assignment</span> Serviços
        </a>
        <a href="dashboard.php?pagina=vendas" class="<?= $grupo_ativo === 'vendas' ? 'active' : '' ?>">
            <span class="material-symbols-outlined">attach_money</span> Vendas
        </a>
    </div>
</aside>
