<?php
// Funções pequenas usadas nas telas de funcionários

// Confere os 2 dígitos verificadores do CPF (mesmo cálculo módulo 11 do CNPJ, só muda os pesos)
function validar_cpf(string $cpf): bool
{
    $cpf = preg_replace('/\D/', '', $cpf);

    // Precisa ter 11 dígitos e não pode ser tudo igual (111.111.111-11 passa na conta, mas não existe)
    if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
        return false;
    }

    for ($t = 9; $t < 11; $t++) {
        $soma = 0;
        for ($i = 0; $i < $t; $i++) {
            $soma += $cpf[$i] * (($t + 1) - $i);
        }
        $digito = ((10 * $soma) % 11) % 10;
        if ((int) $cpf[$t] !== $digito) {
            return false;
        }
    }
    return true;
}

// 12345678901 -> 123.456.789-01
function formatar_cpf(string $cpf): string
{
    return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
}

// 21987654321 -> (21) 98765-4321   |   2132165498 -> (21) 3216-5498
function formatar_telefone(string $tel): string
{
    if (strlen($tel) === 11) {
        return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $tel);
    }
    if (strlen($tel) === 10) {
        return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $tel);
    }
    return $tel;
}

// 1234.5 -> R$ 1.234,50
function formatar_dinheiro($valor): string
{
    return 'R$ ' . number_format((float) $valor, 2, ',', '.');
}

// Lê o POST do formulário de funcionário e já devolve "limpo" (CPF e telefone só com números, salário com ponto)
function ler_dados_funcionario(): array
{
    return [
        'nome'            => trim($_POST['nome'] ?? ''),
        'dt_nasc'         => trim($_POST['dt_nasc'] ?? ''),
        'salario'         => str_replace(',', '.', trim($_POST['salario'] ?? '')),
        'cpf'             => preg_replace('/\D/', '', $_POST['cpf'] ?? ''),
        'email'           => trim($_POST['email'] ?? ''),
        'telefone'        => preg_replace('/\D/', '', $_POST['telefone'] ?? ''),
        'senha'           => trim($_POST['senha'] ?? ''), // mesmo trim() do logar.php
        'cargos_idcargos' => (int) ($_POST['cargo'] ?? 0),
    ];
}

// Devolve a mensagem de erro, ou '' se estiver tudo certo
// $senha_obrigatoria: true no cadastro; false na edição (senha em branco = mantém a atual)
function validar_funcionario(array $d, array $cargos, bool $senha_obrigatoria = true): string
{
    if ($d['nome'] === '') {
        return 'O nome do funcionário é obrigatório.';
    }

    $nasc = DateTime::createFromFormat('Y-m-d', $d['dt_nasc']);
    if (!$nasc || $nasc->format('Y-m-d') !== $d['dt_nasc'] || $nasc > new DateTime() || $nasc->format('Y') < 1900) {
        return 'Data de nascimento inválida.';
    }

    if (!is_numeric($d['salario']) || $d['salario'] < 0 || $d['salario'] > 99999999.99) {
        return 'Salário inválido.';
    }

    if (!validar_cpf($d['cpf'])) {
        return 'CPF inválido.';
    }

    if (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) {
        return 'E-mail inválido.';
    }

    if (strlen($d['telefone']) < 10 || strlen($d['telefone']) > 11) {
        return 'Telefone inválido. Use DDD + número.';
    }

    if ($d['senha'] === '' && $senha_obrigatoria) {
        return 'Informe uma senha para o funcionário.';
    }

    // O cargo precisa ser um dos que existem na tabela cargos
    $ids_cargos = array_column($cargos, 'idcargos');
    if (!in_array($d['cargos_idcargos'], array_map('intval', $ids_cargos), true)) {
        return 'Selecione um cargo.';
    }

    return '';
}