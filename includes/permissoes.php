<?php
// 1 Vendedor | 2 Atendente | 3 Veterinário | 4 Tosador | 5 Gerente | 6 Outros
const PERMISSOES_POR_CARGO = [
    1 => ['pedidos', 'produtos'],
    2 => ['servicos'],
    3 => ['servicos', 'clientes', 'pets'],
    4 => ['servicos', 'clientes', 'pets'],
    5 => ['*'],      
    6 => [],
];

// Para onde mandar quem tenta abrir uma página sem permissão (primeira liberada, nesta ordem)
const ORDEM_PAGINAS_INICIAIS = ['home', 'pedidos', 'produtos', 'servicos', 'clientes', 'pets', 'funcionarios'];

// true = quem logou foi o estabelecimento (dono); false = foi um funcionário
function e_dono(): bool
{
    return empty($_SESSION['funcionario_id']);
}

// Dados do funcionário logado (nome e cargo), ou null se for o dono
function funcionario_logado(): ?array
{
    global $conexao;
    static $cache = false; // false = ainda não consultou

    if (e_dono()) {
        return null;
    }
    if ($cache !== false) {
        return $cache;
    }

    $stmt = $conexao->prepare(
        "SELECT f.idfuncionarios, f.nome, f.cargos_idcargos, c.nome AS cargo
         FROM funcionarios f
         JOIN cargos c ON c.idcargos = f.cargos_idcargos
         WHERE f.idfuncionarios = :id AND f.estabelecimento_idestabelecimento = :estab"
    );
    $stmt->execute([
        ':id'    => (int) $_SESSION['funcionario_id'],
        ':estab' => (int) ($_SESSION['estabelecimento_id'] ?? 0),
    ]);

    // Não achou (funcionário excluído?): cargo 0 não está em PERMISSOES_POR_CARGO = sem acesso
    $cache = $stmt->fetch() ?: ['idfuncionarios' => 0, 'nome' => '', 'cargos_idcargos' => 0, 'cargo' => ''];
    return $cache;
}

function permissoes_do_usuario(): array
{
    if (e_dono()) {
        return ['*'];
    }
    return PERMISSOES_POR_CARGO[(int) funcionario_logado()['cargos_idcargos']] ?? [];
}

// Dono e Gerente: acesso a todas as telas
function e_gerente(): bool
{
    return in_array('*', permissoes_do_usuario(), true);
}

function pode_acessar(string $secao): bool
{
    if ($secao === 'livre') {
        return true;
    }
    // Editar perfil / excluir conta do estabelecimento: só o dono
    if ($secao === 'conta') {
        return e_dono();
    }
    $permitidas = permissoes_do_usuario();
    return in_array('*', $permitidas, true) || in_array($secao, $permitidas, true);
}

// 'pedidos_cadastrar' -> 'pedidos' | 'funcionarios_detalhar' -> 'funcionarios'
function secao_da_pagina(string $pagina): string
{
    if ($pagina === 'logout') {
        return 'livre';
    }
    if ($pagina === 'editar-perfil' || $pagina === 'excluir-conta') {
        return 'conta';
    }
    return explode('_', $pagina)[0];
}

// Chame no começo do dashboard.php: se não puder, redireciona (antes de imprimir qualquer coisa)
function exigir_permissao(string $pagina): void
{
    if (pode_acessar(secao_da_pagina($pagina))) {
        return;
    }

    foreach (ORDEM_PAGINAS_INICIAIS as $secao) {
        if (pode_acessar($secao)) {
            header('Location: dashboard.php?pagina=' . $secao);
            exit;
        }
    }

    // Nenhuma tela liberada para esse usuário: volta pro login
    session_unset();
    $_SESSION['erro_login'] = 'Seu cargo não tem acesso ao sistema.';
    header('Location: login_cadastro/login.php');
    exit;
}
